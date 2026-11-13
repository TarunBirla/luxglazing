<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\MarketingSuppression;
use App\Models\MarketingContact;
use Illuminate\Http\Request;

class SuppressionController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketingSuppression::query();

        if ($search = $request->input('search')) {
            $query->where('email', 'like', "%{$search}%");
        }

        if ($reason = $request->input('reason')) {
            $query->where('reason', $reason);
        }

        $suppressions = $query->orderBy('created_at', 'desc')->paginate(30)->withQueryString();

        $stats = [
            'total' => MarketingSuppression::count(),
            'unsubscribes' => MarketingSuppression::where('reason', 'unsubscribe')->count(),
            'hard_bounces' => MarketingSuppression::where('reason', 'hard_bounce')->count(),
            'complaints' => MarketingSuppression::where('reason', 'complaint')->count(),
        ];

        return view('marketing.suppressions.index', compact('suppressions', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'reason' => 'required|in:unsubscribe,hard_bounce,complaint,manual',
            'notes' => 'nullable|string|max:255',
        ]);

        MarketingSuppression::suppress(
            $validated['email'],
            $validated['reason'],
            'Admin Manual Entry (' . auth()->user()->name . ')',
            ['notes' => $validated['notes'] ?? '']
        );

        return redirect()->back()->with('success', "Email {$validated['email']} added to central suppression list.");
    }

    public function destroy($id)
    {
        $suppression = MarketingSuppression::findOrFail($id);
        $email = $suppression->email;
        $suppression->delete();

        // Optionally allow re-subscribing contact if verified
        MarketingContact::where('email', $email)->update([
            'subscription_status' => 'subscribed',
        ]);

        return redirect()->back()->with('success', "Email {$email} removed from suppression list.");
    }
}
