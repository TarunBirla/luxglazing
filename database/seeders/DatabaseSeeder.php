<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Lead;
use App\Models\B2bProspect;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\EmailCampaign;
use App\Models\TaskReminder;
use App\Models\SeoKeyword;
use App\Models\MarketingMetric;
use App\Models\SystemSetting;
use App\Models\MarketingContact;
use App\Models\MarketingTemplate;
use App\Models\MarketingCampaign;
use App\Models\MarketingConsentLog;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Super Admin & Sales Team
        $admin = User::updateOrCreate(
            ['email' => 'admin@luxglazing.co.uk'],
            [
                'name' => 'Lux Glazing Admin',
                'password' => Hash::make('LuxGlazing@2026'),
                'role' => 'super_admin',
                'phone' => '+44 20 7946 0991',
            ]
        );

        $salesRep = User::updateOrCreate(
            ['email' => 'oliver@luxglazing.co.uk'],
            [
                'name' => 'Oliver Davies (Senior Glazing Consultant)',
                'password' => Hash::make('LuxGlazing@2026'),
                'role' => 'sales_agent',
                'phone' => '+44 7700 900142',
            ]
        );

        // 2. Realistic UK Glazing Leads
        $lead1 = Lead::updateOrCreate(
            ['phone' => '+44 7712 345678'],
            [
                'name' => 'Charles Kensington',
                'email' => 'charles.k@residential-chelsea.co.uk',
                'postcode' => 'SW3 4RY',
                'address' => '42 King\'s Road, Chelsea, London',
                'service_type' => 'Bifold Doors',
                'project_type' => 'Residential',
                'property_type' => 'Detached Victorian Villa',
                'estimated_budget' => '£15,000 - £25,000',
                'timeframe' => 'Immediate (Within 4 weeks)',
                'stage' => 'Survey Booked',
                'source' => 'Website Chatbot',
                'ai_score' => 92,
                'ai_classification' => 'Hot',
                'ai_qualification_summary' => 'High-net-worth homeowner in Chelsea looking for 6-meter ultra-slimline aluminium bifolds in RAL 7016 with solar-control double glazing.',
                'ai_recommended_action' => 'Priority site survey scheduled. Recommend Cortizo or Schüco profiles with integrated solar control glazing.',
                'estimated_value' => 18500.00,
                'assigned_to' => $salesRep->id,
                'notes' => 'Customer requested matching anthracite grey hardware and level threshold for indoor-outdoor patio integration.',
            ]
        );

        $lead2 = Lead::updateOrCreate(
            ['phone' => '+44 7823 456789'],
            [
                'name' => 'Victoria Sterling',
                'email' => 'v.sterling@surreyestates.co.uk',
                'postcode' => 'TW10 6RF',
                'address' => '18 Richmond Hill, Richmond, Surrey',
                'service_type' => 'Sliding Doors',
                'project_type' => 'Residential',
                'property_type' => 'Modern Architect-Designed Home',
                'estimated_budget' => '£25,000 - £50,000',
                'timeframe' => '1-3 months',
                'stage' => 'Quote Sent',
                'source' => 'Google Ads',
                'ai_score' => 88,
                'ai_classification' => 'Hot',
                'ai_qualification_summary' => 'High-value contemporary renovation in Richmond Hill. Specifying floor-to-ceiling ultra-minimalist sliding glass doors with 20mm interlock sightlines.',
                'ai_recommended_action' => 'Send formal 3D render breakdown and invite to showroom/site gallery. Follow up within 48 hours.',
                'estimated_value' => 32000.00,
                'assigned_to' => $admin->id,
                'notes' => 'Structural opening being prepared by main contractor in July.',
            ]
        );

        // 3. Realistic UK B2B Trade Prospects
        B2bProspect::updateOrCreate(
            ['company_name' => 'Octagon Bespoke Homes'],
            [
                'category' => 'Builder',
                'contact_person' => 'James Cunningham',
                'job_title' => 'Commercial Procurement Director',
                'email' => 'procurement@octagon-bespoke.co.uk',
                'phone' => '+44 1932 280000',
                'website' => 'https://www.octagon.co.uk',
                'city' => 'Weybridge, Surrey',
                'postcode' => 'KT13 0UG',
                'status' => 'Researched',
                'ai_research_notes' => 'Premier luxury homebuilder in the Home Counties building £3m-£10m mansions. Frequently install oversized slimline sliding doors and structural roof lanterns.',
                'ai_personalized_pitch' => 'Highlight Lux Glazing trade discounts, dedicated site surveys, and turnkey installation guarantees for prime Surrey & Berkshire developments.',
            ]
        );

        // 4. Sample Quotation for Lead 1
        $quote = Quotation::updateOrCreate(
            ['quote_number' => 'LUX-2026-001'],
            [
                'lead_id' => $lead1->id,
                'customer_name' => 'Charles Kensington',
                'customer_email' => 'charles.k@residential-chelsea.co.uk',
                'customer_phone' => '+44 7712 345678',
                'site_address' => '42 King\'s Road, Chelsea, London SW3 4RY',
                'project_title' => 'Premium Slimline Aluminium Bifold Doors & Integrated Flush Threshold',
                'subtotal' => 15416.67,
                'vat_rate' => 20.00,
                'vat_amount' => 3083.33,
                'discount' => 0.00,
                'total_amount' => 18500.00,
                'status' => 'Sent',
                'valid_until' => now()->addDays(30),
                'terms_conditions' => "1. 10-Year Comprehensive Manufacturer & Installation Guarantee.\n2. Compliant with UK Building Regulations Part L (Thermal) & Part Q (Security).\n3. FENSA Certification and Insurance Backed Guarantee (IBG) included upon completion.",
            ]
        );

        QuotationItem::updateOrCreate(
            ['quotation_id' => $quote->id, 'product_name' => 'Cortizo / Schüco 5-Panel Aluminium Bifold Door Suite'],
            [
                'description' => 'Ultra-slim sightlines (120mm stiles), low thermal U-value (1.3 W/m²K), multi-point high security locking system.',
                'width_mm' => 5200,
                'height_mm' => 2400,
                'color_finish' => 'Anthracite Grey (RAL 7016 Matt)',
                'glass_specification' => '28mm Toughened Solar-Control Low-E Argon Double Glazing with warm-edge spacer bars.',
                'quantity' => 1,
                'unit_price' => 12850.00,
                'line_total' => 12850.00,
            ]
        );

        // 5. UK SEO Keywords
        $keywords = [
            ['keyword' => 'aluminium bifold doors london', 'target_page' => '/bifold-doors', 'intent' => 'Transactional', 'priority' => 'High', 'current_position' => 4, 'target_position' => 1, 'search_volume_est' => 2400],
            ['keyword' => 'slimline sliding doors surrey', 'target_page' => '/sliding-doors', 'intent' => 'Commercial', 'priority' => 'High', 'current_position' => 6, 'target_position' => 2, 'search_volume_est' => 1600],
        ];
        foreach ($keywords as $kw) {
            SeoKeyword::updateOrCreate(['keyword' => $kw['keyword']], $kw);
        }

        // 6. Marketing Metrics
        MarketingMetric::updateOrCreate(
            ['channel' => 'Google Search Ads (PPC)'],
            [
                'period_label' => 'Current Month',
                'spend_gbp' => 1850.00,
                'impressions' => 24500,
                'clicks' => 980,
                'leads_generated' => 42,
                'cost_per_lead_gbp' => 44.05,
                'conversions_won' => 8,
            ]
        );

        // 7. System Settings
        SystemSetting::set('company_name', 'Lux Glazing UK', 'general');
        SystemSetting::set('website_url', 'https://luxglazing.nexteck.uk/', 'general');
        SystemSetting::set('support_email', 'info@luxglazing.co.uk', 'general');
        SystemSetting::set('phone_number', '+44 20 7946 0991', 'general');
        SystemSetting::set('openai_model', 'gpt-4o', 'ai');
        SystemSetting::set('aws_ses_region', 'ap-south-1', 'marketing');
        SystemSetting::set('aws_ses_configuration_set', 'marketing-tracking', 'marketing');
        SystemSetting::set('mail_sending_domain', 'marketing.nimadtech.in', 'marketing');
        SystemSetting::set('currency_symbol', '£', 'general');
        SystemSetting::set('vat_rate', '20', 'financial');

        // 8. AI Marketing Email Manager - Sample Verified Contacts
        $contact1 = MarketingContact::updateOrCreate(
            ['email' => 'oliver.henderson@henderson-architects.co.uk'],
            [
                'name' => 'Oliver Henderson',
                'company' => 'Henderson Architects RIBA',
                'website' => 'https://henderson-architects.co.uk',
                'phone' => '+44 20 7946 0111',
                'business_type' => 'Architect',
                'verification_status' => 'verified',
                'consent_status' => true,
                'consent_source' => 'RIBA Trade Directory Opt-in',
                'consent_date' => now()->subDays(10),
                'subscription_status' => 'subscribed',
            ]
        );

        $contact2 = MarketingContact::updateOrCreate(
            ['email' => 'sarah.jenkins@berkeleyhomes.co.uk'],
            [
                'name' => 'Sarah Jenkins',
                'company' => 'Berkeley Homes London',
                'website' => 'https://berkeleygroup.co.uk',
                'phone' => '+44 20 7946 0222',
                'business_type' => 'Property Developer',
                'verification_status' => 'verified',
                'consent_status' => true,
                'consent_source' => 'B2B Trade Application',
                'consent_date' => now()->subDays(8),
                'subscription_status' => 'subscribed',
            ]
        );

        $contact3 = MarketingContact::updateOrCreate(
            ['email' => 'marcus.vance@vance-construction.co.uk'],
            [
                'name' => 'Marcus Vance',
                'company' => 'Vance Construction & Developments',
                'website' => 'https://vanceconstruction.co.uk',
                'phone' => '+44 1483 500600',
                'business_type' => 'Builder',
                'verification_status' => 'verified',
                'consent_status' => true,
                'consent_source' => 'Direct Consultation',
                'consent_date' => now()->subDays(5),
                'subscription_status' => 'subscribed',
            ]
        );

        // 9. Reusable Email Templates
        $tpl1 = MarketingTemplate::updateOrCreate(
            ['name' => 'Cortizo Ultra-Slim Sliding Doors Showcase'],
            [
                'subject' => 'Architectural Specification: Cortizo Cor Vision Plus – {{company}}',
                'category' => 'Architectural Showcase',
                'body_html' => '<p>Dear {{name}},</p>
<p>I hope you are having a productive week at {{company}}.</p>
<p>As specialists in high-specification architectural glazing across the UK, we would welcome the opportunity to introduce <strong>Cortizo Cor Vision Plus</strong> for your upcoming residential schemes:</p>
<ul>
    <li><strong>20mm Minimal Sightlines:</strong> Floor-to-ceiling glass integration with flush floor transitions.</li>
    <li><strong>Thermal Performance:</strong> Low U-value of 1.1 W/m²K, exceeding UK Part L standards.</li>
    <li><strong>Turnkey FENSA Installation:</strong> In-house certified surveyors and structural glass engineers.</li>
</ul>
<p>Would you like us to share our 2026 Architectural Specifiers Guide and DWG CAD blocks?</p>
<p>Kind regards,<br><strong>Commercial Specifications Team</strong><br>Lux Glazing UK</p>',
                'variables' => ['name', 'company', 'email', 'website', 'unsubscribe_url'],
                'is_active' => true,
            ]
        );

        $tpl2 = MarketingTemplate::updateOrCreate(
            ['name' => 'Heritage Steel-Look Acoustic Screens'],
            [
                'subject' => 'Heritage Steel-Look Glazing for London Schemes – {{company}}',
                'category' => 'Heritage Collection',
                'body_html' => '<p>Dear {{name}},</p>
<p>We are delighted to present Lux Glazing\'s <strong>Heritage Steel-Look Collection</strong>, specifically engineered for luxury home renovations and conservation schemes.</p>
<p>Features slender aluminium profiles mimicking historic Crittall aesthetic with modern thermal break and acoustic insulation.</p>
<p>Let us know if you have an active scheme where we can assist with preliminary estimates.</p>
<p>Warm regards,<br>Lux Glazing UK</p>',
                'variables' => ['name', 'company', 'email', 'website', 'unsubscribe_url'],
                'is_active' => true,
            ]
        );

        // 10. Sample Campaign
        MarketingCampaign::updateOrCreate(
            ['name' => 'Q4 London & Surrey Architects Outreach'],
            [
                'description' => 'Targeted campaign presenting Cortizo minimalist sliding doors to leading RIBA architectural practices.',
                'purpose' => 'Promote Cortizo Cor Vision Plus and Schüco bifold suites with trade discounts.',
                'subject' => 'Bespoke Architectural Glazing Collaboration – Lux Glazing UK',
                'content' => 'Presenting Cortizo Cor Vision Plus (20mm sightlines) and turnkey FENSA installation for luxury residential extensions.',
                'template_id' => $tpl1->id,
                'sending_mode' => 'both',
                'status' => 'completed',
                'started_at' => now()->subDays(3),
                'completed_at' => now()->subDays(2),
                'total_contacts' => 3,
                'emails_generated' => 3,
                'emails_queued' => 3,
                'emails_sent' => 3,
                'emails_delivered' => 3,
                'emails_opened' => 2,
                'emails_clicked' => 1,
                'emails_bounced' => 0,
                'emails_complained' => 0,
                'openai_tokens_used' => 650,
                'openai_cost_usd' => 0.0032,
                'sender_email' => 'marketing@marketing.nimadtech.in',
                'sender_name' => 'Lux Glazing Architectural Team',
            ]
        );
    }
}
