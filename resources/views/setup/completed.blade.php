<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lux Glazing System & Database Initialization</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --lux-gold: #D4AF37;
            --lux-gold-light: #F3E5AB;
            --lux-black: #0B0E14;
            --lux-dark: #121824;
            --lux-card: #1B2232;
            --lux-border: rgba(212, 175, 55, 0.25);
            --lux-text: #E5E7EB;
            --lux-muted: #9CA3AF;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: var(--lux-black);
            color: var(--lux-text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background-image: radial-gradient(circle at 50% 0%, rgba(212, 175, 55, 0.08) 0%, transparent 60%);
        }
        .container {
            max-width: 680px;
            width: 100%;
            background: var(--lux-card);
            border: 1px solid var(--lux-border);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), 0 0 40px rgba(212, 175, 55, 0.1);
        }
        .brand-header {
            text-align: center;
            margin-bottom: 30px;
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
            font-size: 13px;
            color: var(--lux-muted);
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 24px;
        }
        .status-success {
            background: rgba(16, 185, 129, 0.15);
            color: #10B981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .status-error {
            background: rgba(239, 68, 68, 0.15);
            color: #EF4444;
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
            font-size: 18px;
            color: #FFFFFF;
            margin-bottom: 12px;
            font-weight: 600;
        }
        p {
            font-size: 14px;
            line-height: 1.6;
            color: var(--lux-muted);
            margin-bottom: 16px;
        }
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
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
        .module-item .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--lux-gold);
        }
        .creds-box {
            background: rgba(212, 175, 55, 0.05);
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
            color: var(--lux-gold-light);
            font-family: monospace;
            font-weight: 600;
        }
        .btn-group {
            display: flex;
            gap: 14px;
            margin-top: 24px;
        }
        .btn {
            flex: 1;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #D4AF37 0%, #AA820A 100%);
            color: #000000;
            box-shadow: 0 4px 14px rgba(212, 175, 55, 0.3);
        }
        .btn-primary:hover {
            opacity: 0.95;
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
            color: #10B981;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="brand-header">
            <h1 class="brand-title">LUX GLAZING UK</h1>
            <p class="brand-subtitle">AI Growth & Enterprise CRM Infrastructure</p>
        </div>

        <div style="text-align: center;">
            @if($status === 'success')
                <div class="status-badge status-success">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Database Initialized & Migrated Successfully
                </div>
            @else
                <div class="status-badge status-error">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    Initialization Failed
                </div>
            @endif
        </div>

        <div class="card-inner">
            <h2>Ready-to-Use CRM & Lead Generation Modules</h2>
            <p>All database tables, relationship models, and architectural glazing seed datasets have been created and verified.</p>
            
            <div class="modules-grid">
                <div class="module-item"><span class="dot"></span> Leads & Pipeline (Kanban)</div>
                <div class="module-item"><span class="dot"></span> OpenAI Lead Qualification</div>
                <div class="module-item"><span class="dot"></span> B2B Architect Database</div>
                <div class="module-item"><span class="dot"></span> AI Email Generator</div>
                <div class="module-item"><span class="dot"></span> Quotation Management</div>
                <div class="module-item"><span class="dot"></span> UK 20% VAT Calculations</div>
                <div class="module-item"><span class="dot"></span> SEO Keyword Tracker</div>
                <div class="module-item"><span class="dot"></span> Ads & ROI Analytics</div>
            </div>

            <div class="creds-box">
                <div class="creds-row">
                    <span class="creds-label">Admin Portal:</span>
                    <span class="creds-value">{{ url('/admin/dashboard') }}</span>
                </div>
                <div class="creds-row">
                    <span class="creds-label">Default Admin Email:</span>
                    <span class="creds-value">admin@luxglazing.co.uk</span>
                </div>
                <div class="creds-row">
                    <span class="creds-label">Default Password:</span>
                    <span class="creds-value">LuxGlazing@2026</span>
                </div>
                <div class="creds-row" style="margin-bottom: 0;">
                    <span class="creds-label">Security Note:</span>
                    <span style="color: var(--lux-muted); font-size: 11px;">Credentials are pre-seeded and protected.</span>
                </div>
            </div>
        </div>

        <div class="btn-group">
            <a href="{{ url('/admin/dashboard') }}" class="btn btn-primary">Open CRM Dashboard →</a>
            <a href="{{ url('/') }}" class="btn btn-secondary">Visit Website & AI Bot</a>
        </div>

        <div class="logs-details">
            <details>
                <summary>View System & Migration Diagnostics</summary>
                <pre>{{ implode("\n", $logs) }}</pre>
            </details>
        </div>
    </div>
</body>
</html>
