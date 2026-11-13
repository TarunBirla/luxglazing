<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicWebsiteController;
use App\Http\Controllers\DatabaseSetupController;
use App\Http\Controllers\CrmDashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\B2bProspectController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Marketing\MarketingDashboardController;
use App\Http\Controllers\Marketing\ContactController;
use App\Http\Controllers\Marketing\CampaignController;
use App\Http\Controllers\Marketing\TemplateController;
use App\Http\Controllers\Marketing\AiWriterController;
use App\Http\Controllers\Marketing\SuppressionController;
use App\Http\Controllers\Marketing\EmailLogController;
use App\Http\Controllers\Marketing\SettingsController as MarketingSettingsController;
use App\Http\Controllers\Marketing\UnsubscribeController;
use App\Http\Controllers\Marketing\AwsSesWebhookController;

/*
|--------------------------------------------------------------------------
| Web Routes – Lux Glazing UK & AI Marketing Email Manager
|--------------------------------------------------------------------------
*/

// 1. One-Click Database Setup & Migration Route (Hidden / Direct URL for Server Live Setup)
Route::match(['get', 'post'], '/setup-database', [DatabaseSetupController::class, 'setup'])->name('setup.database');
Route::match(['get', 'post'], '/init-database', [DatabaseSetupController::class, 'setup']);
Route::match(['get', 'post'], '/db-migrate', [DatabaseSetupController::class, 'setup']);
Route::match(['get', 'post'], '/api/setup-database', [DatabaseSetupController::class, 'setup']);

// 2. Authentication Routes (Super Admin & Sales Agent Login, Logout, Password Change)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// 3. Public Luxury Architectural Glazing Website & Interactive Quote Estimator
Route::get('/', [PublicWebsiteController::class, 'index'])->name('home');
Route::post('/enquiry', [LeadController::class, 'store'])->name('lead.enquiry');

// 4. AI Lead Generation Chatbot & Automated Qualification Endpoints
Route::post('/api/chat/message', [AiAssistantController::class, 'chat'])->name('api.chat');
Route::post('/api/leads/capture', [LeadController::class, 'store'])->name('api.leads.capture');
Route::post('/api/ai/generate-email', [AiAssistantController::class, 'generateEmail'])->name('api.ai.email');

// 5. Public Unsubscribe & RFC 8058 One-Click Endpoints (Compliance)
Route::get('/unsubscribe/{token}', [UnsubscribeController::class, 'show'])->name('marketing.unsubscribe');
Route::post('/unsubscribe/{token}', [UnsubscribeController::class, 'process'])->name('marketing.unsubscribe.process');
Route::post('/marketing/unsubscribe/one-click/{token}', [UnsubscribeController::class, 'oneClick'])->name('marketing.unsubscribe.one-click');
Route::post('/api/marketing/unsubscribe/one-click/{token}', [UnsubscribeController::class, 'oneClick']);

// 6. Amazon SES Event Notification Webhook (SNS / EventBridge)
Route::post('/api/webhooks/aws-ses', [AwsSesWebhookController::class, 'handle'])->name('webhooks.aws-ses');

// 7. PROTECTED CRM & AI MARKETING EMAIL MANAGER (Requires Authentication)
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->group(function () {
        // Core CRM Routes
        Route::get('/dashboard', [CrmDashboardController::class, 'index'])->name('crm.dashboard');
        Route::post('/change-password', [AuthController::class, 'changePassword'])->name('crm.change-password');
        
        Route::post('/leads/store', [LeadController::class, 'store'])->name('crm.leads.store');
        Route::post('/leads/{id}/stage', [LeadController::class, 'updateStage'])->name('crm.leads.stage');
        Route::post('/leads/{id}/rescore', [LeadController::class, 'rescore'])->name('crm.leads.rescore');
        
        Route::post('/b2b/store', [B2bProspectController::class, 'store'])->name('crm.b2b.store');
        Route::post('/b2b/{id}/pitch', [B2bProspectController::class, 'generatePitch'])->name('crm.b2b.pitch');
        
        Route::post('/quotations/store', [QuotationController::class, 'store'])->name('crm.quotations.store');
        Route::get('/quotations/{id}', [QuotationController::class, 'show'])->name('crm.quotation.view');
        
        Route::post('/settings/ai', [AiAssistantController::class, 'saveSettings'])->name('crm.settings.ai');

        // ==========================================
        // AI MARKETING EMAIL MANAGER MODULE
        // ==========================================
        Route::prefix('marketing')->name('marketing.')->group(function () {
            // Dashboard & Analytics
            Route::get('/', [MarketingDashboardController::class, 'index'])->name('dashboard');
            Route::get('/analytics', [MarketingDashboardController::class, 'index'])->name('analytics');

            // Contacts Management
            Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
            Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
            Route::post('/contacts/{id}', [ContactController::class, 'update'])->name('contacts.update');
            Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');
            Route::get('/contacts-import', [ContactController::class, 'importView'])->name('contacts.import');
            Route::post('/contacts-import', [ContactController::class, 'import'])->name('contacts.import.process');
            Route::get('/contacts/sample-csv', [ContactController::class, 'downloadSample'])->name('contacts.sample');

            // Campaign Management
            Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
            Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
            Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
            Route::get('/campaigns/{id}', [CampaignController::class, 'show'])->name('campaigns.show');
            Route::get('/campaigns/{id}/preview', [CampaignController::class, 'preview'])->name('campaigns.preview');
            Route::post('/campaigns/{id}/test-email', [CampaignController::class, 'sendTestEmail'])->name('campaigns.test-email');
            Route::post('/campaigns/{id}/launch', [CampaignController::class, 'launch'])->name('campaigns.launch');
            Route::post('/campaigns/{id}/pause', [CampaignController::class, 'pause'])->name('campaigns.pause');
            Route::post('/campaigns/{id}/resume', [CampaignController::class, 'resume'])->name('campaigns.resume');
            Route::post('/campaigns/{id}/cancel', [CampaignController::class, 'cancel'])->name('campaigns.cancel');
            Route::post('/campaigns/{id}/duplicate', [CampaignController::class, 'duplicate'])->name('campaigns.duplicate');

            // Email Templates
            Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
            Route::post('/templates', [TemplateController::class, 'store'])->name('templates.store');
            Route::post('/templates/{id}', [TemplateController::class, 'update'])->name('templates.update');
            Route::delete('/templates/{id}', [TemplateController::class, 'destroy'])->name('templates.destroy');

            // AI Email Writer
            Route::get('/ai-writer', [AiWriterController::class, 'index'])->name('ai-writer.index');
            Route::post('/ai-writer/generate', [AiWriterController::class, 'generate'])->name('ai-writer.generate');
            Route::post('/ai-writer/save-template', [AiWriterController::class, 'saveAsTemplate'])->name('ai-writer.save-template');

            // Email Logs & Event Tracking
            Route::get('/logs', [EmailLogController::class, 'index'])->name('logs.index');
            Route::get('/logs/{id}', [EmailLogController::class, 'show'])->name('logs.show');

            // Suppression List
            Route::get('/suppressions', [SuppressionController::class, 'index'])->name('suppressions.index');
            Route::post('/suppressions', [SuppressionController::class, 'store'])->name('suppressions.store');
            Route::delete('/suppressions/{id}', [SuppressionController::class, 'destroy'])->name('suppressions.destroy');

            // Settings (AWS SES & OpenAI)
            Route::get('/settings', [MarketingSettingsController::class, 'index'])->name('settings.index');
            Route::post('/settings', [MarketingSettingsController::class, 'update'])->name('settings.update');
            Route::post('/settings/test-openai', [MarketingSettingsController::class, 'testOpenAi'])->name('settings.test-openai');
        });
    });

    // Shortcut to CRM Dashboard
    Route::get('/crm', [CrmDashboardController::class, 'index'])->name('crm.index');
});
