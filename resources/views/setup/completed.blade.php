<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lux Glazing System & Database Initialization</title>
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
            --lux-black: #0B0E14;
            --lux-dark: #121824;
            --lux-card: #182030;
            --lux-border: rgba(229, 210, 165, 0.25);
            --lux-border-light: rgba(255, 255, 255, 0.08);
            --lux-text: #F3F4F6;
            --lux-muted: #9CA3AF;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background-color: var(--lux-black);
            color: var(--lux-text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background-image: radial-gradient(circle at 50% 0%, rgba(229, 210, 165, 0.08) 0%, transparent 60%);
        }
        .container {
            max-width: 660px;
            width: 100%;
            background: var(--lux-card);
            border: 1px solid var(--lux-border);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7), 0 0 35px rgba(229, 210, 165, 0.1);
        }
        .brand-header {
            text-align: center;
            margin-bottom: 26px;
        }
        .brand-title {
            font-family: 'Cinzel', serif;
            font-size: 26px;
            letter-spacing: 3px;
            color: var(--lux-gold);
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .brand-subtitle {
            font-size: 11px;
            color: var(--lux-muted);
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 22px;
        }
        .status-success {
            background: rgba(16, 185, 129, 0.15);
            color: #6EE7B7;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .status-error {
            background: rgba(239, 68, 68, 0.15);
            color: #FCA5A5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .card-inner {
            background: rgba(11, 14, 20, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }
        h2 {
            font-size: 17px;
            color: #FFFFFF;
            margin-bottom: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        p {
            font-size: 13px;
            line-height: 1.6;
            color: var(--lux-muted);
            margin-bottom: 16px;
        }
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 10px;
            margin: 16px 0;
        }
        .module-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--lux-text);
            background: rgba(255, 255, 255, 0.03);
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.04);
        }
        .module-item i {
            color: var(--lux-gold);
            font-size: 12px;
        }
        .creds-box {
            background: rgba(229, 210, 165, 0.06);
            border: 1px solid var(--lux-border);
            border-radius: 8px;
            padding: 16px;
            margin-top: 16px;
        }
        .creds-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 6px;
        }
        .creds-label {
            color: var(--lux-muted);
        }
        .creds-value {
            color: var(--lux-gold);
            font-family: monospace;
            font-weight: 600;
        }
        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }
        .btn {
            flex: 1;
            padding: 13px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-primary {
            background: var(--lux-gold);
            color: #0B0E14;
            box-shadow: 0 4px 15px rgba(229, 210, 165, 0.3);
        }
        .btn-primary:hover {
            background: var(--lux-gold-hover);
            transform: translateY(-1px);
        }
        .btn-secondary {
            background: transparent;
            color: var(--lux-text);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .btn-secondary:hover {
            border-color: var(--lux-gold);
            color: var(--lux-gold);
        }
        .logs-details {
            margin-top: 20px;
            font-size: 12px;
            color: var(--lux-muted);
        }
        summary {
            cursor: pointer;
            outline: none;
            color: var(--lux-gold);
            margin-bottom: 8px;
        }
        pre {
            background: #000;
            padding: 12px;
            border-radius: 6px;
            font-size: 11px;
            overflow-x: auto;
            color: #6EE7B7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="brand-header">
            <h1 class="brand-title"><i class="fa-solid fa-gem" style="color: var(--lux-gold); font-size: 22px;"></i> LUX GLAZING UK</h1>
            <p class="brand-subtitle">AI Growth & Enterprise CRM Infrastructure</p>
        </div>

        <div style="text-align: center;">
            @if($status === 'success')
                <div class="status-badge status-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Database Initialized & Migrated Successfully</span>
                </div>
            @else
                <div class="status-badge status-error">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Initialization Notice</span>
                </div>
            @endif
        </div>

        <div class="card-inner">
            <h2><i class="fa-solid fa-layer-group" style="color: var(--lux-gold);"></i> Ready-to-Use CRM Modules</h2>
            <p>All database tables, relational foreign keys, and architectural seed data have been initialized.</p>
            
            <div class="modules-grid">
                <div class="module-item"><i class="fa-solid fa-table-columns"></i> Leads & Pipeline (Kanban)</div>
                <div class="module-item"><i class="fa-solid fa-brain"></i> OpenAI Lead Qualification</div>
                <div class="module-item"><i class="fa-solid fa-building"></i> B2B Architect Database</div>
                <div class="module-item"><i class="fa-solid fa-envelope"></i> AI Email Generator</div>
                <div class="module-item"><i class="fa-solid fa-file-invoice"></i> Quotation Management (20% VAT)</div>
                <div class="module-item"><i class="fa-solid fa-chart-line"></i> SEO Keyword Tracker</div>
                <div class="module-item"><i class="fa-brands fa-google"></i> Google Ads & Meta Analytics</div>
                <div class="module-item"><i class="fa-solid fa-lock"></i> Protected Login Portal</div>
            </div>

            <div class="creds-box">
                <div class="creds-row">
                    <span class="creds-label">Login Screen:</span>
                    <span class="creds-value">{{ url('/login') }}</span>
                </div>
                <div class="creds-row">
                    <span class="creds-label">Super Admin Email:</span>
                    <span class="creds-value">admin@luxglazing.co.uk</span>
                </div>
                <div class="creds-row">
                    <span class="creds-label">Sales Agent Email:</span>
                    <span class="creds-value">oliver@luxglazing.co.uk</span>
                </div>
                <div class="creds-row">
                    <span class="creds-label">Default Password:</span>
                    <span class="creds-value">LuxGlazing@2026</span>
                </div>
            </div>
        </div>

        <div class="btn-group">
            <a href="{{ url('/login') }}" class="btn btn-primary"><i class="fa-solid fa-lock-open"></i> Go to Login Screen →</a>
            <a href="{{ url('/') }}" class="btn btn-secondary"><i class="fa-solid fa-globe"></i> View Website</a>
        </div>

        <div class="logs-details">
            <details>
                <summary><i class="fa-solid fa-terminal"></i> View System & Migration Diagnostics</summary>
                <pre>{{ implode("\n", $logs) }}</pre>
            </details>
        </div>
    </div>
</body>
</html>
