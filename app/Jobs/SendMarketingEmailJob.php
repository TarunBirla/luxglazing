<?php

namespace App\Jobs;

use App\Models\MarketingEmail;
use App\Models\MarketingCampaign;
use App\Services\Marketing\AwsSesService;
use App\Services\Marketing\OpenAiMarketingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendMarketingEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public array $backoff = [30, 120, 300]; // Exponential backoff in seconds

    protected int $emailId;

    public function __construct(int $emailId)
    {
        $this->emailId = $emailId;
    }

    public function handle(AwsSesService $sesService, OpenAiMarketingService $aiService): void
    {
        $email = MarketingEmail::with(['campaign', 'contact'])->find($this->emailId);
        if (!$email) {
            return;
        }

        $campaign = $email->campaign;

        // Check if campaign is paused or cancelled
        if (in_array($campaign->status, ['paused', 'cancelled', 'draft'])) {
            $email->update(['status' => 'pending']);
            return;
        }

        // Prevent race conditions between queue workers: atomic lock update
        $affected = DB::table('marketing_emails')
            ->where('id', $email->id)
            ->whereIn('status', ['pending', 'generated', 'queued'])
            ->update([
                'status' => 'processing',
                'updated_at' => now(),
            ]);

        if ($affected === 0) {
            // Already picked up by another worker or already sent
            return;
        }

        $email->refresh();

        // 1. Ensure body content is generated if missing
        if (empty($email->body_html)) {
            $generated = $aiService->getOrGenerateEmail($campaign, $email->contact);
            $email->body_html = $generated->body_html;
            $email->subject = $generated->subject;
            $email->save();
        }

        // 2. Dispatch via Amazon SES
        $result = $sesService->sendMarketingEmail($email);

        if (!$result['success'] && ($result['status'] ?? '') !== 'suppressed') {
            // Only retry temporary transport errors, do not retry permanent suppression
            throw new \Exception("SES Delivery failed: " . ($result['error'] ?? 'Unknown transport error'));
        }
    }

    public function failed(Throwable $exception): void
    {
        $email = MarketingEmail::find($this->emailId);
        if ($email && $email->status !== 'suppressed') {
            $email->update([
                'status' => 'failed',
                'error_message' => 'Job exceeded retry limits: ' . $exception->getMessage(),
            ]);
        }

        Log::error("SendMarketingEmailJob failed for Email #{$this->emailId}: " . $exception->getMessage());
    }
}
