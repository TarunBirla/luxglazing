<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\MarketingContact;
use App\Models\MarketingCampaign;
use App\Models\MarketingEmail;
use App\Models\MarketingSuppression;
use App\Models\MarketingEmailEvent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MarketingDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Date filtering
        $range = $request->input('range', '30_days');
        $startDate = match ($range) {
            '7_days' => now()->subDays(7),
            '30_days' => now()->subDays(30),
            '90_days' => now()->subDays(90),
            'all' => Carbon::createFromTimestamp(0),
            default => now()->subDays(30),
        };

        // Core Metrics
        $totalContacts = MarketingContact::count();
        $subscribedContacts = MarketingContact::where('subscription_status', 'subscribed')->count();
        $suppressedCount = MarketingSuppression::count();
        $totalCampaigns = MarketingCampaign::count();

        // Email Counts
        $emailsGenerated = MarketingEmail::where('created_at', '>=', $startDate)->count();
        $emailsQueued = MarketingEmail::where('status', 'queued')->count();
        $emailsSent = MarketingEmail::whereIn('status', ['sent', 'delivered', 'opened', 'clicked'])->where('created_at', '>=', $startDate)->count();
        $emailsDelivered = MarketingEmail::whereIn('status', ['delivered', 'opened', 'clicked'])->where('created_at', '>=', $startDate)->count();
        $emailsOpened = MarketingEmail::whereIn('status', ['opened', 'clicked'])->where('created_at', '>=', $startDate)->count();
        $emailsClicked = MarketingEmail::where('status', 'clicked')->where('created_at', '>=', $startDate)->count();
        $emailsBounced = MarketingEmail::where('status', 'bounced')->where('created_at', '>=', $startDate)->count();
        $emailsComplained = MarketingEmail::where('status', 'complained')->where('created_at', '>=', $startDate)->count();

        // Rate Calculations
        $openRate = $emailsDelivered > 0 ? round(($emailsOpened / $emailsDelivered) * 100, 1) : 0.0;
        $clickRate = $emailsDelivered > 0 ? round(($emailsClicked / $emailsDelivered) * 100, 1) : 0.0;
        $bounceRate = $emailsSent > 0 ? round(($emailsBounced / $emailsSent) * 100, 2) : 0.0;
        $complaintRate = $emailsSent > 0 ? round(($emailsComplained / $emailsSent) * 100, 2) : 0.0;

        // OpenAI API Usage & Costs
        $openaiTokens = MarketingCampaign::sum('openai_tokens_used');
        $openaiCostUsd = MarketingCampaign::sum('openai_cost_usd');

        // Recent Campaigns
        $campaigns = MarketingCampaign::latest()->take(8)->get();

        // Recent Email Events
        $recentEvents = MarketingEmailEvent::latest()->take(10)->get();

        // AWS SES statistics
        $sesStats = [
            'region' => config('services.ses.region', 'ap-south-1'),
            'sending_domain' => config('services.ses.sending_domain', 'marketing.nimadtech.in'),
            'configuration_set' => config('services.ses.configuration_set', 'marketing-tracking'),
            'rate_limit' => config('services.ses.rate_per_second', 14) . ' emails/sec',
            'daily_quota' => number_format(config('services.ses.daily_quota', 50000)) . ' emails/day',
            'status' => 'Active & Connected',
        ];

        return view('marketing.dashboard', compact(
            'range',
            'totalContacts',
            'subscribedContacts',
            'suppressedCount',
            'totalCampaigns',
            'emailsGenerated',
            'emailsQueued',
            'emailsSent',
            'emailsDelivered',
            'emailsOpened',
            'emailsClicked',
            'emailsBounced',
            'emailsComplained',
            'openRate',
            'clickRate',
            'bounceRate',
            'complaintRate',
            'openaiTokens',
            'openaiCostUsd',
            'campaigns',
            'recentEvents',
            'sesStats'
        ));
    }
}
