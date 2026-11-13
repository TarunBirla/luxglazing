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

/*
|--------------------------------------------------------------------------
| Web Routes – Lux Glazing UK
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

// 5. PROTECTED CRM & Admin Dashboard (Requires Super Admin or Sales Agent Authentication)
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->group(function () {
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
    });

    // Shortcut to CRM Dashboard
    Route::get('/crm', [CrmDashboardController::class, 'index'])->name('crm.index');
});
