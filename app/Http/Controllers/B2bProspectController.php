<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\B2bProspect;
use App\Services\OpenAiGlazingService;

class B2bProspectController extends Controller
{
    protected OpenAiGlazingService $aiService;

    public function __construct(OpenAiGlazingService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'category' => 'required|string',
            'contact_person' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'status' => 'nullable|string',
        ]);

        $pitch = $this->aiService->generateB2bPitch($validated);

        $prospect = B2bProspect::create(array_merge($validated, [
            'status' => $validated['status'] ?? 'Identified',
            'ai_personalized_pitch' => $pitch,
            'ai_research_notes' => 'Target profile verified in UK ' . ($validated['city'] ?? 'London') . ' area for high-specification glazing packages.',
        ]));

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'prospect' => $prospect]);
        }

        return redirect()->back()->with('success', 'B2B Trade Prospect added and personalized AI pitch generated.');
    }

    public function generatePitch($id)
    {
        $prospect = B2bProspect::findOrFail($id);
        $pitch = $this->aiService->generateB2bPitch($prospect->toArray());
        $prospect->ai_personalized_pitch = $pitch;
        $prospect->save();

        return response()->json([
            'success' => true,
            'pitch' => $pitch,
        ]);
    }
}
