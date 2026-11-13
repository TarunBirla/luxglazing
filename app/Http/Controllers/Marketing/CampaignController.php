<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\MarketingCampaign;
use App\Models\MarketingContact;
use App\Models\MarketingTemplate;
use App\Models\MarketingEmail;
use App\Jobs\DispatchCampaignBatchJob;
use App\Services\Marketing\AwsSesService;
use App\Services\Marketing\OpenAiMarketingService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CampaignController extends Controller
{
    protected AwsSesService $sesService;
    protected OpenAiMarketingService $aiService;

    public function __construct(AwsSesService $sesService, OpenAiMarketingService $aiService)
    {
        $this->sesService = $sesService;
        $this->aiService = $aiService;
    }

    public function index()
    {
        $campaigns = MarketingCampaign::with('template')->latest()->paginate(15);
        return view('marketing.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $templates = MarketingTemplate::where('is_active', true)->get();
        $businessTypes = MarketingContact::select('business_type')
            ->whereNotNull('business_type')
            ->distinct()
            ->pluck('business_type');

        $eligibleContactsCount = MarketingContact::where('consent_status', true)
            ->where('subscription_status', 'subscribed')
            ->count();

        return view('marketing.campaigns.create', compact('templates', 'businessTypes', 'eligibleContactsCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purpose' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'nullable|string',
            'template_id' => 'nullable|exists:marketing_templates,id',
            'sending_mode' => 'required|in:ai_generated,template,both',
            'scheduled_date' => 'nullable|date',
            'scheduled_time' => 'nullable|string',
            'target_business_type' => 'nullable|string',
            'sender_email' => 'nullable|email',
            'sender_name' => 'nullable|string|max:100',
        ]);

        $scheduledAt = null;
        if (!empty($validated['scheduled_date'])) {
            $time = $validated['scheduled_time'] ?? '09:00';
            $scheduledAt = Carbon::parse($validated['scheduled_date'] . ' ' . $time);
        }

        $criteria = [];
        if (!empty($validated['target_business_type'])) {
            $criteria['business_type'] = $validated['target_business_type'];
        }

        $campaign = MarketingCampaign::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'purpose' => $validated['purpose'],
            'subject' => $validated['subject'],
            'content' => $validated['content'] ?? null,
            'template_id' => $validated['template_id'] ?? null,
            'sending_mode' => $validated['sending_mode'],
            'status' => $scheduledAt && $scheduledAt->isFuture() ? 'scheduled' : 'draft',
            'scheduled_at' => $scheduledAt,
            'target_criteria' => $criteria,
            'sender_email' => $validated['sender_email'] ?? config('services.ses.sending_domain') ? 'marketing@' . config('services.ses.sending_domain') : null,
            'sender_name' => $validated['sender_name'] ?? 'Lux Glazing Architectural Team',
        ]);

        return redirect()->route('marketing.campaigns.show', $campaign->id)
            ->with('success', "Campaign '{$campaign->name}' created successfully. You can preview, test, or launch it now.");
    }

    public function show($id)
    {
        $campaign = MarketingCampaign::with(['template', 'emails' => function ($q) {
            $q->latest()->take(50);
        }])->findOrFail($id);

        $statusBreakdown = MarketingEmail::where('campaign_id', $campaign->id)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('marketing.campaigns.show', compact('campaign', 'statusBreakdown'));
    }

    public function preview($id)
    {
        $campaign = MarketingCampaign::with('template')->findOrFail($id);

        // Fetch a real sample contact or create mock
        $sampleContact = MarketingContact::first() ?: new MarketingContact([
            'name' => 'Oliver Henderson',
            'company' => 'Henderson Architects RIBA',
            'website' => 'https://hendersonarchitects.co.uk',
            'email' => 'oliver@hendersonarchitects.co.uk',
            'business_type' => 'Architect',
            'unsubscribe_token' => 'sample-preview-token',
        ]);

        if ($campaign->sending_mode === 'template' && $campaign->template) {
            $rendered = $campaign->template->render($sampleContact);
            $subject = $rendered['subject'];
            $html = $rendered['html'];
        } else {
            $aiData = $this->aiService->generatePersonalizedContent($campaign, $sampleContact);
            $subject = $aiData['subject'];
            $html = $aiData['html'];
        }

        return view('marketing.campaigns.preview', compact('campaign', 'sampleContact', 'subject', 'html'));
    }

    public function sendTestEmail(Request $request, $id)
    {
        $campaign = MarketingCampaign::findOrFail($id);
        $testEmail = $request->input('test_email', auth()->user()->email);

        $request->validate([
            'test_email' => 'required|email',
        ]);

        $mockContact = new MarketingContact([
            'name' => 'Valued Specifier (Test Mode)',
            'company' => 'Sample Architectural Studio',
            'website' => 'https://example.co.uk',
            'email' => $testEmail,
            'business_type' => 'Architect',
            'consent_status' => true,
            'subscription_status' => 'subscribed',
            'unsubscribe_token' => 'test-token-' . uniqid(),
        ]);

        $mockEmail = new MarketingEmail([
            'campaign_id' => $campaign->id,
            'contact_id' => 1,
            'recipient_email' => $testEmail,
            'recipient_name' => 'Test Recipient',
            'subject' => '[TEST EMAIL] ' . $campaign->subject,
            'body_html' => $campaign->content ?: '<p>This is a test broadcast for campaign: ' . e($campaign->name) . '</p>',
            'status' => 'pending',
        ]);
        $mockEmail->setRelation('campaign', $campaign);
        $mockEmail->setRelation('contact', $mockContact);

        $result = $this->sesService->sendMarketingEmail($mockEmail);

        if ($result['success']) {
            return back()->with('success', "Test email successfully dispatched to {$testEmail} via Amazon SES.");
        }

        return back()->withErrors(['test_email' => 'SES test dispatch notice: ' . ($result['error'] ?? 'Check SES credentials and verified email status.')]);
    }

    public function launch($id)
    {
        $campaign = MarketingCampaign::findOrFail($id);

        if (in_array($campaign->status, ['sending', 'completed'])) {
            return back()->with('error', 'Campaign is already active or completed.');
        }

        $campaign->update(['status' => 'processing']);

        // Dispatch background job to Redis Queue
        DispatchCampaignBatchJob::dispatch($campaign->id);

        return redirect()->route('marketing.campaigns.show', $campaign->id)
            ->with('success', "Campaign '{$campaign->name}' queued for batch transmission via Redis and Amazon SES.");
    }

    public function pause($id)
    {
        $campaign = MarketingCampaign::findOrFail($id);
        $campaign->update(['status' => 'paused']);

        return back()->with('success', 'Campaign delivery paused. Active queue jobs will halt immediately.');
    }

    public function resume($id)
    {
        $campaign = MarketingCampaign::findOrFail($id);
        $campaign->update(['status' => 'sending']);

        DispatchCampaignBatchJob::dispatch($campaign->id);

        return back()->with('success', 'Campaign resumed. Processing queued recipients.');
    }

    public function cancel($id)
    {
        $campaign = MarketingCampaign::findOrFail($id);
        $campaign->update(['status' => 'cancelled']);

        return back()->with('success', 'Campaign cancelled.');
    }

    public function duplicate($id)
    {
        $original = MarketingCampaign::findOrFail($id);

        $duplicate = $original->replicate([
            'status', 'started_at', 'completed_at',
            'emails_generated', 'emails_queued', 'emails_sent',
            'emails_delivered', 'emails_opened', 'emails_clicked',
            'emails_bounced', 'emails_complained', 'openai_tokens_used', 'openai_cost_usd'
        ]);

        $duplicate->name = $original->name . ' (Copy)';
        $duplicate->status = 'draft';
        $duplicate->save();

        return redirect()->route('marketing.campaigns.show', $duplicate->id)
            ->with('success', "Campaign duplicated successfully as '{$duplicate->name}'.");
    }
}
