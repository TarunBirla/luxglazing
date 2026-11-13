<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\SystemSetting;
use Throwable;

class OpenAiGlazingService
{
    public function getApiKey(): ?string
    {
        return config('services.openai.key') ?: env('OPENAI_API_KEY') ?: SystemSetting::get('openai_api_key');
    }

    public function getModel(): string
    {
        return SystemSetting::get('openai_model', 'gpt-4o');
    }

    /**
     * Score & Qualify Glazing Lead
     */
    public function qualifyAndScoreLead(array $data): array
    {
        $apiKey = $this->getApiKey();
        $model = $this->getModel();

        if (!empty($apiKey)) {
            try {
                $prompt = "You are an elite UK Architectural Glazing Sales Director and Estimator.
Analyze this glazing lead for Lux Glazing UK:
Product: " . ($data['service_type'] ?? 'Glazing') . "
Project Type: " . ($data['project_type'] ?? 'Residential') . "
Budget: " . ($data['estimated_budget'] ?? 'Undisclosed') . "
Timeframe: " . ($data['timeframe'] ?? 'Not specified') . "
Property: " . ($data['property_type'] ?? 'Residential') . "
Postcode: " . ($data['postcode'] ?? 'UK') . "
Notes: " . ($data['notes'] ?? 'None') . "

Respond strictly in JSON format with keys:
- score: integer 0-100
- classification: 'Hot', 'Warm', or 'Cold'
- qualification_summary: 2-3 sentence executive summary of the project viability
- recommended_action: 1-2 actionable next steps for the sales team
- estimated_value_gbp: numeric estimated total order value in GBP";

                $response = Http::withToken($apiKey)
                    ->timeout(15)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => $model,
                        'messages' => [
                            ['role' => 'system', 'content' => 'You evaluate UK architectural glazing leads.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'response_format' => ['type' => 'json_object'],
                        'temperature' => 0.2,
                    ]);

                if ($response->successful()) {
                    $json = $response->json('choices.0.message.content');
                    $parsed = json_decode($json, true);
                    if ($parsed && isset($parsed['score'])) {
                        return [
                            'score' => (int) $parsed['score'],
                            'classification' => $parsed['classification'] ?? 'Warm',
                            'summary' => $parsed['qualification_summary'] ?? '',
                            'recommended_action' => $parsed['recommended_action'] ?? '',
                            'estimated_value' => (float) ($parsed['estimated_value_gbp'] ?? 10000),
                        ];
                    }
                }
            } catch (Throwable $e) {
                // Fall back to expert heuristics
            }
        }

        // Heuristic fallback for UK Architectural Glazing
        $score = 50;
        $budget = $data['estimated_budget'] ?? '';
        $timeframe = $data['timeframe'] ?? '';
        $service = $data['service_type'] ?? 'Bespoke Glazing';

        if (str_contains($budget, '50,000') || str_contains($budget, '25,000')) {
            $score += 25;
            $val = 32000;
        } elseif (str_contains($budget, '15,000')) {
            $score += 18;
            $val = 18000;
        } elseif (str_contains($budget, '6,000')) {
            $score += 10;
            $val = 8500;
        } else {
            $val = 4500;
        }

        if (stripos($timeframe, 'Immediate') !== false || stripos($timeframe, '4 weeks') !== false) {
            $score += 20;
        } elseif (stripos($timeframe, '1-3 months') !== false) {
            $score += 10;
        }

        if (($data['project_type'] ?? '') === 'Commercial') {
            $score += 10;
            $val = max($val, 45000);
        }

        $score = min(98, max(25, $score));
        $class = $score >= 75 ? 'Hot' : ($score >= 45 ? 'Warm' : 'Cold');

        return [
            'score' => $score,
            'classification' => $class,
            'summary' => "High intent lead for {$service} with an indicated budget of {$budget} and project timeframe: {$timeframe}.",
            'recommended_action' => $class === 'Hot' ? 'Urgent priority: Call within 30 minutes to book professional laser site survey.' : 'Send digital product brochure and request architectural drawings for preliminary estimate.',
            'estimated_value' => (float) $val,
        ];
    }

    /**
     * Generate B2B Outreach Pitch
     */
    public function generateB2bPitch(array $prospect): string
    {
        $company = $prospect['company_name'] ?? 'Your Firm';
        $category = $prospect['category'] ?? 'Architecture & Construction';
        $contact = $prospect['contact_person'] ?? 'Team';
        $city = $prospect['city'] ?? 'UK';

        return "Subject: Architectural Glazing & Slimline Aluminium Collaboration – Lux Glazing & {$company}\n\n" .
               "Dear {$contact},\n\n" .
               "I hope this finds you well.\n\n" .
               "I am reaching out from Lux Glazing UK. Having reviewed {$company}'s exceptional {$category} portfolio across {$city}, we would welcome the opportunity to support your upcoming high-end residential and commercial schemes.\n\n" .
               "We specialize in premium, precision-engineered glazing solutions tailored specifically for architects, high-end builders and developers:\n" .
               "• Ultra-Slimline Aluminium Sliding Doors (Cortizo 20mm sightlines, panoramic floor-to-ceiling glass)\n" .
               "• High-Performance Bi-Fold Door Suites (Schüco & Smart Systems)\n" .
               "• Heritage Crittall-Style Internal & External Steel-Look Screens (Part L & Part Q compliant)\n" .
               "• Frameless Structural Glass Roof Lanterns & Walk-on Rooflights\n" .
               "• Turnkey FENSA Certified Survey, Supply & In-House Installation Teams\n\n" .
               "We provide dedicated trade pricing discounts, full technical DWG/BIM CAD assistance, and rapid 24-48 hour quotation turnaround.\n\n" .
               "Could we arrange a brief 10-minute introductory call or send across our latest 2026 Architectural Specifiers Guide?\n\n" .
               "Kind regards,\n\n" .
               "Commercial Partnerships Team\n" .
               "Lux Glazing UK\n" .
               "https://luxglazing.nexteck.uk/ | +44 20 7946 0991";
    }

    /**
     * Interactive Website AI Chatbot Qualification
     */
    public function chatbotReply(array $history, string $userMessage): array
    {
        $messageLower = strtolower($userMessage);

        // Detect lead details
        $detectedService = null;
        if (str_contains($messageLower, 'bifold') || str_contains($messageLower, 'bi-fold')) {
            $detectedService = 'Bifold Doors';
        } elseif (str_contains($messageLower, 'sliding') || str_contains($messageLower, 'patio')) {
            $detectedService = 'Sliding Doors';
        } elseif (str_contains($messageLower, 'crittall') || str_contains($messageLower, 'heritage') || str_contains($messageLower, 'steel')) {
            $detectedService = 'Heritage/Steel Doors';
        } elseif (str_contains($messageLower, 'upvc')) {
            $detectedService = 'uPVC Windows';
        } elseif (str_contains($messageLower, 'commercial') || str_contains($messageLower, 'curtain wall') || str_contains($messageLower, 'shopfront')) {
            $detectedService = 'Commercial Glazing';
        } elseif (str_contains($messageLower, 'window') || str_contains($messageLower, 'aluminium')) {
            $detectedService = 'Aluminium Windows';
        }

        // Phone detection regex
        preg_match('/(?:(?:\+44\s?\(0\)\s?|\+44\s?|0)[1-9]\d{2,4}\s?\d{3,4}\s?\d{3,4})/', $userMessage, $phoneMatches);
        $phone = $phoneMatches[0] ?? null;

        // Postcode detection
        preg_match('/[A-Z]{1,2}[0-9][A-Z0-9]? ?[0-9][A-Z]{2}/i', $userMessage, $postcodeMatches);
        $postcode = $postcodeMatches[0] ?? null;

        $reply = "Thank you for reaching out to Lux Glazing UK! We specialize in luxury architectural aluminium windows, bifold doors, ultra-slim sliding systems, and heritage steel-look glass.\n\nCould you please share the approximate dimensions of your opening and your UK postcode so we can provide an accurate quotation and check surveyor availability in your area?";

        if ($detectedService) {
            $reply = "Excellent choice. Our {$detectedService} range features market-leading thermal efficiency (Part L compliant), high security multi-point locks, and bespoke RAL finishes (including Anthracite Grey RAL 7016 and Matte Black).\n\nTo tailor a direct proposal, approximately how wide is your opening, and what is your UK postcode or city?";
        }

        if ($phone || $postcode) {
            $reply = "Thank you! Our technical surveying team has received your inquiry. One of our Senior Glazing Consultants will contact you shortly with an initial estimate and CAD specification breakdown. Would you like us to arrange a free laser site survey?";
        }

        return [
            'reply' => $reply,
            'detected_service' => $detectedService,
            'phone' => $phone,
            'postcode' => $postcode,
        ];
    }
}
