<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'aws_region' => SystemSetting::get('aws_ses_region', config('services.ses.region', 'ap-south-1')),
            'aws_configuration_set' => SystemSetting::get('aws_ses_configuration_set', config('services.ses.configuration_set', 'marketing-tracking')),
            'sending_domain' => SystemSetting::get('mail_sending_domain', config('services.ses.sending_domain', 'marketing.nimadtech.in')),
            'rate_per_second' => SystemSetting::get('aws_ses_rate_per_second', config('services.ses.rate_per_second', 14)),
            'daily_quota' => SystemSetting::get('aws_ses_daily_quota', config('services.ses.daily_quota', 50000)),
            'sender_email' => env('MAIL_FROM_ADDRESS', 'marketing@marketing.nimadtech.in'),
            'sender_name' => env('MAIL_FROM_NAME', 'Lux Glazing Architectural Marketing'),
            'openai_api_key' => env('OPENAI_API_KEY') ?: SystemSetting::get('openai_api_key'),
            'openai_model' => SystemSetting::get('openai_model', config('services.openai.model', 'gpt-4o')),
        ];

        return view('marketing.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'aws_region' => 'required|string',
            'aws_configuration_set' => 'required|string',
            'sending_domain' => 'required|string',
            'rate_per_second' => 'required|integer|min:1|max:100',
            'daily_quota' => 'required|integer|min:1',
            'sender_email' => 'required|email',
            'sender_name' => 'required|string',
            'openai_api_key' => 'nullable|string',
            'openai_model' => 'required|string',
        ]);

        foreach ($validated as $key => $val) {
            SystemSetting::set($key, (string) $val, 'marketing');
        }

        return redirect()->back()->with('success', 'Marketing, AWS SES and OpenAI configuration updated successfully.');
    }

    public function testOpenAi(Request $request)
    {
        $key = env('OPENAI_API_KEY') ?: SystemSetting::get('openai_api_key');
        if (empty($key)) {
            return response()->json(['success' => false, 'message' => 'No OpenAI API key found in .env or settings.']);
        }

        try {
            $res = Http::withToken($key)
                ->timeout(10)
                ->get('https://api.openai.com/v1/models');

            if ($res->successful()) {
                return response()->json(['success' => true, 'message' => 'OpenAI API connection verified successfully! Models accessible.']);
            }

            return response()->json(['success' => false, 'message' => 'OpenAI API error: ' . $res->body()]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()]);
        }
    }
}
