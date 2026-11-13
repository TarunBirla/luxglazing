<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Services\OpenAiGlazingService;

class LeadController extends Controller
{
    protected OpenAiGlazingService $aiService;

    public function __construct(OpenAiGlazingService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'postcode' => 'nullable|string|max:20',
            'service_type' => 'required|string',
            'project_type' => 'nullable|string',
            'estimated_budget' => 'nullable|string',
            'timeframe' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Run AI Lead Qualification & Scoring
        $aiResult = $this->aiService->qualifyAndScoreLead($validated);

        $lead = Lead::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'postcode' => $validated['postcode'] ?? null,
            'service_type' => $validated['service_type'],
            'project_type' => $validated['project_type'] ?? 'Residential',
            'estimated_budget' => $validated['estimated_budget'] ?? '£5,000 - £15,000',
            'timeframe' => $validated['timeframe'] ?? '1-3 months',
            'stage' => 'New Lead',
            'source' => $request->input('source', 'Website Form'),
            'ai_score' => $aiResult['score'],
            'ai_classification' => $aiResult['classification'],
            'ai_qualification_summary' => $aiResult['summary'],
            'ai_recommended_action' => $aiResult['recommended_action'],
            'estimated_value' => $aiResult['estimated_value'],
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your quotation enquiry has been registered.',
                'lead_id' => $lead->id,
                'ai_classification' => $lead->ai_classification,
            ]);
        }

        return redirect()->back()->with('success', 'Lead created successfully with AI Score ' . $lead->ai_score . '/100 (' . $lead->ai_classification . ')');
    }

    public function updateStage(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $lead->stage = $request->input('stage', $lead->stage);
        if ($request->has('assigned_to')) {
            $lead->assigned_to = $request->input('assigned_to');
        }
        $lead->save();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'lead' => $lead]);
        }

        return redirect()->back()->with('success', 'Lead stage updated to ' . $lead->stage);
    }

    public function rescore(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $aiResult = $this->aiService->qualifyAndScoreLead($lead->toArray());

        $lead->ai_score = $aiResult['score'];
        $lead->ai_classification = $aiResult['classification'];
        $lead->ai_qualification_summary = $aiResult['summary'];
        $lead->ai_recommended_action = $aiResult['recommended_action'];
        $lead->estimated_value = $aiResult['estimated_value'];
        $lead->save();

        return response()->json([
            'success' => true,
            'ai_score' => $lead->ai_score,
            'ai_classification' => $lead->ai_classification,
            'summary' => $lead->ai_qualification_summary,
            'recommended_action' => $lead->ai_recommended_action,
        ]);
    }
}
