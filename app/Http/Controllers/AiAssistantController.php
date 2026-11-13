<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAiGlazingService;
use App\Models\Lead;
use App\Models\SystemSetting;

class AiAssistantController extends Controller
{
    protected OpenAiGlazingService $aiService;

    public function __construct(OpenAiGlazingService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Public AI Website Chatbot endpoint
     */
    public function chat(Request $request)
    {
        $message = $request->input('message', '');
        $history = $request->input('history', []);

        if (empty($message)) {
            return response()->json(['reply' => 'How can Lux Glazing assist you with your architectural glazing project today?']);
        }

        $result = $this->aiService->chatbotReply($history, $message);

        // Auto-create lead if contact detected
        if (!empty($result['phone'])) {
            $service = $result['detected_service'] ?? 'Architectural Glazing';
            $postcode = $result['postcode'] ?? 'UK';

            Lead::create([
                'name' => 'Web Chat Prospect (' . ($result['phone']) . ')',
                'phone' => $result['phone'],
                'postcode' => $postcode,
                'service_type' => $service,
                'project_type' => 'Residential',
                'stage' => 'New Lead',
                'source' => 'Website Chatbot',
                'ai_score' => 85,
                'ai_classification' => 'Hot',
                'ai_qualification_summary' => "Enquiry captured via Website AI Chatbot for {$service} in area {$postcode}.",
                'ai_recommended_action' => 'Call within 15 minutes while client is engaged online.',
                'estimated_value' => 12500.00,
                'notes' => 'User prompt: ' . $message,
            ]);
        }

        return response()->json([
            'reply' => $result['reply'],
            'detected' => [
                'service' => $result['detected_service'],
                'phone' => $result['phone'],
                'postcode' => $result['postcode'],
            ],
        ]);
    }

    /**
     * Generate AI Email Content
     */
    public function generateEmail(Request $request)
    {
        $type = $request->input('type', 'quote_followup');
        $recipientName = $request->input('recipient_name', 'Valued Customer');
        $projectName = $request->input('project_name', 'Architectural Glazing Package');
        $customDetails = $request->input('custom_details', '');

        if ($type === 'quote_followup') {
            $subject = "Following Up: Your Bespoke Glazing Proposal – Lux Glazing UK";
            $body = "Dear {$recipientName},\n\n" .
                    "I hope you are having a pleasant week.\n\n" .
                    "I am following up regarding the quotation we prepared for your {$projectName}.\n\n" .
                    "At Lux Glazing, every aluminium window, bifold and sliding door suite is manufactured to bespoke millimetre specifications with premium thermal breaks and multi-point security. Our quotes include our comprehensive 10-year guarantee and full FENSA certification.\n\n" .
                    ($customDetails ? "{$customDetails}\n\n" : "") .
                    "Have you had a chance to review the specification breakdown? I would be delighted to answer any technical questions or arrange for one of our senior surveyors to carry out a final laser site measure.\n\n" .
                    "Warm regards,\n\n" .
                    "Oliver Davies\n" .
                    "Senior Glazing Consultant | Lux Glazing UK\n" .
                    "020 7946 0991 | https://luxglazing.nexteck.uk/";
        } elseif ($type === 'survey_confirmation') {
            $subject = "Site Survey Confirmation – Lux Glazing UK";
            $body = "Dear {$recipientName},\n\n" .
                    "Thank you for choosing Lux Glazing. This is to confirm your upcoming technical laser site survey for your {$projectName}.\n\n" .
                    "Our senior surveyor will inspect structural openings, laser-level floor finishes (for flush thresholds), verify access, and confirm frame colour RAL swatches on site with you.\n\n" .
                    ($customDetails ? "Survey notes: {$customDetails}\n\n" : "") .
                    "Should you need to reschedule or have any questions ahead of the visit, please do not hesitate to contact our technical team directly.\n\n" .
                    "Best regards,\n\n" .
                    "Technical Survey Department | Lux Glazing UK";
        } else {
            $subject = "Architectural Aluminium Glazing Partnership – Lux Glazing";
            $body = "Dear {$recipientName},\n\n" .
                    "We are pleased to introduce Lux Glazing UK's Trade & Architect Partner Network.\n\n" .
                    "We support RIBA architects, high-end developers, and contractors with precision-engineered aluminium bifolds, minimal-frame sliding systems, and heritage steel-look solutions across the UK.\n\n" .
                    "Let us know if we can assist with your current project specifications or provide trade rate cards.\n\n" .
                    "Kind regards,\nCommercial Partnerships | Lux Glazing UK";
        }

        return response()->json([
            'success' => true,
            'subject' => $subject,
            'body' => $body,
        ]);
    }

    /**
     * Save OpenAI API Settings
     */
    public function saveSettings(Request $request)
    {
        if ($request->has('openai_api_key')) {
            SystemSetting::set('openai_api_key', $request->input('openai_api_key'), 'ai');
        }
        if ($request->has('openai_model')) {
            SystemSetting::set('openai_model', $request->input('openai_model'), 'ai');
        }

        return redirect()->back()->with('success', 'AI & OpenAI settings saved successfully.');
    }
}
