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
        // 1. Marketing Contacts Table
        Schema::create('marketing_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('company')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->string('business_type')->nullable(); // Architect, Builder, Developer, Contractor, Retailer, etc.
            $table->string('verification_status')->default('unverified'); // unverified, verified, invalid
            $table->boolean('consent_status')->default(true);
            $table->string('consent_source')->default('Manual Import'); // Web Form, Direct Enquiry, Client Relationship, Trade Partner, Event
            $table->timestamp('consent_date')->nullable();
            $table->string('consent_ip')->nullable();
            $table->string('subscription_status')->default('subscribed'); // subscribed, unsubscribed, suppressed, bounced, complained
            $table->string('unsubscribe_token', 80)->unique();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['subscription_status', 'consent_status']);
            $table->index('business_type');
        });

        // 2. Marketing Templates Table
        Schema::create('marketing_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->longText('body_html');
            $table->longText('body_text')->nullable();
            $table->string('category')->default('General');
            $table->json('variables')->nullable(); // ["name", "company", "website", "unsubscribe_url"]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Marketing Campaigns Table
        Schema::create('marketing_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('purpose')->nullable();
            $table->string('subject');
            $table->longText('content')->nullable(); // template or guidance content
            $table->foreignId('template_id')->nullable()->constrained('marketing_templates')->nullOnDelete();
            $table->string('sending_mode')->default('ai_generated'); // ai_generated, template, both
            $table->string('status')->default('draft'); // draft, scheduled, processing, sending, paused, completed, cancelled
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('target_criteria')->nullable(); // filters: business_type, only verified, etc.
            $table->string('sender_email')->nullable();
            $table->string('sender_name')->nullable();
            
            // Statistics counters
            $table->unsignedInteger('total_contacts')->default(0);
            $table->unsignedInteger('emails_generated')->default(0);
            $table->unsignedInteger('emails_queued')->default(0);
            $table->unsignedInteger('emails_sent')->default(0);
            $table->unsignedInteger('emails_delivered')->default(0);
            $table->unsignedInteger('emails_opened')->default(0);
            $table->unsignedInteger('emails_clicked')->default(0);
            $table->unsignedInteger('emails_bounced')->default(0);
            $table->unsignedInteger('emails_complained')->default(0);
            $table->unsignedInteger('openai_tokens_used')->default(0);
            $table->decimal('openai_cost_usd', 10, 4)->default(0.0000);

            $table->timestamps();
            $table->index('status');
        });

        // 4. Marketing Emails Table
        Schema::create('marketing_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('marketing_campaigns')->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained('marketing_contacts')->cascadeOnDelete();
            $table->string('recipient_email');
            $table->string('recipient_name')->nullable();
            $table->string('subject');
            $table->longText('body_html');
            $table->longText('body_text')->nullable();
            $table->string('ses_message_id')->nullable()->index();
            $table->string('status')->default('pending'); 
            // pending, generated, queued, sent, delivered, opened, clicked, bounced, failed, complained, unsubscribed, suppressed
            $table->string('generation_mode')->default('ai'); // ai, template, manual
            $table->text('ai_prompt_used')->nullable();
            $table->unsignedInteger('openai_tokens')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('bounced_at')->nullable();
            $table->timestamp('complained_at')->nullable();
            $table->string('bounce_type')->nullable(); // Permanent, Transient, Undetermined
            $table->text('error_message')->nullable();
            $table->unsignedSmallInteger('retry_count')->default(0);
            $table->timestamps();

            $table->index(['campaign_id', 'status']);
            $table->index(['contact_id', 'status']);
            $table->index('recipient_email');
        });

        // 5. Marketing Email Events Table (Audit logs & Webhook delivery)
        Schema::create('marketing_email_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->nullable()->constrained('marketing_campaigns')->nullOnDelete();
            $table->foreignId('email_id')->nullable()->constrained('marketing_emails')->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('marketing_contacts')->nullOnDelete();
            $table->string('ses_message_id')->nullable()->index();
            $table->string('event_type'); // send, delivery, open, click, bounce, complaint, reject
            $table->timestamp('event_timestamp');
            $table->string('recipient_email');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['campaign_id', 'event_type']);
            $table->index(['recipient_email', 'event_type']);
        });

        // 6. Marketing Suppressions Table (Hard bounce, Complaints, Unsubscribes)
        Schema::create('marketing_suppressions', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('reason'); // unsubscribe, hard_bounce, complaint, manual
            $table->string('source')->nullable(); // RFC 8058, Web Link, SES Bounce Webhook, SES Complaint Webhook, Admin
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 7. Marketing Consent Logs (GDPR / PECR Compliance Audit)
        Schema::create('marketing_consent_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->nullable()->constrained('marketing_contacts')->cascadeOnDelete();
            $table->string('email')->index();
            $table->string('action'); // opt_in, opt_out, consent_updated, suppressed
            $table->string('consent_source');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_consent_logs');
        Schema::dropIfExists('marketing_suppressions');
        Schema::dropIfExists('marketing_email_events');
        Schema::dropIfExists('marketing_emails');
        Schema::dropIfExists('marketing_campaigns');
        Schema::dropIfExists('marketing_templates');
        Schema::dropIfExists('marketing_contacts');
    }
};
