<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\B2bProspect;
use App\Models\Quotation;
use App\Models\SeoKeyword;
use App\Models\MarketingMetric;
use App\Models\User;
use App\Models\SystemSetting;
use App\Services\OpenAiGlazingService;

class CrmDashboardController extends Controller
{
    protected OpenAiGlazingService $aiService;

    public function __construct(OpenAiGlazingService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        // 1. Dashboard Metrics
        $totalLeads = Lead::count();
        $hotLeads = Lead::where('ai_classification', 'Hot')->count();
        $pipelineValue = Lead::sum('estimated_value');
        $wonValue = Lead::where('stage', 'Won')->sum('estimated_value');
        $quotationsSent = Quotation::count();
        $b2bProspectsCount = B2bProspect::count();

        // 2. Leads collection
        $leads = Lead::with('assignedAgent')->latest()->get();

        // 3. Pipeline grouped stages
        $pipeline = [
            'New Lead' => Lead::where('stage', 'New Lead')->get(),
            'Contacted' => Lead::where('stage', 'Contacted')->get(),
            'Qualified' => Lead::where('stage', 'Qualified')->get(),
            'Survey Booked' => Lead::where('stage', 'Survey Booked')->get(),
            'Quote Sent' => Lead::where('stage', 'Quote Sent')->get(),
            'Won' => Lead::where('stage', 'Won')->get(),
        ];

        // 4. B2B Trade Prospects
        $b2bProspects = B2bProspect::latest()->get();

        // 5. Quotations
        $quotations = Quotation::with('items')->latest()->get();

        // 6. SEO Keywords
        $seoKeywords = SeoKeyword::orderBy('priority', 'desc')->get();

        // 7. Marketing Channels Performance
        $marketingMetrics = MarketingMetric::all();

        // 8. Sales Reps
        $salesReps = User::all();

        // 9. Settings
        $settings = [
            'company_name' => SystemSetting::get('company_name', 'Lux Glazing UK'),
            'openai_key_configured' => !empty(env('OPENAI_API_KEY')) || !empty(SystemSetting::get('openai_api_key')),
            'openai_model' => SystemSetting::get('openai_model', 'gpt-4o'),
        ];

        return view('crm.dashboard', compact(
            'totalLeads',
            'hotLeads',
            'pipelineValue',
            'wonValue',
            'quotationsSent',
            'b2bProspectsCount',
            'leads',
            'pipeline',
            'b2bProspects',
            'quotations',
            'seoKeywords',
            'marketingMetrics',
            'salesReps',
            'settings'
        ));
    }
}
