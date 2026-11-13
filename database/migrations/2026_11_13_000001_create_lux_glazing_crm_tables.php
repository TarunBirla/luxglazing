<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add role and phone to users if not present
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'role')) {
                    $table->string('role')->default('sales_agent')->after('email');
                }
                if (!Schema::hasColumn('users', 'phone')) {
                    $table->string('phone')->nullable()->after('role');
                }
            });
        }

        // Leads Table
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('postcode')->nullable();
            $table->text('address')->nullable();
            $table->string('service_type'); // Aluminium Windows, uPVC Windows, Bifold Doors, Sliding Doors, Composite Doors, Heritage/Steel Doors, Commercial Glazing
            $table->string('project_type')->default('Residential'); // Residential, Commercial, Trade
            $table->string('property_type')->nullable(); // Detached, Semi-detached, Commercial, New Build
            $table->string('estimated_budget')->nullable(); // £3,000 - £6,000, £6,000 - £15,000, etc.
            $table->string('timeframe')->nullable(); // Immediate, 1-3 months, Planning stage
            $table->string('stage')->default('New Lead'); // New Lead, Contacted, Qualified, Survey Booked, Quote Sent, Won, Lost
            $table->string('source')->default('Website Chatbot'); // Website Chatbot, Google Ads, Organic SEO, Instagram, Facebook, B2B Referral, Phone
            $table->integer('ai_score')->default(50); // 0-100
            $table->string('ai_classification')->default('Warm'); // Hot, Warm, Cold
            $table->text('ai_qualification_summary')->nullable();
            $table->text('ai_recommended_action')->nullable();
            $table->decimal('estimated_value', 12, 2)->default(0.00);
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // B2B Prospects Table
        Schema::create('b2b_prospects', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('category'); // Architect, Builder, General Contractor, Property Developer, Commercial Fit-Out
            $table->string('contact_person')->nullable();
            $table->string('job_title')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('city')->nullable(); // London, Surrey, Manchester, Birmingham, etc.
            $table->string('postcode')->nullable();
            $table->string('status')->default('Identified'); // Identified, Researched, Contacted, Meeting Scheduled, Trade Partner, Inactive
            $table->text('ai_research_notes')->nullable();
            $table->text('ai_personalized_pitch')->nullable();
            $table->timestamps();
        });

        // Quotations Table
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number')->unique(); // LUX-2026-001
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('site_address')->nullable();
            $table->string('project_title')->default('Architectural Glazing Supply & Installation');
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('vat_rate', 5, 2)->default(20.00); // 20% UK Standard VAT
            $table->decimal('vat_amount', 12, 2)->default(0.00);
            $table->decimal('discount', 12, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->string('status')->default('Draft'); // Draft, Sent, Accepted, Declined, Revised
            $table->date('valid_until')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->timestamps();
        });

        // Quotation Items Table
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations')->cascadeOnDelete();
            $table->string('product_name'); // Cortizo Cor Vision Plus, Schüco Bifold, Aluk Windows, etc.
            $table->text('description')->nullable();
            $table->integer('width_mm')->nullable();
            $table->integer('height_mm')->nullable();
            $table->string('color_finish')->default('Anthracite Grey (RAL 7016)');
            $table->string('glass_specification')->default('28mm Toughened Low-E Argon-Filled Double Glazing');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0.00);
            $table->decimal('line_total', 12, 2)->default(0.00);
            $table->timestamps();
        });

        // Email Campaigns Table
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subject');
            $table->string('target_audience'); // Architects & Specifiers, Residential Homeowners, Commercial Fit-Out
            $table->longText('template_body');
            $table->string('status')->default('Draft'); // Draft, Scheduled, Sent
            $table->integer('recipients_count')->default(0);
            $table->integer('opened_count')->default(0);
            $table->integer('replied_count')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        // Tasks & Reminders Table
        Schema::create('tasks_and_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('priority')->default('Medium'); // High, Medium, Low
            $table->string('status')->default('Pending'); // Pending, Completed, Overdue
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        // SEO Keywords Table
        Schema::create('seo_keywords', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->string('target_page');
            $table->string('intent'); // Commercial, Transactional, Informational, Navigational
            $table->string('priority')->default('High'); // High, Medium, Low
            $table->integer('current_position')->nullable();
            $table->integer('target_position')->default(1);
            $table->integer('search_volume_est')->nullable();
            $table->timestamps();
        });

        // Marketing Metrics Table
        Schema::create('marketing_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('channel'); // Google Search Ads, Meta (IG/FB), Organic SEO, B2B Cold Outreach
            $table->string('period_label'); // e.g. "Current Month"
            $table->decimal('spend_gbp', 12, 2)->default(0.00);
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('leads_generated')->default(0);
            $table->decimal('cost_per_lead_gbp', 10, 2)->default(0.00);
            $table->decimal('conversions_won', 10, 2)->default(0.00);
            $table->timestamps();
        });

        // System Settings Table
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key')->unique();
            $table->text('setting_value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('marketing_metrics');
        Schema::dropIfExists('seo_keywords');
        Schema::dropIfExists('tasks_and_reminders');
        Schema::dropIfExists('email_campaigns');
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('quotations');
        Schema::dropIfExists('b2b_prospects');
        Schema::dropIfExists('leads');
    }
};
