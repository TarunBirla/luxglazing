<?php

namespace App\Services\Marketing;

use App\Models\MarketingContact;
use App\Models\MarketingCampaign;
use App\Models\MarketingEmail;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class OpenAiMarketingService
{
    protected ?string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.openai.key') ?: env('OPENAI_API_KEY') ?: SystemSetting::get('openai_api_key');
        $this->model = config('services.openai.model') ?: SystemSetting::get('openai_model', 'gpt-4o');
    }

    /**
     * Generate or retrieve existing personalized email content for a contact & campaign.
     * Prevents re-generation on retries!
     */
    public function getOrGenerateEmail(MarketingCampaign $campaign, MarketingContact $contact): MarketingEmail
    {
        // 1. Check if an email draft already exists for this contact in this campaign
        $existing = MarketingEmail::where('campaign_id', $campaign->id)
            ->where('contact_id', $contact->id)
            ->first();

        if ($existing && !empty($existing->body_html)) {
            return $existing;
        }

        // 2. Decide based on campaign sending mode: AI, Template or Both
        if ($campaign->sending_mode === 'template' && $campaign->template) {
            $rendered = $campaign->template->render($contact);
            $subject = $rendered['subject'];
            $html = $rendered['html'];
            $tokens = 0;
            $mode = 'template';
            $prompt = 'Template rendered via MarketingTemplate #' . $campaign->template_id;
        } else {
            // Generate using OpenAI AI Engine
            $aiData = $this->generatePersonalizedContent($campaign, $contact);
            $subject = $aiData['subject'];
            $html = $aiData['html'];
            $tokens = $aiData['tokens'];
            $mode = 'ai';
            $prompt = $aiData['prompt'];

            // Atomically track campaign OpenAI usage
            $campaign->increment('openai_tokens_used', $tokens);
            // Estimate cost: approx $0.005 per 1,000 tokens for GPT-4o
            $cost = ($tokens / 1000) * 0.005;
            $campaign->increment('openai_cost_usd', $cost);
        }

        // 3. Save generated email content in database BEFORE queueing or sending
        $email = MarketingEmail::updateOrCreate(
            [
                'campaign_id' => $campaign->id,
                'contact_id' => $contact->id,
            ],
            [
                'recipient_email' => $contact->email,
                'recipient_name' => $contact->name,
                'subject' => $subject,
                'body_html' => $html,
                'body_text' => strip_tags($html),
                'status' => 'generated',
                'generation_mode' => $mode,
                'ai_prompt_used' => $prompt,
                'openai_tokens' => $tokens,
            ]
        );

        $campaign->increment('emails_generated');

        return $email;
    }

    /**
     * Generate personalized high-converting content with OpenAI
     */
    public function generatePersonalizedContent(MarketingCampaign $campaign, MarketingContact $contact): array
    {
        $prompt = "You are an expert UK Architectural Glazing and High-End Commercial Marketing Director.
Generate a concise, highly personalized B2B marketing introduction email tailored to this specific contact:

Contact Name: {$contact->name}
Company Name: " . ($contact->company ?? 'Your Practice') . "
Business Type/Industry: " . ($contact->business_type ?? 'Architect / Developer') . "
Website: " . ($contact->website ?? 'Not specified') . "
Campaign Objective: {$campaign->purpose}
Product Details: {$campaign->content}

STRICT CONSTRAINTS:
1. Approximately 100 words in length.
2. British English spelling and sophisticated, respectful business tone.
3. No exaggerated claims, false statistics, or misleading statements.
4. Clear, soft call to action (e.g. review specification catalogue, request trade sample, or 10-min project review).
5. Highlight relevant Lux Glazing systems (Cortizo slimline sliding doors, Schüco bifolds, heritage steel-look, or structural glazing).

Respond STRICTLY in JSON format with keys:
- subject: A compelling, relevant subject line (under 60 characters)
- email_html: Clean HTML formatted email body with <p> and <ul> tags.";

        if (!empty($this->apiKey)) {
            try {
                $response = Http::withToken($this->apiKey)
                    ->timeout(20)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => $this->model,
                        'messages' => [
                            ['role' => 'system', 'content' => 'You write authentic, personalized B2B architectural marketing communications.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'response_format' => ['type' => 'json_object'],
                        'temperature' => 0.4,
                    ]);

                if ($response->successful()) {
                    $json = $response->json('choices.0.message.content');
                    $usage = $response->json('usage.total_tokens', 250);
                    $parsed = json_decode($json, true);

                    if ($parsed && !empty($parsed['subject']) && !empty($parsed['email_html'])) {
                        return [
                            'subject' => $parsed['subject'],
                            'html' => $parsed['email_html'],
                            'tokens' => (int) $usage,
                            'prompt' => $prompt,
                        ];
                    }
                }
            } catch (Throwable $e) {
                Log::warning("OpenAI Marketing generation fallback: " . $e->getMessage());
            }
        }

        // Intelligent high-converting fallback when OpenAI is offline or API key is not configured
        $companyName = $contact->company ?: 'your practice';
        $industry = $contact->business_type ?: 'architectural';
        $subject = "Architectural Glazing Collaboration: Lux Glazing & {$companyName}";

        $html = "<p>Dear {$contact->name},</p>
<p>I hope this finds you well.</p>
<p>Having followed {$companyName}'s notable {$industry} portfolio, we wanted to briefly introduce <strong>Lux Glazing UK</strong>. We supply and install bespoke, precision-engineered architectural glass systems tailored specifically for UK specifiers:</p>
<ul>
    <li><strong>Ultra-Slim Sliding Doors</strong> – Cortizo Cor Vision Plus with 20mm minimalist sightlines.</li>
    <li><strong>Architectural Bi-Folds</strong> – Thermally broken Schüco suites compliant with Part L & Part Q.</li>
    <li><strong>Heritage Steel-Look Screens</strong> – Slender acoustic glazing bars for contemporary zoning.</li>
</ul>
<p>We provide full NBS specifications, CAD/DWG support, and dedicated trade rate pricing.</p>
<p>Could we send across our latest 2026 Specifiers Guide, or review any upcoming schemes with your team?</p>
<p>Kind regards,<br>
<strong>Commercial Partnerships Team</strong><br>
Lux Glazing UK<br>
<a href='https://luxglazing.co.uk/' style='color: #E5D2A5;'>https://luxglazing.co.uk/</a></p>";

        return [
            'subject' => $subject,
            'html' => $html,
            'tokens' => 220,
            'prompt' => 'Heuristic Architectural Fallback',
        ];
    }
}
