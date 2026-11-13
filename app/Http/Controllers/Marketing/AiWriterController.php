<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\MarketingContact;
use App\Models\MarketingCampaign;
use App\Models\MarketingTemplate;
use App\Services\Marketing\OpenAiMarketingService;
use Illuminate\Http\Request;

class AiWriterController extends Controller
{
    protected OpenAiMarketingService $aiService;

    public function __construct(OpenAiMarketingService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $contacts = MarketingContact::take(30)->get();
        $campaigns = MarketingCampaign::take(20)->get();

        return view('marketing.ai-writer.index', compact('contacts', 'campaigns'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'contact_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'purpose' => 'required|string|max:255',
            'product_details' => 'required|string',
        ]);

        $mockContact = new MarketingContact([
            'name' => $request->input('contact_name'),
            'company' => $request->input('company_name'),
            'business_type' => $request->input('business_type'),
            'website' => $request->input('website'),
        ]);

        $mockCampaign = new MarketingCampaign([
            'purpose' => $request->input('purpose'),
            'content' => $request->input('product_details'),
        ]);

        $result = $this->aiService->generatePersonalizedContent($mockCampaign, $mockContact);

        return response()->json([
            'success' => true,
            'subject' => $result['subject'],
            'html' => $result['html'],
            'tokens' => $result['tokens'],
        ]);
    }

    public function saveAsTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
        ]);

        $template = MarketingTemplate::create([
            'name' => $request->input('name'),
            'subject' => $request->input('subject'),
            'body_html' => $request->input('body_html'),
            'category' => 'AI Generated',
            'variables' => ['name', 'company', 'email', 'website', 'unsubscribe_url'],
            'is_active' => true,
        ]);

        return redirect()->route('marketing.templates.index')->with('success', "AI Generated draft saved as template '{$template->name}'.");
    }
}
