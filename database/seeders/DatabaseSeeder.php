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
                'ai_qualification_summary' => 'High-net-worth homeowner in Chelsea looking for 6-meter ultra-slimline aluminium bifolds in RAL 7016 with solar-control double glazing. Immediate decision maker with drawings ready.',
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

        $lead3 = Lead::updateOrCreate(
            ['phone' => '+44 7934 567890'],
            [
                'name' => 'Marcus Vance',
                'email' => 'marcus@vancearchitecture.co.uk',
                'postcode' => 'N1 2UQ',
                'address' => '78 Upper Street, Islington, London',
                'service_type' => 'Heritage/Steel Doors',
                'project_type' => 'Residential',
                'property_type' => 'Georgian Townhouse',
                'estimated_budget' => '£6,000 - £15,000',
                'timeframe' => '1-3 months',
                'stage' => 'Qualified',
                'source' => 'Instagram',
                'ai_score' => 74,
                'ai_classification' => 'Warm',
                'ai_qualification_summary' => 'Internal Crittall-style steel-look screening and French doors for an open-plan kitchen zoning in Islington.',
                'ai_recommended_action' => 'Offer digital quote breakdown with RAL 9005 matte black heritage glazing bars.',
                'estimated_value' => 9800.00,
                'assigned_to' => $salesRep->id,
                'notes' => 'Wants acoustic glass option for sound insulation between dining and home office.',
            ]
        );

        $lead4 = Lead::updateOrCreate(
            ['phone' => '+44 7456 789012'],
            [
                'name' => 'David Thorne (Apex Commercial)',
                'email' => 'dthorne@apexbuildgroup.co.uk',
                'postcode' => 'M1 4ET',
                'address' => 'Piccadilly Plaza, Manchester City Centre',
                'service_type' => 'Commercial Glazing',
                'project_type' => 'Commercial',
                'property_type' => 'Office HQ & Retail Ground Floor',
                'estimated_budget' => '£50,000+',
                'timeframe' => 'Immediate (Within 4 weeks)',
                'stage' => 'Contacted',
                'source' => 'Organic SEO',
                'ai_score' => 95,
                'ai_classification' => 'Hot',
                'ai_qualification_summary' => 'Commercial contractor tendering for ground-floor retail curtain walling and automatic commercial entrance doors. Full tender pack available.',
                'ai_recommended_action' => 'Request architectural DWG/PDF drawings and prepare trade pricing matrix.',
                'estimated_value' => 64500.00,
                'assigned_to' => $admin->id,
                'notes' => 'Commercial compliance with UK Building Regulations Part L & Part Q required.',
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

        B2bProspect::updateOrCreate(
            ['company_name' => 'Studio Indigo Architects'],
            [
                'category' => 'Architect',
                'contact_person' => 'Elena Rostova',
                'job_title' => 'Senior Associate / Lead Specifier',
                'email' => 'specifications@studioindigo.co.uk',
                'phone' => '+44 20 7349 8080',
                'website' => 'https://www.studioindigo.co.uk',
                'city' => 'Chelsea, London',
                'postcode' => 'SW10 0AJ',
                'status' => 'Identified',
                'ai_research_notes' => 'Award-winning architecture and interior design practice specializing in luxury London townhouses, penthouses and yachts.',
                'ai_personalized_pitch' => 'Introduce Lux Glazing Cortizo minimalist sliding doors (20mm interlock) and Heritage steel-look acoustic screens with NBS specification support.',
            ]
        );

        B2bProspect::updateOrCreate(
            ['company_name' => 'Berkeley Group (St Edward)'],
            [
                'category' => 'Property Developer',
                'contact_person' => 'Mark Atherton',
                'job_title' => 'Head of Supply Chain',
                'email' => 'm.atherton@berkeleygroup.co.uk',
                'phone' => '+44 20 7720 2600',
                'website' => 'https://www.berkeleygroup.co.uk',
                'city' => 'London',
                'postcode' => 'SW8 5NN',
                'status' => 'Trade Partner',
                'ai_research_notes' => 'FTSE 100 residential developer delivering landmark regenerations across London and the South of England.',
                'ai_personalized_pitch' => 'Long-term commercial framework for residential aluminium window suites and PAS 24 certified composite & entrance systems.',
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
            [
                'quotation_id' => $quote->id,
                'product_name' => 'Cortizo / Schüco 5-Panel Aluminium Bifold Door Suite',
            ],
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

        QuotationItem::updateOrCreate(
            [
                'quotation_id' => $quote->id,
                'product_name' => 'Full FENSA Site Survey, Structural Preparation & Expert Installation',
            ],
            [
                'description' => 'Laser precision site survey, removal of existing doors, disposal, laser level installation with weather-sealed flush threshold.',
                'width_mm' => 0,
                'height_mm' => 0,
                'color_finish' => 'Matching RAL 7016',
                'glass_specification' => 'Installation & Certification',
                'quantity' => 1,
                'unit_price' => 2566.67,
                'line_total' => 2566.67,
            ]
        );

        // 5. UK SEO Keywords
        $keywords = [
            ['keyword' => 'aluminium bifold doors london', 'target_page' => '/bifold-doors', 'intent' => 'Transactional', 'priority' => 'High', 'current_position' => 4, 'target_position' => 1, 'search_volume_est' => 2400],
            ['keyword' => 'slimline sliding doors surrey', 'target_page' => '/sliding-doors', 'intent' => 'Commercial', 'priority' => 'High', 'current_position' => 6, 'target_position' => 2, 'search_volume_est' => 1600],
            ['keyword' => 'architectural glazing contractors uk', 'target_page' => '/commercial-glazing', 'intent' => 'Commercial', 'priority' => 'High', 'current_position' => 8, 'target_position' => 3, 'search_volume_est' => 1100],
            ['keyword' => 'heritage steel look doors london', 'target_page' => '/heritage-doors', 'intent' => 'Transactional', 'priority' => 'High', 'current_position' => 3, 'target_position' => 1, 'search_volume_est' => 1900],
            ['keyword' => 'upvc flush sash windows price', 'target_page' => '/upvc-windows', 'intent' => 'Commercial', 'priority' => 'Medium', 'current_position' => 11, 'target_position' => 4, 'search_volume_est' => 3200],
            ['keyword' => 'composite front door installers near me', 'target_page' => '/composite-doors', 'intent' => 'Transactional', 'priority' => 'Medium', 'current_position' => 7, 'target_position' => 2, 'search_volume_est' => 4500],
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

        MarketingMetric::updateOrCreate(
            ['channel' => 'Meta (Instagram & Facebook Ads)'],
            [
                'period_label' => 'Current Month',
                'spend_gbp' => 950.00,
                'impressions' => 58000,
                'clicks' => 1240,
                'leads_generated' => 28,
                'cost_per_lead_gbp' => 33.93,
                'conversions_won' => 4,
            ]
        );

        MarketingMetric::updateOrCreate(
            ['channel' => 'Organic SEO & Local Google Maps'],
            [
                'period_label' => 'Current Month',
                'spend_gbp' => 800.00,
                'impressions' => 94000,
                'clicks' => 3120,
                'leads_generated' => 56,
                'cost_per_lead_gbp' => 14.28,
                'conversions_won' => 11,
            ]
        );

        // 7. System Settings
        SystemSetting::set('company_name', 'Lux Glazing UK', 'general');
        SystemSetting::set('website_url', 'https://luxglazing.co.uk/', 'general');
        SystemSetting::set('support_email', 'info@luxglazing.co.uk', 'general');
        SystemSetting::set('phone_number', '+44 20 7946 0991', 'general');
        SystemSetting::set('openai_model', 'gpt-4o', 'ai');
        SystemSetting::set('ai_lead_qualification_enabled', '1', 'ai');
        SystemSetting::set('currency_symbol', '£', 'general');
        SystemSetting::set('vat_rate', '20', 'financial');
    }
}
