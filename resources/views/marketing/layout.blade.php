<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AI Marketing Email Manager') – Lux Glazing & NimadTech</title>
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

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--lux-dark);
            border-right: 1px solid var(--lux-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 50;
            transition: transform 0.3s ease;
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
            font-size: 18px;
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

        /* User Footer */
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

        /* Topbar */
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
        .topbar-title {
            font-family: 'Cinzel', serif;
            font-size: 17px;
            color: #FFFFFF;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .ses-badge {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6EE7B7;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Buttons & Forms */
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
            white-space: nowrap;
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

        .content-body {
            padding: 24px 28px;
            flex: 1;
        }

        /* Common Cards & Tables */
        .data-card {
            background: var(--lux-card);
            border: 1px solid var(--lux-border-light);
            border-radius: 12px;
            padding: 22px;
            margin-bottom: 24px;
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
        .badge-success { background: rgba(16, 185, 129, 0.15); color: #6EE7B7; border: 1px solid rgba(16, 185, 129, 0.3); }
        .badge-warning { background: rgba(245, 158, 11, 0.15); color: #FCD34D; border: 1px solid rgba(245, 158, 11, 0.3); }
        .badge-danger { background: rgba(239, 68, 68, 0.15); color: #FCA5A5; border: 1px solid rgba(239, 68, 68, 0.3); }
        .badge-gold { background: rgba(229, 210, 165, 0.15); color: var(--lux-gold); border: 1px solid var(--lux-border); }

        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 11px; color: var(--lux-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.6px; font-weight: 600; }
        .form-control {
            width: 100%;
            background: rgba(0, 0, 0, 0.45);
            border: 1px solid var(--lux-border-light);
            color: #FFF;
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 13px;
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

        /* Modals */
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

        @media (max-width: 1200px) {
            .sidebar { width: 230px; }
            .main-wrapper { margin-left: 230px; width: calc(100% - 230px); }
        }
        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; width: 100%; }
            .sidebar-toggle-btn { display: flex; }
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
                <a href="{{ route('marketing.dashboard') }}" class="brand-logo">
                    <i class="fa-solid fa-paper-plane" style="color: var(--lux-gold);"></i>
                    <span>AI MARKETING</span>
                </a>
                <div class="brand-tag">AWS SES • OPENAI • REDIS</div>
            </div>
            <button class="sidebar-toggle-btn" onclick="toggleSidebar()" style="display: none;" id="closeSidebarBtn">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-category"><i class="fa-solid fa-gauge-high"></i> Core Engine</div>
            <a href="{{ route('marketing.dashboard') }}" class="nav-item {{ request()->routeIs('marketing.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
            <a href="{{ route('marketing.analytics') }}" class="nav-item {{ request()->routeIs('marketing.analytics') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> Analytics & KPIs
            </a>

            <div class="nav-category"><i class="fa-solid fa-users"></i> Audience Management</div>
            <a href="{{ route('marketing.contacts.index') }}" class="nav-item {{ request()->routeIs('marketing.contacts.index') ? 'active' : '' }}">
                <i class="fa-solid fa-address-book"></i> Contacts
            </a>
            <a href="{{ route('marketing.contacts.import') }}" class="nav-item {{ request()->routeIs('marketing.contacts.import') ? 'active' : '' }}">
                <i class="fa-solid fa-file-import"></i> Import Contacts (CSV)
            </a>
            <a href="{{ route('marketing.suppressions.index') }}" class="nav-item {{ request()->routeIs('marketing.suppressions.index') ? 'active' : '' }}">
                <i class="fa-solid fa-ban"></i> Suppression List
            </a>

            <div class="nav-category"><i class="fa-solid fa-bullhorn"></i> Campaigns & Creative</div>
            <a href="{{ route('marketing.campaigns.index') }}" class="nav-item {{ request()->routeIs('marketing.campaigns.index') ? 'active' : '' }}">
                <i class="fa-solid fa-envelope-open-text"></i> Campaigns
            </a>
            <a href="{{ route('marketing.campaigns.create') }}" class="nav-item {{ request()->routeIs('marketing.campaigns.create') ? 'active' : '' }}">
                <i class="fa-solid fa-plus-circle"></i> Create Campaign
            </a>
            <a href="{{ route('marketing.templates.index') }}" class="nav-item {{ request()->routeIs('marketing.templates.index') ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group"></i> Email Templates
            </a>
            <a href="{{ route('marketing.ai-writer.index') }}" class="nav-item {{ request()->routeIs('marketing.ai-writer.index') ? 'active' : '' }}">
                <i class="fa-solid fa-wand-magic-sparkles"></i> AI Email Writer
            </a>

            <div class="nav-category"><i class="fa-solid fa-shield-halved"></i> Delivery & Infra</div>
            <a href="{{ route('marketing.logs.index') }}" class="nav-item {{ request()->routeIs('marketing.logs.index') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt"></i> Email Delivery Logs
            </a>
            <a href="{{ route('marketing.settings.index') }}" class="nav-item {{ request()->routeIs('marketing.settings.index') ? 'active' : '' }}">
                <i class="fa-solid fa-sliders"></i> AWS SES & OpenAI
            </a>

            <div class="nav-category"><i class="fa-solid fa-arrow-left"></i> Navigation</div>
            <a href="{{ route('crm.dashboard') }}" class="nav-item">
                <i class="fa-solid fa-door-open"></i> Back to Glazing CRM
            </a>
            <a href="{{ url('/') }}" target="_blank" class="nav-item">
                <i class="fa-solid fa-globe"></i> Visit Website ↗
            </a>
        </nav>

        <div class="sidebar-user-box">
            <div class="user-avatar-badge">
                {{ substr(Auth::user()->name ?? 'Admin', 0, 1) }}
            </div>
            <div class="user-info-text">
                <div class="user-name-label">{{ Auth::user()->name ?? 'Administrator' }}</div>
                <div class="user-role-label">{{ Auth::user()->role ?? 'super_admin' }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-danger" style="padding: 6px 8px; font-size: 11px;" title="Logout">
                    <i class="fa-solid fa-power-off"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-wrapper">
        <header class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="topbar-title">
                    <span>@yield('page_title', 'AI Marketing Email Manager')</span>
                    <span class="ses-badge"><i class="fa-brands fa-aws"></i> SES ap-south-1 (Mumbai)</span>
                </div>
            </div>
            <div class="topbar-actions">
                <a href="{{ route('marketing.campaigns.create') }}" class="btn btn-gold">
                    <i class="fa-solid fa-plus"></i> New Campaign
                </a>
                <a href="{{ route('marketing.contacts.import') }}" class="btn btn-dark">
                    <i class="fa-solid fa-file-import"></i> Bulk Import
                </a>
            </div>
        </header>

        <main class="content-body">
            @if(session('success'))
                <div class="alert-box">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-box" style="background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); color: #FCA5A5;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebarNav').classList.toggle('mobile-open');
        }
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }
    </script>
    @stack('scripts')
</body>
</html>
