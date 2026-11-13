<?php

namespace App\Jobs;

use App\Models\MarketingCampaign;
use App\Models\MarketingContact;
use App\Models\MarketingSuppression;
use App\Services\Marketing\OpenAiMarketingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DispatchCampaignBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $campaignId;
    protected int $batchSize;

    public function __construct(int $campaignId, int $batchSize = 250)
    {
        $this->campaignId = $campaignId;
        $this->batchSize = $batchSize;
    }

    public function handle(OpenAiMarketingService $aiService): void
    {
        $campaign = MarketingCampaign::find($this->campaignId);
        if (!$campaign || in_array($campaign->status, ['paused', 'cancelled', 'completed'])) {
            return;
        }

        $campaign->update([
            'status' => 'sending',
            'started_at' => $campaign->started_at ?: now(),
        ]);

        // Base query for eligible contacts
        $query = MarketingContact::where('consent_status', true)
            ->where('subscription_status', 'subscribed');

        // Apply campaign target criteria if any
        if (!empty($campaign->target_criteria)) {
            $criteria = $campaign->target_criteria;
            if (!empty($criteria['business_type'])) {
                $query->where('business_type', $criteria['business_type']);
            }
            if (!empty($criteria['verification_status'])) {
                $query->where('verification_status', $criteria['verification_status']);
            }
        }

        // Get count
        $total = $query->count();
        $campaign->update(['total_contacts' => $total]);

        // Rate limiter configuration: default 14 emails per second
        $ratePerSecond = config('services.ses.rate_per_second', 14);
        $delaySeconds = 0;

        // Process in memory-efficient chunks
        $query->chunkById($this->batchSize, function ($contacts) use ($campaign, $aiService, $ratePerSecond, &$delaySeconds) {
            foreach ($contacts as $contact) {
                // If campaign was paused or cancelled during processing, stop dispatching
                $campaign->refresh();
                if (in_array($campaign->status, ['paused', 'cancelled'])) {
                    return false;
                }

                // Check central suppression list before generating
                if (MarketingSuppression::isSuppressed($contact->email)) {
                    continue;
                }

                // Get or generate email content
                $email = $aiService->getOrGenerateEmail($campaign, $contact);

                if (in_array($email->status, ['sent', 'delivered'])) {
                    continue; // Skip already sent emails
                }

                $email->update(['status' => 'queued']);
                $campaign->increment('emails_queued');

                // Stagger dispatch queue delay according to SES per-second quota
                $delayInterval = floor($delaySeconds / max(1, $ratePerSecond));
                SendMarketingEmailJob::dispatch($email->id)->delay(now()->addSeconds($delayInterval));

                $delaySeconds++;
            }
        });
    }
}
