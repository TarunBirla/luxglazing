<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lux Glazing UK – Enterprise CRM & AI Lead Engine</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --lux-gold: #E5D2A5;
            --lux-gold-hover: #d8c28f;
            --lux-gold-light: #F7EEDB;
            --lux-black: #0B0E14;
            --lux-dark: #121824;
            --lux-card: #182030;
            --lux-card-hover: #1f2a3f;
            --lux-border: rgba(229, 210, 165, 0.25);
            --lux-border-light: rgba(255, 255, 255, 0.08);
            --lux-text: #F3F4F6;
            --lux-muted: #9CA3AF;
            --lux-success: #10B981;
            --lux-warning: #F59E0B;
            --lux-danger: #EF4444;
            --lux-info: #3B82F6;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background-color: var(--lux-black);
            color: var(--lux-text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* Sidebar - Fully Adaptive for 14" Laptops & Screens */
        .sidebar {
            width: 260px;
            background: var(--lux-dark);
            border-right: 1px solid var(--lux-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 50;
            transition: transform 0.3s ease, width 0.3s ease;
        }
        .sidebar-brand {
            padding: 22px 18px;
            border-bottom: 1px solid var(--lux-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .brand-logo {
            font-family: 'Cinzel', serif;
            font-size: 19px;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--lux-gold);
            text-transform: uppercase;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .brand-tag {
            font-size: 9px;
            letter-spacing: 1.5px;
            color: var(--lux-muted);
            text-transform: uppercase;
            margin-top: 3px;
        }
        .sidebar-nav {
            padding: 14px 10px;
            flex: 1;
            overflow-y: auto;
        }
        .nav-category {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--lux-gold);
            padding: 10px 10px 4px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 12px;
            font-size: 13px;
            color: var(--lux-muted);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
            margin-bottom: 2px;
        }
        .nav-item i {
            width: 18px;
            text-align: center;
            font-size: 14px;
            color: var(--lux-gold);
        }
        .nav-item:hover, .nav-item.active {
            color: #FFFFFF;
            background: rgba(229, 210, 165, 0.12);
            border: 1px solid rgba(229, 210, 165, 0.3);
        }
        .nav-item.active {
            color: var(--lux-gold);
            font-weight: 600;
        }

        /* Sidebar Profile Card at Bottom */
        .sidebar-user-box {
            padding: 14px 16px;
            border-top: 1px solid var(--lux-border-light);
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .user-avatar-badge {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--lux-gold);
            color: #0B0E14;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }
        .user-info-text {
            flex: 1;
            margin-left: 10px;
            overflow: hidden;
        }
        .user-name-label {
            font-size: 12px;
            font-weight: 700;
            color: #FFFFFF;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-role-label {
            font-size: 10px;
            color: var(--lux-gold);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Main Content Wrapper */
        .main-wrapper {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - 260px);
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        /* Top Header */
        .topbar {
            height: 70px;
            background: var(--lux-dark);
            border-bottom: 1px solid var(--lux-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .sidebar-toggle-btn {
            background: transparent;
            border: 1px solid var(--lux-border-light);
            color: var(--lux-gold);
            width: 36px;
            height: 36px;
            border-radius: 6px;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }
        .sidebar-toggle-btn:hover {
            border-color: var(--lux-gold);
        }
        .topbar-title {
            font-family: 'Cinzel', serif;
            font-size: 17px;
            color: #FFFFFF;
            letter-spacing: 1px;
        }
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Buttons with #E5D2A5 */
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-gold {
            background: var(--lux-gold);
            color: #0B0E14;
            box-shadow: 0 4px 12px rgba(229, 210, 165, 0.25);
        }
        .btn-gold:hover {
            background: var(--lux-gold-hover);
            transform: translateY(-1px);
        }
        .btn-dark {
            background: var(--lux-card);
            color: var(--lux-text);
            border: 1px solid var(--lux-border-light);
        }
        .btn-dark:hover {
            border-color: var(--lux-gold);
            color: var(--lux-gold);
        }
        .btn-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #F87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.25);
        }

        .content-body {
            padding: 24px 28px;
            flex: 1;
        }

        /* Metric Cards Grid - Fully Responsive */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
            margin-bottom: 26px;
        }
        .metric-card {
            background: var(--lux-card);
            border: 1px solid var(--lux-border-light);
            border-radius: 12px;
            padding: 20px;
            position: relative;
            overflow: hidden;
            transition: all 0.2s;
        }
        .metric-card:hover {
            border-color: var(--lux-border);
            transform: translateY(-2px);
        }
        .metric-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 4px; height: 100%;
            background: var(--lux-gold);
        }
        .metric-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .metric-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--lux-muted);
            font-weight: 600;
        }
        .metric-icon {
            color: var(--lux-gold);
            font-size: 16px;
        }
        .metric-val {
            font-size: 24px;
            font-weight: 700;
            color: #FFFFFF;
            margin-bottom: 4px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .metric-sub {
            font-size: 11px;
            color: var(--lux-gold);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Section Layouts */
        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.2s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .section-title {
            font-family: 'Cinzel', serif;
            font-size: 18px;
            color: var(--lux-gold-light);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Responsive Data Tables */
        .data-card {
            background: var(--lux-card);
            border: 1px solid var(--lux-border-light);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 26px;
        }
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            min-width: 680px;
        }
        th {
            background: rgba(0, 0, 0, 0.45);
            color: var(--lux-gold);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-size: 11px;
            font-weight: 700;
            text-align: left;
            padding: 13px 16px;
            border-bottom: 1px solid var(--lux-border-light);
            white-space: nowrap;
        }
        td {
            padding: 13px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            color: var(--lux-text);
            vertical-align: middle;
        }
        tr:hover td {
            background: var(--lux-card-hover);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }
        .badge-hot { background: rgba(239, 68, 68, 0.2); color: #FCA5A5; border: 1px solid rgba(239, 68, 68, 0.4); }
        .badge-warm { background: rgba(245, 158, 11, 0.2); color: #FCD34D; border: 1px solid rgba(245, 158, 11, 0.4); }
        .badge-cold { background: rgba(156, 163, 175, 0.2); color: #D1D5DB; border: 1px solid rgba(156, 163, 175, 0.4); }
        .badge-stage { background: rgba(229, 210, 165, 0.15); color: var(--lux-gold); border: 1px solid var(--lux-border); }

        /* Kanban Board */
        .kanban-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(210px, 1fr));
            gap: 14px;
            overflow-x: auto;
            padding-bottom: 16px;
            -webkit-overflow-scrolling: touch;
        }
        .kanban-col {
            background: rgba(18, 24, 36, 0.85);
            border: 1px solid var(--lux-border-light);
            border-radius: 10px;
            padding: 12px;
            min-height: 480px;
        }
        .kanban-col-header {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--lux-gold);
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--lux-border-light);
        }
        .kanban-item {
            background: var(--lux-card);
            border: 1px solid var(--lux-border-light);
            border-radius: 8px;
            padding: 11px;
            margin-bottom: 10px;
            font-size: 12px;
            transition: all 0.2s;
        }
        .kanban-item:hover {
            border-color: var(--lux-border);
            transform: translateY(-2px);
        }
        .kanban-lead-name { font-weight: 600; color: #FFF; margin-bottom: 4px; }
        .kanban-lead-meta { font-size: 11px; color: var(--lux-muted); margin-bottom: 8px; }

        /* Modal Dialogs */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(4px);
            z-index: 100;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .modal.active { display: flex; }
        .modal-content {
            background: var(--lux-dark);
            border: 1px solid var(--lux-border);
            border-radius: 14px;
            padding: 28px;
            max-width: 580px;
            width: 100%;
            box-shadow: 0 20px 45px rgba(0,0,0,0.85);
            max-height: 90vh;
            overflow-y: auto;
        }
        .form-group { margin-bottom: 16px; position: relative; }
        .form-label { display: block; font-size: 11px; color: var(--lux-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.6px; font-weight: 600; }
        .form-control {
            width: 100%;
            background: rgba(0, 0, 0, 0.45);
            border: 1px solid var(--lux-border-light);
            color: #FFF;
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 13px;
            transition: border-color 0.2s;
        }
        .form-control:focus { outline: none; border-color: var(--lux-gold); }

        .alert-box {
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6EE7B7;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* 14-Inch Laptop & Mobile Responsiveness */
        @media (max-width: 1200px) {
            .sidebar { width: 230px; }
            .main-wrapper { margin-left: 230px; width: calc(100% - 230px); }
            .metrics-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; width: 100%; }
            .sidebar-toggle-btn { display: flex; }
            .metrics-grid { grid-template-columns: 1fr; }
            .topbar { padding: 0 16px; }
            .content-body { padding: 18px 16px; }
        }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebarNav">
        <div class="sidebar-brand">
            <div>
                <a href="{{ url('/') }}" class="brand-logo">
                    <i class="fa-solid fa-gem" style="color: var(--lux-gold);"></i>
                    <span>LUX GLAZING</span>
                </a>
                <div class="brand-tag">AI CRM & LEAD ENGINE</div>
            </div>
            <button class="sidebar-toggle-btn" onclick="toggleSidebar()" style="display: none;" id="closeSidebarBtn">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-category"><i class="fa-solid fa-chart-line"></i> Operations</div>
            <div class="nav-item active" onclick="switchTab('dashboard')"><i class="fa-solid fa-gauge-high"></i> Dashboard & KPIs</div>
            <div class="nav-item" onclick="switchTab('leads')"><i class="fa-solid fa-users"></i> Lead Management</div>
            <div class="nav-item" onclick="switchTab('pipeline')"><i class="fa-solid fa-table-columns"></i> Pipeline Kanban</div>
            <div class="nav-item" onclick="switchTab('scoring')"><i class="fa-solid fa-brain"></i> AI Lead Scoring</div>
            <div class="nav-item" onclick="switchTab('quotations')"><i class="fa-solid fa-file-invoice-dollar"></i> Quotations (20% VAT)</div>

            <div class="nav-category"><i class="fa-solid fa-wand-magic-sparkles"></i> AI Growth Suite</div>
            <div class="nav-item" onclick="switchTab('b2b')"><i class="fa-solid fa-building"></i> B2B Architects Hub</div>
            <div class="nav-item" onclick="switchTab('ai-emails')"><i class="fa-solid fa-envelope-open-text"></i> AI Email Generator</div>
            <div class="nav-item" onclick="switchTab('campaigns')"><i class="fa-solid fa-bullhorn"></i> Outreach Campaigns</div>
            <div class="nav-item" onclick="switchTab('tasks')"><i class="fa-solid fa-calendar-check"></i> Tasks & Surveys</div>

            <div class="nav-category"><i class="fa-solid fa-magnifying-glass-chart"></i> Growth & Marketing</div>
            <div class="nav-item" onclick="switchTab('seo')"><i class="fa-solid fa-arrow-trend-up"></i> UK SEO Performance</div>
            <div class="nav-item" onclick="switchTab('advertising')"><i class="fa-brands fa-google"></i> Google Ads & Meta</div>
            <div class="nav-item" onclick="switchTab('settings')"><i class="fa-solid fa-sliders"></i> System Settings</div>
        </nav>

        <!-- Current Logged-in User Info & Logout -->
        <div class="sidebar-user-box">
            <div class="user-avatar-badge">
                {{ substr(Auth::user()->name ?? 'Admin', 0, 1) }}
            </div>
            <div class="user-info-text">
                <div class="user-name-label">{{ Auth::user()->name ?? 'Administrator' }}</div>
                <div class="user-role-label"><i class="fa-solid fa-shield-halved"></i> {{ Auth::user()->role ?? 'super_admin' }}</div>
            </div>
            <div style="display: flex; gap: 4px;">
                <button class="btn btn-dark" style="padding: 6px 8px; font-size: 11px;" onclick="openModal('passwordModal')" title="Change Password">
                    <i class="fa-solid fa-key" style="color: var(--lux-gold);"></i>
                </button>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="padding: 6px 8px; font-size: 11px;" title="Logout">
                        <i class="fa-solid fa-power-off"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="main-wrapper">
        <header class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle-btn" onclick="toggleSidebar()" title="Toggle Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="topbar-title">Executive CRM Control Panel</div>
            </div>
            <div class="topbar-actions">
                <a href="{{ url('/') }}" target="_blank" class="btn btn-dark" title="Open Public Site">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span style="display: inline-block;">Website</span>
                </a>
                <button class="btn btn-gold" onclick="openModal('addLeadModal')">
                    <i class="fa-solid fa-plus"></i>
                    <span>New Lead</span>
                </button>
                <button class="btn btn-dark" onclick="openModal('passwordModal')" title="Profile & Password">
                    <i class="fa-solid fa-user-shield" style="color: var(--lux-gold);"></i>
                    <span>Profile</span>
                </button>
            </div>
        </header>

        <main class="content-body">
            @if(session('success'))
                <div class="alert-box">
                    <i class="fa-solid fa-circle-check" style="font-size: 16px;"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-box" style="background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); color: #FCA5A5;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- 1. TAB: DASHBOARD -->
            <div id="tab-dashboard" class="tab-content active">
                <!-- Top Key Metrics -->
                <div class="metrics-grid">
                    <div class="metric-card">
                        <div class="metric-header">
                            <span class="metric-label">Total Inbound Leads</span>
                            <i class="fa-solid fa-users-viewfinder metric-icon"></i>
                        </div>
                        <div class="metric-val">{{ $totalLeads }}</div>
                        <div class="metric-sub"><i class="fa-solid fa-fire"></i> {{ $hotLeads }} Hot Priority Leads</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-header">
                            <span class="metric-label">Pipeline Value (GBP)</span>
                            <i class="fa-solid fa-pound-sign metric-icon"></i>
                        </div>
                        <div class="metric-val">£{{ number_format($pipelineValue, 2) }}</div>
                        <div class="metric-sub"><i class="fa-solid fa-chart-line"></i> Active Estimations</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-header">
                            <span class="metric-label">Won Contracts</span>
                            <i class="fa-solid fa-trophy metric-icon"></i>
                        </div>
                        <div class="metric-val">£{{ number_format($wonValue, 2) }}</div>
                        <div class="metric-sub"><i class="fa-solid fa-certificate"></i> FENSA Orders Secured</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-header">
                            <span class="metric-label">B2B Architects & Trade</span>
                            <i class="fa-solid fa-handshake metric-icon"></i>
                        </div>
                        <div class="metric-val">{{ $b2bProspectsCount }}</div>
                        <div class="metric-sub"><i class="fa-solid fa-compass-drafting"></i> Active Trade Accounts</div>
                    </div>
                </div>

                <div class="section-header">
                    <h2 class="section-title"><i class="fa-solid fa-fire-flame-curved" style="color: var(--lux-gold);"></i> Hot Priority Project Leads</h2>
                    <button class="btn btn-dark" style="font-size: 11px;" onclick="switchTab('leads')">View All Leads →</button>
                </div>

                <div class="data-card">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Client Details</th>
                                    <th>System Required</th>
                                    <th>Postcode</th>
                                    <th>Budget Guide</th>
                                    <th>Timeframe</th>
                                    <th>AI Score</th>
                                    <th>Pipeline Stage</th>
                                    <th>Estimated Value</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leads->take(6) as $lead)
                                <tr>
                                    <td>
                                        <strong>{{ $lead->name }}</strong><br>
                                        <span style="font-size: 11px; color: var(--lux-muted)"><i class="fa-solid fa-phone" style="font-size: 10px; color: var(--lux-gold);"></i> {{ $lead->phone }}</span>
                                    </td>
                                    <td>{{ $lead->service_type }}</td>
                                    <td><code style="color: var(--lux-gold);">{{ $lead->postcode ?? 'UK' }}</code></td>
                                    <td>{{ $lead->estimated_budget }}</td>
                                    <td>{{ $lead->timeframe }}</td>
                                    <td>
                                        <span class="badge badge-{{ strtolower($lead->ai_classification) }}">
                                            <i class="fa-solid fa-bolt"></i> {{ $lead->ai_score }}/100 ({{ $lead->ai_classification }})
                                        </span>
                                    </td>
                                    <td><span class="badge badge-stage">{{ $lead->stage }}</span></td>
                                    <td><strong style="color: var(--lux-gold);">£{{ number_format($lead->estimated_value, 2) }}</strong></td>
                                    <td>
                                        <button class="btn btn-dark" style="padding: 4px 8px; font-size: 11px;" onclick="openRescoreModal('{{ $lead->id }}', '{{ $lead->name }}', '{{ $lead->ai_qualification_summary }}', '{{ $lead->ai_recommended_action }}')">
                                            <i class="fa-solid fa-sparkles"></i> AI Insights
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="9" style="text-align: center; color: var(--lux-muted);">No leads recorded yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 2. TAB: LEADS -->
            <div id="tab-leads" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title"><i class="fa-solid fa-users" style="color: var(--lux-gold);"></i> All Customer Leads & Inquiries</h2>
                    <button class="btn btn-gold" onclick="openModal('addLeadModal')"><i class="fa-solid fa-plus"></i> New Lead</button>
                </div>

                <div class="data-card">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Client Details</th>
                                    <th>Glazing Solution</th>
                                    <th>Postcode</th>
                                    <th>Source Channel</th>
                                    <th>AI Viability Score</th>
                                    <th>Current Stage</th>
                                    <th>Update Pipeline Stage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leads as $lead)
                                <tr>
                                    <td>
                                        <strong>{{ $lead->name }}</strong><br>
                                        <span style="font-size: 11px; color: var(--lux-muted)">{{ $lead->phone }} | {{ $lead->email }}</span>
                                    </td>
                                    <td>{{ $lead->service_type }} ({{ $lead->project_type }})</td>
                                    <td><code>{{ $lead->postcode }}</code></td>
                                    <td><i class="fa-solid fa-satellite-dish" style="font-size: 10px; color: var(--lux-gold);"></i> {{ $lead->source }}</td>
                                    <td>
                                        <span class="badge badge-{{ strtolower($lead->ai_classification) }}">
                                            {{ $lead->ai_score }}/100 • {{ $lead->ai_classification }}
                                        </span>
                                    </td>
                                    <td><span class="badge badge-stage">{{ $lead->stage }}</span></td>
                                    <td>
                                        <form action="{{ route('crm.leads.stage', $lead->id) }}" method="POST" style="display: flex; gap: 6px;">
                                            @csrf
                                            <select name="stage" class="form-control" style="padding: 4px 8px; font-size: 11px; width: 140px;">
                                                <option value="New Lead" {{ $lead->stage === 'New Lead' ? 'selected' : '' }}>New Lead</option>
                                                <option value="Contacted" {{ $lead->stage === 'Contacted' ? 'selected' : '' }}>Contacted</option>
                                                <option value="Qualified" {{ $lead->stage === 'Qualified' ? 'selected' : '' }}>Qualified</option>
                                                <option value="Survey Booked" {{ $lead->stage === 'Survey Booked' ? 'selected' : '' }}>Survey Booked</option>
                                                <option value="Quote Sent" {{ $lead->stage === 'Quote Sent' ? 'selected' : '' }}>Quote Sent</option>
                                                <option value="Won" {{ $lead->stage === 'Won' ? 'selected' : '' }}>Won</option>
                                                <option value="Lost" {{ $lead->stage === 'Lost' ? 'selected' : '' }}>Lost</option>
                                            </select>
                                            <button type="submit" class="btn btn-dark" style="padding: 4px 8px; font-size: 11px;">Save</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 3. TAB: PIPELINE KANBAN -->
            <div id="tab-pipeline" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title"><i class="fa-solid fa-table-columns" style="color: var(--lux-gold);"></i> Sales Pipeline Kanban Board</h2>
                </div>

                <div class="kanban-grid">
                    @foreach(['New Lead', 'Contacted', 'Qualified', 'Survey Booked', 'Quote Sent', 'Won'] as $stage)
                    <div class="kanban-col">
                        <div class="kanban-col-header">
                            <span>{{ $stage }}</span>
                            <span class="badge badge-stage" style="font-size: 10px;">{{ count($pipeline[$stage] ?? []) }}</span>
                        </div>
                        @forelse($pipeline[$stage] ?? [] as $item)
                        <div class="kanban-item">
                            <div class="kanban-lead-name">{{ $item->name }}</div>
                            <div class="kanban-lead-meta"><i class="fa-solid fa-door-open" style="font-size: 10px; color: var(--lux-gold);"></i> {{ $item->service_type }} • {{ $item->postcode }}</div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px;">
                                <span class="badge badge-{{ strtolower($item->ai_classification) }}" style="font-size: 10px;">{{ $item->ai_score }} pts</span>
                                <strong style="color: var(--lux-gold);">£{{ number_format($item->estimated_value, 0) }}</strong>
                            </div>
                        </div>
                        @empty
                        <div style="font-size: 11px; color: var(--lux-muted); text-align: center; margin-top: 24px;">No active leads</div>
                        @endforelse
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- 4. TAB: AI LEAD SCORING -->
            <div id="tab-scoring" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title"><i class="fa-solid fa-brain" style="color: var(--lux-gold);"></i> OpenAI Lead Viability Diagnostics</h2>
                </div>

                <div class="data-card">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Lead Name</th>
                                    <th>Classification</th>
                                    <th>AI Viability Summary</th>
                                    <th>Recommended Sales Action</th>
                                    <th>Estimated Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leads as $lead)
                                <tr>
                                    <td>
                                        <strong>{{ $lead->name }}</strong><br>
                                        <span style="font-size: 11px; color: var(--lux-muted);">{{ $lead->service_type }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ strtolower($lead->ai_classification) }}">
                                            <i class="fa-solid fa-bolt"></i> {{ $lead->ai_score }}/100 • {{ $lead->ai_classification }}
                                        </span>
                                    </td>
                                    <td style="max-width: 320px; font-size: 12px; line-height: 1.4;">
                                        {{ $lead->ai_qualification_summary ?? 'Evaluated on budget, location and timeframe viability.' }}
                                    </td>
                                    <td style="max-width: 300px; font-size: 12px; color: var(--lux-gold-light);">
                                        <i class="fa-solid fa-arrow-right" style="color: var(--lux-gold); font-size: 10px;"></i> {{ $lead->ai_recommended_action ?? 'Contact prospect to confirm architectural requirements.' }}
                                    </td>
                                    <td><strong style="color: var(--lux-gold);">£{{ number_format($lead->estimated_value, 2) }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 5. TAB: QUOTATIONS (20% UK VAT) -->
            <div id="tab-quotations" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title"><i class="fa-solid fa-file-invoice-dollar" style="color: var(--lux-gold);"></i> Quotation Management (UK 20% VAT Compliant)</h2>
                    <button class="btn btn-gold" onclick="openModal('addQuoteModal')"><i class="fa-solid fa-plus"></i> Create Quotation</button>
                </div>

                <div class="data-card">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Quote #</th>
                                    <th>Client Name</th>
                                    <th>Project Title</th>
                                    <th>Net Subtotal</th>
                                    <th>VAT (20%)</th>
                                    <th>Total (GBP)</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quotations as $quote)
                                <tr>
                                    <td><strong style="color: var(--lux-gold);">{{ $quote->quote_number }}</strong></td>
                                    <td>{{ $quote->customer_name }}</td>
                                    <td>{{ $quote->project_title }}</td>
                                    <td>£{{ number_format($quote->subtotal, 2) }}</td>
                                    <td>£{{ number_format($quote->vat_amount, 2) }}</td>
                                    <td><strong style="color: var(--lux-gold);">£{{ number_format($quote->total_amount, 2) }}</strong></td>
                                    <td><span class="badge badge-stage">{{ $quote->status }}</span></td>
                                    <td>
                                        <a href="{{ route('crm.quotation.view', $quote->id) }}" target="_blank" class="btn btn-dark" style="padding: 4px 10px; font-size: 11px;">
                                            <i class="fa-solid fa-print"></i> View & Print
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="8" style="text-align: center; color: var(--lux-muted);">No quotations generated yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 6. TAB: B2B TRADE PROSPECTS -->
            <div id="tab-b2b" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title"><i class="fa-solid fa-building" style="color: var(--lux-gold);"></i> B2B Architects, Builders & Developers Directory</h2>
                    <button class="btn btn-gold" onclick="openModal('addB2bModal')"><i class="fa-solid fa-plus"></i> Add Trade Target</button>
                </div>

                <div class="data-card">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Company / Practice</th>
                                    <th>Category</th>
                                    <th>Contact Person</th>
                                    <th>City / Region</th>
                                    <th>Status</th>
                                    <th>AI Trade Pitch</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($b2bProspects as $prospect)
                                <tr>
                                    <td>
                                        <strong>{{ $prospect->company_name }}</strong><br>
                                        <a href="{{ $prospect->website }}" target="_blank" style="font-size: 11px; color: var(--lux-gold); text-decoration: none;"><i class="fa-solid fa-link"></i> {{ $prospect->website }}</a>
                                    </td>
                                    <td>{{ $prospect->category }}</td>
                                    <td>{{ $prospect->contact_person }} ({{ $prospect->job_title }})</td>
                                    <td>{{ $prospect->city }}</td>
                                    <td><span class="badge badge-stage">{{ $prospect->status }}</span></td>
                                    <td>
                                        <button class="btn btn-dark" style="padding: 4px 10px; font-size: 11px;" onclick="viewPitch('{{ addslashes($prospect->company_name) }}', `{{ addslashes($prospect->ai_personalized_pitch) }}`)">
                                            <i class="fa-solid fa-envelope"></i> Preview Pitch
                                        </button>
                                    </td>
                                    <td><span style="font-size: 11px; color: var(--lux-muted)">{{ $prospect->email }}</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="7" style="text-align: center; color: var(--lux-muted);">No B2B trade targets recorded.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 7. TAB: AI EMAIL GENERATOR -->
            <div id="tab-ai-emails" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title"><i class="fa-solid fa-envelope-open-text" style="color: var(--lux-gold);"></i> OpenAI Commercial Email Assistant</h2>
                </div>

                <div class="data-card" style="padding: 24px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                        <div>
                            <div class="form-group">
                                <label class="form-label">Email Campaign Type</label>
                                <select id="aiEmailType" class="form-control">
                                    <option value="quote_followup">Quotation Follow-Up (High Conversion)</option>
                                    <option value="survey_confirmation">Laser Site Survey Confirmation</option>
                                    <option value="architect_trade_pitch">RIBA Architect & Trade Partner Pitch</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Recipient Name</label>
                                <input type="text" id="aiRecipientName" class="form-control" value="Charles Kensington">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Project / Solution</label>
                                <input type="text" id="aiProjectName" class="form-control" value="Cortizo 5-Panel Slimline Bifold Doors">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Custom Notes / Discount Offered</label>
                                <textarea id="aiCustomDetails" class="form-control" rows="3" placeholder="e.g. Offering free trickle vents upgrade or 5% early booking survey discount."></textarea>
                            </div>
                            <button class="btn btn-gold" onclick="generateAiEmail()"><i class="fa-solid fa-wand-magic-sparkles"></i> Generate Email with AI</button>
                        </div>

                        <div>
                            <div class="form-group">
                                <label class="form-label">Generated Subject Line</label>
                                <input type="text" id="generatedSubject" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Generated Email Body</label>
                                <textarea id="generatedBody" class="form-control" rows="11" readonly></textarea>
                            </div>
                            <button class="btn btn-dark" onclick="copyEmail()"><i class="fa-solid fa-copy"></i> Copy to Clipboard</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 8. TAB: CAMPAIGNS -->
            <div id="tab-campaigns" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title"><i class="fa-solid fa-bullhorn" style="color: var(--lux-gold);"></i> Active Email Broadcast Campaigns</h2>
                </div>
                <div class="data-card" style="padding: 24px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                        <div style="background: rgba(0,0,0,0.3); border: 1px solid var(--lux-border-light); padding: 18px; border-radius: 8px;">
                            <h3 style="color: var(--lux-gold); font-size: 14px; margin-bottom: 6px;"><i class="fa-solid fa-landmark"></i> London Architects Network</h3>
                            <p style="font-size: 12px; color: var(--lux-muted); margin-bottom: 12px;">Audience: 120 RIBA Chartered Practices in London & Surrey.</p>
                            <span class="badge badge-stage">Open Rate: 42% • Replies: 18</span>
                        </div>
                        <div style="background: rgba(0,0,0,0.3); border: 1px solid var(--lux-border-light); padding: 18px; border-radius: 8px;">
                            <h3 style="color: var(--lux-gold); font-size: 14px; margin-bottom: 6px;"><i class="fa-solid fa-clock-rotate-left"></i> Quote Sent 48h Follow-up</h3>
                            <p style="font-size: 12px; color: var(--lux-muted); margin-bottom: 12px;">Audience: Residential proposals older than 48 hours.</p>
                            <span class="badge badge-stage">Response Rate: 28% • Won: 4</span>
                        </div>
                        <div style="background: rgba(0,0,0,0.3); border: 1px solid var(--lux-border-light); padding: 18px; border-radius: 8px;">
                            <h3 style="color: var(--lux-gold); font-size: 14px; margin-bottom: 6px;"><i class="fa-solid fa-hard-hat"></i> Commercial Fit-Out Contractors</h3>
                            <p style="font-size: 12px; color: var(--lux-muted); margin-bottom: 12px;">Audience: Main contractors tendering retail & facade packages.</p>
                            <span class="badge badge-stage">Meeting Rate: 16% • Tenders: 2</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 9. TAB: TASKS -->
            <div id="tab-tasks" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title"><i class="fa-solid fa-calendar-check" style="color: var(--lux-gold);"></i> Site Surveys & Action Reminders</h2>
                </div>
                <div class="data-card" style="padding: 24px;">
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(0,0,0,0.3); padding: 14px 18px; border-radius: 8px; border-left: 3px solid #10B981; flex-wrap: wrap; gap: 10px;">
                            <div>
                                <strong style="color: #FFF;"><i class="fa-solid fa-ruler-combined" style="color: var(--lux-gold);"></i> Laser Site Survey – Charles Kensington</strong><br>
                                <span style="font-size: 12px; color: var(--lux-muted);">42 King's Road, Chelsea • Friday 10:00 AM • Surveyor: Oliver Davies</span>
                            </div>
                            <span class="badge badge-hot">High Priority</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(0,0,0,0.3); padding: 14px 18px; border-radius: 8px; border-left: 3px solid var(--lux-gold); flex-wrap: wrap; gap: 10px;">
                            <div>
                                <strong style="color: #FFF;"><i class="fa-solid fa-file-signature" style="color: var(--lux-gold);"></i> Specification Review – Victoria Sterling</strong><br>
                                <span style="font-size: 12px; color: var(--lux-muted);">Richmond Hill project • Verify Cortizo Cor Vision glass weights with structural engineer</span>
                            </div>
                            <span class="badge badge-warm">Pending Review</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 10. TAB: SEO PERFORMANCE -->
            <div id="tab-seo" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title"><i class="fa-solid fa-arrow-trend-up" style="color: var(--lux-gold);"></i> UK Glazing Target Keywords & Organic Google Rankings</h2>
                </div>

                <div class="data-card">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Target UK Keyword</th>
                                    <th>Target Landing Page</th>
                                    <th>Search Intent</th>
                                    <th>Priority</th>
                                    <th>Current Google UK Rank</th>
                                    <th>Target Rank</th>
                                    <th>Monthly Search Volume</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($seoKeywords as $kw)
                                <tr>
                                    <td><strong>{{ $kw->keyword }}</strong></td>
                                    <td><code style="color: var(--lux-gold);">{{ $kw->target_page }}</code></td>
                                    <td>{{ $kw->intent }}</td>
                                    <td><span class="badge badge-{{ $kw->priority === 'High' ? 'hot' : 'warm' }}">{{ $kw->priority }}</span></td>
                                    <td><strong style="color: #10B981;"><i class="fa-solid fa-medal"></i> #{{ $kw->current_position }}</strong></td>
                                    <td>#{{ $kw->target_position }}</td>
                                    <td>{{ number_format($kw->search_volume_est) }} searches/mo</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 11. TAB: ADVERTISING & PPC -->
            <div id="tab-advertising" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title"><i class="fa-brands fa-google" style="color: var(--lux-gold);"></i> Paid Advertising & Performance Metrics (Google & Meta)</h2>
                </div>

                <div class="data-card">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Channel</th>
                                    <th>Period</th>
                                    <th>Spend (GBP)</th>
                                    <th>Impressions</th>
                                    <th>Clicks</th>
                                    <th>Qualified Leads</th>
                                    <th>Cost Per Lead (CPL)</th>
                                    <th>Won Contracts</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($marketingMetrics as $m)
                                <tr>
                                    <td><strong>{{ $m->channel }}</strong></td>
                                    <td>{{ $m->period_label }}</td>
                                    <td>£{{ number_format($m->spend_gbp, 2) }}</td>
                                    <td>{{ number_format($m->impressions) }}</td>
                                    <td>{{ number_format($m->clicks) }}</td>
                                    <td><strong style="color: var(--lux-gold);">{{ $m->leads_generated }}</strong></td>
                                    <td>£{{ number_format($m->cost_per_lead_gbp, 2) }}</td>
                                    <td>{{ $m->conversions_won }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 12. TAB: SETTINGS & OPENAI -->
            <div id="tab-settings" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title"><i class="fa-solid fa-sliders" style="color: var(--lux-gold);"></i> System Settings & OpenAI API Integration</h2>
                </div>

                <div class="data-card" style="padding: 24px; max-width: 650px;">
                    <form action="{{ route('crm.settings.ai') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">OpenAI API Key</label>
                            <input type="password" name="openai_api_key" class="form-control" placeholder="sk-proj-..." value="{{ env('OPENAI_API_KEY') }}">
                            <span style="font-size: 11px; color: var(--lux-muted);">Configured in .env or stored securely in system settings.</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Default AI Model</label>
                            <select name="openai_model" class="form-control">
                                <option value="gpt-4o" {{ $settings['openai_model'] === 'gpt-4o' ? 'selected' : '' }}>GPT-4o (Recommended for Architectural Glazing Lead Analysis)</option>
                                <option value="gpt-4o-mini" {{ $settings['openai_model'] === 'gpt-4o-mini' ? 'selected' : '' }}>GPT-4o-mini (High Speed / Lower Cost)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Notification Email (.env: ADMIN_MAIL)</label>
                            <input type="text" class="form-control" value="{{ env('ADMIN_MAIL', 'tarun@thenexteck.com') }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">UK Standard VAT Rate</label>
                            <input type="text" class="form-control" value="20%" readonly>
                        </div>
                        <button type="submit" class="btn btn-gold"><i class="fa-solid fa-floppy-disk"></i> Save AI Settings</button>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <!-- MODAL: ADD LEAD -->
    <div id="addLeadModal" class="modal">
        <div class="modal-content">
            <h3 style="font-family: 'Cinzel', serif; color: var(--lux-gold); margin-bottom: 16px;"><i class="fa-solid fa-plus-circle"></i> Register New Glazing Enquiry</h3>
            <form action="{{ route('crm.leads.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Client Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Lord & Lady Hamilton">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" required placeholder="+44 7700 900123">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="client@example.co.uk">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Service Type</label>
                        <select name="service_type" class="form-control" required>
                            <option value="Bifold Doors">Aluminium Bifold Doors</option>
                            <option value="Sliding Doors">Ultra-Slim Sliding Doors</option>
                            <option value="Heritage/Steel Doors">Heritage Steel-Look Crittall</option>
                            <option value="Aluminium Windows">Aluminium Casement Windows</option>
                            <option value="uPVC Windows">uPVC Flush Sash Windows</option>
                            <option value="Rooflights">Structural Glass Roof Lanterns</option>
                            <option value="Commercial Glazing">Commercial Curtain Walling</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">UK Postcode</label>
                        <input type="text" name="postcode" class="form-control" placeholder="e.g. SW1X 7PJ">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Estimated Budget</label>
                        <select name="estimated_budget" class="form-control">
                            <option value="£3,000 - £6,000">£3,000 - £6,000</option>
                            <option value="£6,000 - £15,000">£6,000 - £15,000</option>
                            <option value="£15,000 - £35,000">£15,000 - £35,000</option>
                            <option value="£35,000 - £60,000">£35,000 - £60,000</option>
                            <option value="£60,000+">£60,000+</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Project Timeframe</label>
                        <select name="timeframe" class="form-control">
                            <option value="Immediate (Within 4 weeks)">Immediate (Within 4 weeks)</option>
                            <option value="1-3 months">1-3 months</option>
                            <option value="3-6 months">3-6 months</option>
                            <option value="Planning Stage">Planning Stage</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Project Notes & Dimensions</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="e.g. 5m structural opening, flush threshold, Anthracite Grey RAL 7016."></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 14px;">
                    <button type="button" class="btn btn-dark" onclick="closeModal('addLeadModal')">Cancel</button>
                    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-bolt"></i> Run AI Score & Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: ADD QUOTATION -->
    <div id="addQuoteModal" class="modal">
        <div class="modal-content" style="max-width: 640px;">
            <h3 style="font-family: 'Cinzel', serif; color: var(--lux-gold); margin-bottom: 16px;"><i class="fa-solid fa-file-invoice"></i> Generate Client Quotation</h3>
            <form action="{{ route('crm.quotations.store') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Customer Name</label>
                        <input type="text" name="customer_name" class="form-control" required placeholder="Lord & Lady Hamilton">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Customer Email</label>
                        <input type="email" name="customer_email" class="form-control" placeholder="client@example.co.uk">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Project Title</label>
                    <input type="text" name="project_title" class="form-control" value="Cortizo Cor Vision Plus Sliding Doors Supply & Installation">
                </div>
                <div class="form-group">
                    <label class="form-label">Site Address</label>
                    <input type="text" name="site_address" class="form-control" placeholder="12 Eaton Square, Belgravia, London SW1W 9DA">
                </div>
                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Product / Specification</label>
                        <input type="text" name="product_name" class="form-control" value="Schüco ASS 70 FD Bifold Suite" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Qty</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Net Price (£)</label>
                        <input type="number" step="0.01" name="unit_price" class="form-control" value="8500.00" required>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Colour Finish</label>
                        <input type="text" name="color_finish" class="form-control" value="Anthracite Grey RAL 7016 Matt">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Glass Spec</label>
                        <input type="text" name="glass_specification" class="form-control" value="28mm Toughened Solar-Control Low-E Argon">
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 14px;">
                    <button type="button" class="btn btn-dark" onclick="closeModal('addQuoteModal')">Cancel</button>
                    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-check"></i> Create Quote (+20% UK VAT)</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: ADD B2B PROSPECT -->
    <div id="addB2bModal" class="modal">
        <div class="modal-content">
            <h3 style="font-family: 'Cinzel', serif; color: var(--lux-gold); margin-bottom: 16px;"><i class="fa-solid fa-building"></i> Add Trade Target Practice</h3>
            <form action="{{ route('crm.b2b.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Company / Practice Name</label>
                    <input type="text" name="company_name" class="form-control" required placeholder="e.g. Zaha Hadid Architects">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control">
                            <option value="Architect">Architectural Practice (RIBA)</option>
                            <option value="Builder">High-End Residential Builder</option>
                            <option value="Property Developer">Property Developer</option>
                            <option value="General Contractor">General Commercial Contractor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">City / Region</label>
                        <input type="text" name="city" class="form-control" placeholder="London / Surrey">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" placeholder="Head of Specifications">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="specifications@practice.co.uk">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Website URL</label>
                    <input type="url" name="website" class="form-control" placeholder="https://practice.co.uk">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 14px;">
                    <button type="button" class="btn btn-dark" onclick="closeModal('addB2bModal')">Cancel</button>
                    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-sparkles"></i> Save & Generate Pitch</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: CHANGE PASSWORD -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <h3 style="font-family: 'Cinzel', serif; color: var(--lux-gold); margin-bottom: 16px;">
                <i class="fa-solid fa-key" style="margin-right: 6px;"></i> Change Account Password
            </h3>
            <form action="{{ route('crm.change-password') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <div style="position: relative;">
                        <input type="password" name="current_password" id="curPass" class="form-control" required style="padding-right: 38px;">
                        <span onclick="toggleModalEye('curPass', 'curEye')" style="position: absolute; right: 12px; top: 10px; cursor: pointer; color: var(--lux-muted);">
                            <i class="fa-solid fa-eye" id="curEye"></i>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">New Password (Min 8 Characters)</label>
                    <div style="position: relative;">
                        <input type="password" name="new_password" id="newPass" class="form-control" required minlength="8" style="padding-right: 38px;">
                        <span onclick="toggleModalEye('newPass', 'newEye')" style="position: absolute; right: 12px; top: 10px; cursor: pointer; color: var(--lux-muted);">
                            <i class="fa-solid fa-eye" id="newEye"></i>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <div style="position: relative;">
                        <input type="password" name="new_password_confirmation" id="confPass" class="form-control" required minlength="8" style="padding-right: 38px;">
                        <span onclick="toggleModalEye('confPass', 'confEye')" style="position: absolute; right: 12px; top: 10px; cursor: pointer; color: var(--lux-muted);">
                            <i class="fa-solid fa-eye" id="confEye"></i>
                        </span>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn btn-dark" onclick="closeModal('passwordModal')">Cancel</button>
                    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-check"></i> Update Password</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: AI INSIGHTS / RESCORE -->
    <div id="aiInsightsModal" class="modal">
        <div class="modal-content">
            <h3 style="font-family: 'Cinzel', serif; color: var(--lux-gold); margin-bottom: 12px;" id="insightLeadName">AI Qualification Summary</h3>
            <div style="margin-bottom: 16px;">
                <label class="form-label">Viability Analysis</label>
                <div id="insightSummary" style="background: rgba(0,0,0,0.45); padding: 12px; border-radius: 6px; font-size: 13px; line-height: 1.5; color: #FFF;"></div>
            </div>
            <div style="margin-bottom: 20px;">
                <label class="form-label">Recommended Action</label>
                <div id="insightAction" style="background: rgba(229, 210, 165, 0.1); border: 1px solid var(--lux-border); padding: 12px; border-radius: 6px; font-size: 13px; color: var(--lux-gold);"></div>
            </div>
            <div style="display: flex; justify-content: flex-end;">
                <button type="button" class="btn btn-gold" onclick="closeModal('aiInsightsModal')">Close</button>
            </div>
        </div>
    </div>

    <!-- MODAL: PREVIEW PITCH -->
    <div id="pitchModal" class="modal">
        <div class="modal-content" style="max-width: 640px;">
            <h3 style="font-family: 'Cinzel', serif; color: var(--lux-gold); margin-bottom: 12px;" id="pitchCompanyTitle">Personalized B2B Trade Outreach</h3>
            <div class="form-group">
                <textarea id="pitchBody" class="form-control" rows="12" style="font-size: 12px; line-height: 1.5;" readonly></textarea>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button class="btn btn-dark" onclick="copyPitch()"><i class="fa-solid fa-copy"></i> Copy Text</button>
                <button class="btn btn-gold" onclick="closeModal('pitchModal')">Close</button>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebarNav').classList.toggle('mobile-open');
        }

        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
            
            const target = document.getElementById('tab-' + tabId);
            if (target) target.classList.add('active');

            if (event && event.currentTarget) {
                event.currentTarget.classList.add('active');
            }

            // Close mobile sidebar on tab switch if open
            document.getElementById('sidebarNav').classList.remove('mobile-open');
        }

        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        function toggleModalEye(inputId, eyeId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }

        function openRescoreModal(id, name, summary, action) {
            document.getElementById('insightLeadName').innerText = name + ' – AI Viability';
            document.getElementById('insightSummary').innerText = summary || 'Evaluated for architectural fit, budget tier and installation feasibility.';
            document.getElementById('insightAction').innerText = action || 'Priority follow up by Senior Glazing Consultant.';
            openModal('aiInsightsModal');
        }

        function viewPitch(company, pitch) {
            document.getElementById('pitchCompanyTitle').innerText = 'Personalized Pitch for ' + company;
            document.getElementById('pitchBody').value = pitch;
            openModal('pitchModal');
        }

        function copyPitch() {
            const textarea = document.getElementById('pitchBody');
            textarea.select();
            document.execCommand('copy');
            alert('Outreach pitch copied to clipboard!');
        }

        function generateAiEmail() {
            const type = document.getElementById('aiEmailType').value;
            const recipient_name = document.getElementById('aiRecipientName').value;
            const project_name = document.getElementById('aiProjectName').value;
            const custom_details = document.getElementById('aiCustomDetails').value;

            fetch('{{ route("api.ai.email") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ type, recipient_name, project_name, custom_details })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('generatedSubject').value = data.subject;
                    document.getElementById('generatedBody').value = data.body;
                }
            });
        }

        function copyEmail() {
            const subject = document.getElementById('generatedSubject').value;
            const body = document.getElementById('generatedBody').value;
            navigator.clipboard.writeText(subject + "\n\n" + body);
            alert('Email content copied to clipboard!');
        }
    </script>
</body>
</html>
