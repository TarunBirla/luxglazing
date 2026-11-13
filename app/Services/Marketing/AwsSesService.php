<?php

namespace App\Services\Marketing;

use App\Models\MarketingEmail;
use App\Models\MarketingEmailEvent;
use App\Models\MarketingSuppression;
use App\Models\MarketingCampaign;
use App\Models\MarketingContact;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Mime\Email;
use Throwable;

class AwsSesService
{
    protected string $region;
    protected string $configurationSet;
    protected string $senderEmail;
    protected string $senderName;
    protected string $sendingDomain;

    public function __construct()
    {
        $this->region = config('services.ses.region', 'ap-south-1');
        $this->configurationSet = config('services.ses.configuration_set', 'marketing-tracking');
        $this->senderEmail = env('MAIL_FROM_ADDRESS', 'marketing@marketing.nimadtech.in');
        $this->senderName = env('MAIL_FROM_NAME', 'Lux Glazing Architectural Marketing');
        $this->sendingDomain = config('services.ses.sending_domain', 'marketing.nimadtech.in');
    }

    /**
     * Send marketing email with Amazon SES, RFC 8058 One-Click Unsubscribe headers
     * and Configuration Set tracking.
     */
    public function sendMarketingEmail(MarketingEmail $email): array
    {
        $contact = $email->contact;

        // Central suppression verification before every single send
        if (MarketingSuppression::isSuppressed($email->recipient_email)) {
            $email->update([
                'status' => 'suppressed',
                'error_message' => 'Suppressed: Recipient email is present in central suppression list.',
            ]);

            return ['success' => false, 'status' => 'suppressed', 'reason' => 'Email is suppressed.'];
        }

        if (!$contact->canReceiveMarketing()) {
            $email->update([
                'status' => 'suppressed',
                'error_message' => 'Not permitted: Contact lacks documented marketing consent or has unsubscribed.',
            ]);

            return ['success' => false, 'status' => 'suppressed', 'reason' => 'Contact unverified or consent revoked.'];
        }

        $unsubscribeUrl = $contact->unsubscribe_url;
        $oneClickPostUrl = route('marketing.unsubscribe.one-click', ['token' => $contact->unsubscribe_token]);

        $senderEmail = $email->campaign->sender_email ?: $this->senderEmail;
        $senderName = $email->campaign->sender_name ?: $this->senderName;

        try {
            // Append clean, compliant footer with physical address, consent note and unsubscribe link
            $htmlWithFooter = $this->appendComplianceFooter($email->body_html, $unsubscribeUrl);
            $textWithFooter = ($email->body_text ?: strip_tags($email->body_html)) . "\n\n---\nTo unsubscribe, click: {$unsubscribeUrl}";

            $sesMessageId = null;

            // Send via Laravel Mailer configured with SES / SMTP
            Mail::send([], [], function ($message) use ($email, $senderEmail, $senderName, $htmlWithFooter, $textWithFooter, $unsubscribeUrl, $oneClickPostUrl, &$sesMessageId) {
                $message->to($email->recipient_email, $email->recipient_name)
                    ->from($senderEmail, $senderName)
                    ->subject($email->subject)
                    ->html($htmlWithFooter)
                    ->text($textWithFooter);

                // Add RFC 8058 and SES tracking headers via Symfony Email
                $symfonyMessage = $message->getSymfonyMessage();
                if ($symfonyMessage instanceof Email) {
                    $headers = $symfonyMessage->getHeaders();

                    // Amazon SES Configuration Set header
                    if (!empty($this->configurationSet)) {
                        $headers->addTextHeader('X-SES-CONFIGURATION-SET', $this->configurationSet);
                    }

                    // RFC 8058 List-Unsubscribe & List-Unsubscribe-Post
                    $headers->addTextHeader('List-Unsubscribe', "<{$unsubscribeUrl}>, <{$oneClickPostUrl}>");
                    $headers->addTextHeader('List-Unsubscribe-Post', 'List-Unsubscribe=One-Click');

                    // Campaign & Tracking metadata
                    $headers->addTextHeader('X-Campaign-ID', (string) $email->campaign_id);
                    $headers->addTextHeader('X-Email-ID', (string) $email->id);
                }
            });

            // In SMTP or SES driver, generate/capture message ID reference
            $sesMessageId = 'SES-' . $this->region . '-' . uniqid('', true) . '@' . $this->sendingDomain;

            $email->update([
                'status' => 'sent',
                'ses_message_id' => $sesMessageId,
                'sent_at' => now(),
                'error_message' => null,
            ]);

            // Record Send Event
            MarketingEmailEvent::create([
                'campaign_id' => $email->campaign_id,
                'email_id' => $email->id,
                'contact_id' => $email->contact_id,
                'ses_message_id' => $sesMessageId,
                'event_type' => 'send',
                'event_timestamp' => now(),
                'recipient_email' => $email->recipient_email,
                'metadata' => [
                    'sender' => $senderEmail,
                    'configuration_set' => $this->configurationSet,
                    'region' => $this->region,
                ],
            ]);

            // Increment campaign sent count
            $email->campaign->increment('emails_sent');

            return ['success' => true, 'message_id' => $sesMessageId];

        } catch (Throwable $e) {
            Log::error("AWS SES Send Failure for {$email->recipient_email}: " . $e->getMessage());

            $email->increment('retry_count');
            $email->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Process AWS SES / SNS Event Notifications idempotently
     */
    public function processSesEvent(array $payload): array
    {
        // Handle SNS SubscriptionConfirmation
        if (isset($payload['Type']) && $payload['Type'] === 'SubscriptionConfirmation') {
            if (!empty($payload['SubscribeURL'])) {
                Http::get($payload['SubscribeURL']);
                return ['status' => 'confirmed', 'message' => 'SNS Subscription confirmed.'];
            }
        }

        // Parse Message object if delivered via SNS
        $messageData = $payload;
        if (isset($payload['Message']) && is_string($payload['Message'])) {
            $messageData = json_decode($payload['Message'], true) ?: [];
        }

        $eventType = strtolower($messageData['eventType'] ?? $messageData['notificationType'] ?? 'unknown');
        $mail = $messageData['mail'] ?? [];
        $sesMessageId = $mail['messageId'] ?? null;
        $destination = $mail['destination'][0] ?? null;

        if (!$destination && isset($messageData['bounce']['bouncedRecipients'][0]['emailAddress'])) {
            $destination = $messageData['bounce']['bouncedRecipients'][0]['emailAddress'];
        }

        if (!$destination && isset($messageData['complaint']['complainedRecipients'][0]['emailAddress'])) {
            $destination = $messageData['complaint']['complainedRecipients'][0]['emailAddress'];
        }

        if (!$destination) {
            return ['status' => 'ignored', 'reason' => 'No recipient email found in payload.'];
        }

        // Locate existing MarketingEmail
        $marketingEmail = null;
        if ($sesMessageId) {
            $marketingEmail = MarketingEmail::where('ses_message_id', $sesMessageId)->first();
        }
        if (!$marketingEmail && $destination) {
            $marketingEmail = MarketingEmail::where('recipient_email', $destination)->latest()->first();
        }

        $campaignId = $marketingEmail ? $marketingEmail->campaign_id : null;
        $contactId = $marketingEmail ? $marketingEmail->contact_id : null;

        // Prevent duplicate processing of the same event
        $alreadyLogged = MarketingEmailEvent::where('ses_message_id', $sesMessageId)
            ->where('event_type', $eventType)
            ->where('recipient_email', $destination)
            ->exists();

        if ($alreadyLogged) {
            return ['status' => 'duplicate', 'message' => 'Event already processed.'];
        }

        // Record the event
        MarketingEmailEvent::create([
            'campaign_id' => $campaignId,
            'email_id' => $marketingEmail?->id,
            'contact_id' => $contactId,
            'ses_message_id' => $sesMessageId,
            'event_type' => $eventType,
            'event_timestamp' => now(),
            'recipient_email' => $destination,
            'metadata' => $messageData,
        ]);

        // Process Event Specific Logic
        switch ($eventType) {
            case 'delivery':
                if ($marketingEmail) {
                    $marketingEmail->update(['status' => 'delivered', 'delivered_at' => now()]);
                    $marketingEmail->campaign->increment('emails_delivered');
                }
                break;

            case 'open':
                if ($marketingEmail && !$marketingEmail->opened_at) {
                    $marketingEmail->update(['status' => 'opened', 'opened_at' => now()]);
                    $marketingEmail->campaign->increment('emails_opened');
                }
                break;

            case 'click':
                if ($marketingEmail && !$marketingEmail->clicked_at) {
                    $marketingEmail->update(['status' => 'clicked', 'clicked_at' => now()]);
                    $marketingEmail->campaign->increment('emails_clicked');
                }
                break;

            case 'bounce':
                $bounceType = $messageData['bounce']['bounceType'] ?? 'Permanent';
                $bounceSubType = $messageData['bounce']['bounceSubType'] ?? 'General';

                if ($marketingEmail) {
                    $marketingEmail->update([
                        'status' => 'bounced',
                        'bounced_at' => now(),
                        'bounce_type' => $bounceType,
                        'error_message' => "SES Bounce: {$bounceType} - {$bounceSubType}",
                    ]);
                    $marketingEmail->campaign->increment('emails_bounced');
                }

                // Hard Bounces MUST be automatically suppressed
                if ($bounceType === 'Permanent') {
                    MarketingSuppression::suppress($destination, 'hard_bounce', 'SES Bounce Webhook', $messageData);
                }
                break;

            case 'complaint':
                if ($marketingEmail) {
                    $marketingEmail->update([
                        'status' => 'complained',
                        'complained_at' => now(),
                        'error_message' => 'SES Complaint: Recipient marked email as spam.',
                    ]);
                    $marketingEmail->campaign->increment('emails_complained');
                }

                // Spam complaints MUST be suppressed immediately
                MarketingSuppression::suppress($destination, 'complaint', 'SES Complaint Webhook', $messageData);
                break;
        }

        return ['status' => 'processed', 'event' => $eventType, 'email' => $destination];
    }

    /**
     * Append clean architectural compliance footer with unsubscribe & address
     */
    protected function appendComplianceFooter(string $htmlBody, string $unsubscribeUrl): string
    {
        $domain = $this->sendingDomain;
        $footer = "
        <br><br>
        <div style='margin-top: 32px; padding-top: 18px; border-top: 1px solid #E5D2A5; font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif; font-size: 11px; color: #888888; line-height: 1.5;'>
            <p style='margin: 0 0 6px 0;'>
                You received this commercial communication from <strong>Lux Glazing UK</strong> because of your registered trade or architectural interest.
            </p>
            <p style='margin: 0 0 8px 0;'>
                Registered Office: 45 Curzon Street, Mayfair, London W1J 7UZ | FENSA Registered No. 38492
            </p>
            <p style='margin: 0;'>
                If you no longer wish to receive our architectural updates and project showcases, you can 
                <a href='{$unsubscribeUrl}' style='color: #B89025; text-decoration: underline;'>unsubscribe instantly</a>.
            </p>
        </div>";

        if (str_contains($htmlBody, '</body>')) {
            return str_replace('</body>', $footer . '</body>', $htmlBody);
        }

        return $htmlBody . $footer;
    }
}
