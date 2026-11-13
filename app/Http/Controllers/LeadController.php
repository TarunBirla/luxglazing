<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Services\OpenAiGlazingService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Throwable;

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

        // Send Email Notifications (Admin + Client)
        $this->sendLeadEmails($lead);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your quotation enquiry has been registered and our team has been notified.',
                'lead_id' => $lead->id,
                'ai_classification' => $lead->ai_classification,
            ]);
        }

        return redirect()->back()->with('success', 'Thank you! Your enquiry has been received. Our senior glazing consultant will contact you shortly.');
    }

    /**
     * Send email notifications using SMTP settings from .env
     */
    protected function sendLeadEmails(Lead $lead): void
    {
        $adminMail = env('ADMIN_MAIL', 'tarun@thenexteck.com');
        $fromAddress = env('MAIL_FROM_ADDRESS', 'phil.andreson@nexteck.uk');
        $fromName = env('MAIL_FROM_NAME', 'Lux Glazing UK');

        // 1. Notify Admin
        try {
            if (!empty($adminMail)) {
                $subject = "🚨 New Glazing Enquiry: {$lead->name} – {$lead->service_type} (AI Score: {$lead->ai_score}/100 [{$lead->ai_classification}])";
                
                $htmlBody = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #0B0E14; color: #FFFFFF; border: 1px solid #E5D2A5; border-radius: 8px; overflow: hidden;'>
                    <div style='background: #121824; padding: 20px; border-bottom: 2px solid #E5D2A5; text-align: center;'>
                        <h2 style='color: #E5D2A5; margin: 0; font-family: serif; letter-spacing: 2px;'>LUX GLAZING UK</h2>
                        <p style='color: #9CA3AF; margin: 4px 0 0 0; font-size: 12px;'>NEW LEAD NOTIFICATION</p>
                    </div>
                    <div style='padding: 24px;'>
                        <p style='font-size: 15px; margin-top: 0;'>A new high-priority project enquiry has been submitted on the Lux Glazing portal:</p>
                        <table style='width: 100%; border-collapse: collapse; font-size: 13px; color: #E5E7EB; margin-bottom: 20px;'>
                            <tr style='border-bottom: 1px solid rgba(255,255,255,0.1);'><td style='padding: 8px 0; color: #E5D2A5;'>Client Name:</td><td style='padding: 8px 0; font-weight: bold;'>{$lead->name}</td></tr>
                            <tr style='border-bottom: 1px solid rgba(255,255,255,0.1);'><td style='padding: 8px 0; color: #E5D2A5;'>Phone:</td><td style='padding: 8px 0;'><a href='tel:{$lead->phone}' style='color: #E5D2A5; font-weight: bold;'>{$lead->phone}</a></td></tr>
                            <tr style='border-bottom: 1px solid rgba(255,255,255,0.1);'><td style='padding: 8px 0; color: #E5D2A5;'>Email:</td><td style='padding: 8px 0;'>{$lead->email}</td></tr>
                            <tr style='border-bottom: 1px solid rgba(255,255,255,0.1);'><td style='padding: 8px 0; color: #E5D2A5;'>Service Needed:</td><td style='padding: 8px 0; font-weight: bold;'>{$lead->service_type}</td></tr>
                            <tr style='border-bottom: 1px solid rgba(255,255,255,0.1);'><td style='padding: 8px 0; color: #E5D2A5;'>UK Postcode:</td><td style='padding: 8px 0;'>{$lead->postcode}</td></tr>
                            <tr style='border-bottom: 1px solid rgba(255,255,255,0.1);'><td style='padding: 8px 0; color: #E5D2A5;'>Estimated Budget:</td><td style='padding: 8px 0;'>{$lead->estimated_budget}</td></tr>
                            <tr style='border-bottom: 1px solid rgba(255,255,255,0.1);'><td style='padding: 8px 0; color: #E5D2A5;'>Project Timeframe:</td><td style='padding: 8px 0;'>{$lead->timeframe}</td></tr>
                            <tr style='border-bottom: 1px solid rgba(255,255,255,0.1);'><td style='padding: 8px 0; color: #E5D2A5;'>Estimated Value:</td><td style='padding: 8px 0; color: #E5D2A5; font-weight: bold;'>£" . number_format($lead->estimated_value, 2) . "</td></tr>
                            <tr style='border-bottom: 1px solid rgba(255,255,255,0.1);'><td style='padding: 8px 0; color: #E5D2A5;'>AI Lead Score:</td><td style='padding: 8px 0; font-weight: bold;'>{$lead->ai_score}/100 ({$lead->ai_classification})</td></tr>
                        </table>

                        <div style='background: rgba(229, 210, 165, 0.1); border-left: 3px solid #E5D2A5; padding: 12px; margin-bottom: 20px;'>
                            <strong style='color: #E5D2A5; font-size: 12px;'>AI RECOMMENDED ACTION:</strong>
                            <p style='margin: 4px 0 0; font-size: 12px; color: #FFFFFF;'>{$lead->ai_recommended_action}</p>
                        </div>

                        <div style='text-align: center; margin-top: 24px;'>
                            <a href='" . url('/admin/dashboard') . "' style='background: #E5D2A5; color: #000000; font-weight: bold; text-decoration: none; padding: 12px 24px; border-radius: 6px; display: inline-block;'>Open Lead in CRM Dashboard →</a>
                        </div>
                    </div>
                </div>";

                Mail::html($htmlBody, function ($message) use ($adminMail, $fromAddress, $fromName, $subject) {
                    $message->to($adminMail)
                            ->from($fromAddress, $fromName)
                            ->subject($subject);
                });
            }
        } catch (Throwable $e) {
            Log::error("Failed to send admin lead email: " . $e->getMessage());
        }

        // 2. Send Confirmation to Client (if email provided)
        try {
            if (!empty($lead->email)) {
                $clientSubject = "Thank You for Your Glazing Enquiry – Lux Glazing UK";
                $clientBody = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #0B0E14; color: #FFFFFF; border: 1px solid #E5D2A5; border-radius: 8px; overflow: hidden;'>
                    <div style='background: #121824; padding: 24px; border-bottom: 2px solid #E5D2A5; text-align: center;'>
                        <h2 style='color: #E5D2A5; margin: 0; font-family: serif; letter-spacing: 2px;'>LUX GLAZING UK</h2>
                        <p style='color: #9CA3AF; margin: 4px 0 0 0; font-size: 12px;'>ARCHITECTURAL WINDOWS & DOORS</p>
                    </div>
                    <div style='padding: 24px; font-size: 14px; line-height: 1.6; color: #E5E7EB;'>
                        <p style='margin-top: 0;'>Dear {$lead->name},</p>
                        <p>Thank you for requesting an architectural glazing quotation for your <strong>{$lead->service_type}</strong> project.</p>
                        <p>One of our Senior Glazing Consultants has been assigned to your project and is preparing an initial technical specification and cost estimate.</p>
                        
                        <div style='background: rgba(229, 210, 165, 0.08); border: 1px solid rgba(229, 210, 165, 0.2); padding: 16px; border-radius: 6px; margin: 20px 0;'>
                            <h4 style='margin: 0 0 8px 0; color: #E5D2A5;'>What Happens Next?</h4>
                            <ul style='margin: 0; padding-left: 18px; font-size: 13px; color: #D1D5DB;'>
                                <li>Technical Review of opening dimensions & thermal Part L requirements.</li>
                                <li>Preparation of itemized CAD quotation & profile options.</li>
                                <li>Complimentary laser site survey scheduling by our senior surveyor.</li>
                            </ul>
                        </div>

                        <p>If you have architectural plans, drawings, or photos of the opening, simply reply directly to this email.</p>
                        
                        <p style='margin-bottom: 0;'>Warm regards,<br>
                        <strong style='color: #E5D2A5;'>The Technical Survey Team</strong><br>
                        Lux Glazing UK<br>
                        <a href='https://luxglazing.nexteck.uk/' style='color: #E5D2A5;'>https://luxglazing.nexteck.uk/</a> | Tel: +44 20 7946 0991</p>
                    </div>
                </div>";

                Mail::html($clientBody, function ($message) use ($lead, $fromAddress, $fromName, $clientSubject) {
                    $message->to($lead->email)
                            ->from($fromAddress, $fromName)
                            ->subject($clientSubject);
                });
            }
        } catch (Throwable $e) {
            Log::error("Failed to send client confirmation email: " . $e->getMessage());
        }
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
