<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\MarketingEmail;
use App\Models\MarketingEmailEvent;
use App\Models\MarketingCampaign;
use Illuminate\Http\Request;

class EmailLogController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketingEmail::with(['campaign', 'contact']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('recipient_email', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhere('ses_message_id', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($campaignId = $request->input('campaign_id')) {
            $query->where('campaign_id', $campaignId);
        }

        $emails = $query->latest()->paginate(30)->withQueryString();
        $campaigns = MarketingCampaign::select('id', 'name')->latest()->take(50)->get();

        return view('marketing.logs.index', compact('emails', 'campaigns'));
    }

    public function show($id)
    {
        $email = MarketingEmail::with(['campaign', 'contact', 'events' => function ($q) {
            $q->latest();
        }])->findOrFail($id);

        return view('marketing.logs.show', compact('email'));
    }
}
