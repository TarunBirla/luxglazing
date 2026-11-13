<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\MarketingContact;
use App\Models\MarketingSuppression;
use App\Models\MarketingConsentLog;
use Illuminate\Http\Request;

class UnsubscribeController extends Controller
{
    /**
     * Show Web Unsubscribe Confirmation Page
     */
    public function show(string $token)
    {
        $contact = MarketingContact::where('unsubscribe_token', $token)->first();

        if (!$contact) {
            return view('marketing.unsubscribe.invalid');
        }

        return view('marketing.unsubscribe.confirm', compact('contact'));
    }

    /**
     * Process Web Unsubscribe Form
     */
    public function process(Request $request, string $token)
    {
        $contact = MarketingContact::where('unsubscribe_token', $token)->first();

        if (!$contact) {
            return view('marketing.unsubscribe.invalid');
        }

        // Add to permanent suppression list
        MarketingSuppression::suppress(
            $contact->email,
            'unsubscribe',
            'Web One-Click Link',
            [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'reason' => $request->input('reason', 'User requested unsubscribe'),
            ]
        );

        // Update contact record
        $contact->update([
            'subscription_status' => 'unsubscribed',
            'consent_status' => false,
            'unsubscribed_at' => now(),
        ]);

        // Log consent change
        MarketingConsentLog::create([
            'contact_id' => $contact->id,
            'email' => $contact->email,
            'action' => 'opt_out',
            'consent_source' => 'Web Unsubscribe Link',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'notes' => 'User confirmed unsubscription',
        ]);

        return view('marketing.unsubscribe.success', compact('contact'));
    }

    /**
     * RFC 8058 One-Click Unsubscribe POST Endpoint (Used by Gmail, Apple Mail, Yahoo)
     */
    public function oneClick(Request $request, string $token)
    {
        $contact = MarketingContact::where('unsubscribe_token', $token)->first();

        if (!$contact) {
            return response()->json(['error' => 'Invalid token'], 404);
        }

        MarketingSuppression::suppress(
            $contact->email,
            'unsubscribe',
            'RFC 8058 One-Click Header',
            [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]
        );

        $contact->update([
            'subscription_status' => 'unsubscribed',
            'consent_status' => false,
            'unsubscribed_at' => now(),
        ]);

        MarketingConsentLog::create([
            'contact_id' => $contact->id,
            'email' => $contact->email,
            'action' => 'opt_out',
            'consent_source' => 'RFC 8058 One-Click Post',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'notes' => 'Automated RFC 8058 mail client action',
        ]);

        return response()->json(['success' => true, 'message' => 'Successfully unsubscribed.']);
    }
}
