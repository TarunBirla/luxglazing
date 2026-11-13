<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lux Glazing UK – Luxury Architectural Windows, Bifolds & Sliding Doors</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            --lux-card-hover: #1e283c;
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
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Top Navigation */
        .navbar {
            height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            background: rgba(11, 14, 20, 0.9);
            backdrop-filter: blur(12px);
            position: fixed;
            top: 0; width: 100%;
            z-index: 100;
            border-bottom: 1px solid var(--lux-border-light);
        }
        .brand-logo {
            font-family: 'Cinzel', serif;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 2.5px;
            color: var(--lux-gold);
            text-transform: uppercase;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .nav-link {
            color: var(--lux-muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .nav-link:hover { color: var(--lux-gold); }

        .btn {
            padding: 9px 20px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            white-space: nowrap;
        }
        .btn-gold {
            background: var(--lux-gold);
            color: #0B0E14;
            box-shadow: 0 4px 15px rgba(229, 210, 165, 0.3);
        }
        .btn-gold:hover {
            background: var(--lux-gold-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(229, 210, 165, 0.4);
        }
        .btn-outline {
            border: 1px solid var(--lux-border);
            color: var(--lux-gold);
            background: transparent;
        }
        .btn-outline:hover {
            background: rgba(229, 210, 165, 0.1);
            border-color: var(--lux-gold);
        }

        /* Hero Section */
        .hero {
            min-height: 90vh;
            padding: 130px 5% 70px;
            display: flex;
            align-items: center;
            background: radial-gradient(circle at 75% 30%, rgba(229, 210, 165, 0.12) 0%, transparent 60%),
                        linear-gradient(to bottom, rgba(11, 14, 20, 0.65), #0B0E14),
                        url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
        }
        .hero-content {
            max-width: 740px;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 9999px;
            background: rgba(229, 210, 165, 0.12);
            border: 1px solid var(--lux-border);
            color: var(--lux-gold);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        .hero-title {
            font-family: 'Cinzel', serif;
            font-size: clamp(32px, 5vw, 50px);
            font-weight: 700;
            line-height: 1.15;
            color: #FFFFFF;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }
        .hero-title span {
            color: var(--lux-gold);
        }
        .hero-desc {
            font-size: 16px;
            color: var(--lux-muted);
            margin-bottom: 32px;
            max-width: 620px;
        }
        .hero-buttons {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .hero-badges {
            display: flex;
            gap: 24px;
            margin-top: 36px;
            border-top: 1px solid var(--lux-border-light);
            padding-top: 24px;
            flex-wrap: wrap;
        }
        .hero-trust-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
            color: var(--lux-muted);
        }
        .hero-trust-item i {
            color: var(--lux-gold);
            font-size: 15px;
        }
        .hero-trust-item strong {
            color: #FFF;
        }

        /* Products Section */
        .section {
            padding: 80px 5%;
        }
        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }
        .section-tag {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--lux-gold);
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .section-title {
            font-family: 'Cinzel', serif;
            font-size: clamp(26px, 3.5vw, 36px);
            color: #FFFFFF;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 28px;
        }
        .product-card {
            background: var(--lux-card);
            border: 1px solid var(--lux-border-light);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .product-card:hover {
            border-color: var(--lux-border);
            transform: translateY(-4px);
            box-shadow: 0 16px 36px rgba(0,0,0,0.5);
        }
        .product-img {
            width: 100%;
            height: 230px;
            object-fit: cover;
        }
        .product-body {
            padding: 22px;
        }
        .product-category {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--lux-gold);
            font-weight: 600;
            margin-bottom: 6px;
        }
        .product-name {
            font-family: 'Cinzel', serif;
            font-size: 19px;
            color: #FFF;
            margin-bottom: 10px;
        }
        .product-desc {
            font-size: 13px;
            color: var(--lux-muted);
            margin-bottom: 14px;
            min-height: 40px;
        }
        .product-specs {
            font-size: 11px;
            color: var(--lux-gold);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--lux-border-light);
            padding-top: 14px;
        }
        .product-price {
            font-weight: 700;
            color: var(--lux-gold);
            font-size: 14px;
        }

        /* Interactive Cost Estimator */
        .calculator-box {
            background: var(--lux-dark);
            border: 1px solid var(--lux-border);
            border-radius: 16px;
            padding: 40px 36px;
            max-width: 880px;
            margin: 0 auto;
            box-shadow: 0 20px 50px rgba(0,0,0,0.6);
        }
        .calc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .form-label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--lux-muted);
            margin-bottom: 8px;
            font-weight: 600;
        }
        .form-select, .form-input {
            width: 100%;
            background: rgba(0, 0, 0, 0.45);
            border: 1px solid var(--lux-border-light);
            color: #FFF;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 13px;
            transition: border-color 0.2s;
        }
        .form-select:focus, .form-input:focus {
            outline: none;
            border-color: var(--lux-gold);
        }
        .calc-result-box {
            background: rgba(229, 210, 165, 0.08);
            border: 1px solid var(--lux-border);
            border-radius: 10px;
            padding: 22px;
            text-align: center;
            margin-top: 26px;
        }
        .calc-price {
            font-size: 36px;
            font-weight: 700;
            color: var(--lux-gold);
            font-family: 'Cinzel', serif;
            margin: 8px 0;
        }

        /* Floating AI Chatbot Widget */
        .chat-widget-btn {
            position: fixed;
            bottom: 26px;
            right: 26px;
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: var(--lux-gold);
            color: #0B0E14;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 8px 24px rgba(229, 210, 165, 0.4);
            cursor: pointer;
            z-index: 999;
            transition: transform 0.2s ease, background 0.2s;
        }
        .chat-widget-btn:hover {
            transform: scale(1.08);
            background: var(--lux-gold-hover);
        }
        .chat-panel {
            display: none;
            position: fixed;
            bottom: 96px;
            right: 26px;
            width: 360px;
            height: 500px;
            background: var(--lux-dark);
            border: 1px solid var(--lux-border);
            border-radius: 14px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.85);
            flex-direction: column;
            z-index: 999;
            overflow: hidden;
        }
        .chat-panel.active { display: flex; }
        .chat-header {
            padding: 14px 18px;
            background: rgba(0, 0, 0, 0.5);
            border-bottom: 1px solid var(--lux-border-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .chat-messages {
            flex: 1;
            padding: 16px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .chat-bubble {
            max-width: 84%;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 13px;
            line-height: 1.4;
        }
        .chat-bot {
            background: var(--lux-card);
            border: 1px solid var(--lux-border-light);
            color: var(--lux-text);
            align-self: flex-start;
        }
        .chat-user {
            background: var(--lux-gold);
            color: #0B0E14;
            font-weight: 600;
            align-self: flex-end;
        }
        .chat-input-bar {
            padding: 12px;
            border-top: 1px solid var(--lux-border-light);
            display: flex;
            gap: 8px;
        }

        /* Footer */
        footer {
            background: #07090D;
            border-top: 1px solid var(--lux-border-light);
            padding: 50px 5% 26px;
            font-size: 13px;
            color: var(--lux-muted);
        }
        .footer-grid {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--lux-border-light);
            padding-bottom: 26px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 16px;
        }

        /* Responsive Mobile / Laptop Adjustments */
        @media (max-width: 900px) {
            .nav-links { display: none; }
            .hero { padding-top: 110px; }
            .chat-panel { width: calc(100vw - 32px); right: 16px; bottom: 84px; }
            .calculator-box { padding: 24px 18px; }
        }
    </style>
</head>
<body>

    <!-- Top Navigation -->
    <header class="navbar">
        <a href="{{ url('/') }}" class="brand-logo">
            <i class="fa-solid fa-gem" style="color: var(--lux-gold);"></i>
            <span>LUX GLAZING</span>
        </a>
        <nav class="nav-links">
            <a href="#products" class="nav-link"><i class="fa-solid fa-layer-group"></i> Solutions</a>
            <a href="#estimator" class="nav-link"><i class="fa-solid fa-calculator"></i> Price Calculator</a>
            <a href="{{ route('setup.database') }}" class="nav-link" style="color: var(--lux-gold); font-size: 11px;">
                <i class="fa-solid fa-database"></i> DB SETUP ROUTE
            </a>
            @auth
                <a href="{{ route('crm.dashboard') }}" class="btn btn-outline">
                    <i class="fa-solid fa-gauge-high"></i> CRM Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline">
                    <i class="fa-solid fa-lock"></i> CRM Login
                </a>
            @endauth
            <a href="#estimator" class="btn btn-gold">
                <i class="fa-solid fa-paper-plane"></i> Instant Quote
            </a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fa-solid fa-award"></i> Precision Architectural Glazing UK
            </div>
            <h1 class="hero-title">
                Elegance in Glass.<br>
                <span>Engineered for Luxury.</span>
            </h1>
            <p class="hero-desc">
                Specialists in ultra-slimline aluminium sliding doors, panoramic bifolds, heritage steel-look screens, and high-performance flush windows across the United Kingdom.
            </p>
            <div class="hero-buttons">
                <a href="#estimator" class="btn btn-gold">
                    <i class="fa-solid fa-calculator"></i> Instant Project Estimator →
                </a>
                <button onclick="toggleChat()" class="btn btn-outline">
                    <i class="fa-solid fa-comments"></i> Speak with Glazing AI
                </button>
                @auth
                    <a href="{{ route('crm.dashboard') }}" class="btn btn-outline" style="border-color: var(--lux-gold);">
                        <i class="fa-solid fa-gauge"></i> Open CRM
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Staff Sign In
                    </a>
                @endauth
            </div>

            <div class="hero-badges">
                <div class="hero-trust-item">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span><strong>10-Year</strong> Insurance Backed Guarantee</span>
                </div>
                <div class="hero-trust-item">
                    <i class="fa-solid fa-certificate"></i>
                    <span><strong>FENSA</strong> Certified Installation</span>
                </div>
                <div class="hero-trust-item">
                    <i class="fa-solid fa-temperature-arrow-down"></i>
                    <span><strong>Part L & Q</strong> Building Regs Compliant</span>
                </div>
                <div class="hero-trust-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <span><strong>UK Nationwide</strong> Survey Coverage</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Showcase -->
    <section id="products" class="section">
        <div class="section-header">
            <div class="section-tag"><i class="fa-solid fa-sparkles"></i> Bespoke Architectural Engineering</div>
            <h2 class="section-title">Our Glazing Collection</h2>
        </div>

        <div class="products-grid">
            @foreach($products as $p)
            <div class="product-card">
                <img src="{{ $p['image'] }}" alt="{{ $p['name'] }}" class="product-img">
                <div class="product-body">
                    <div class="product-category">{{ $p['category'] }}</div>
                    <h3 class="product-name">{{ $p['name'] }}</h3>
                    <p class="product-desc">{{ $p['description'] }}</p>
                    <div class="product-specs"><i class="fa-solid fa-check-circle"></i> {{ $p['specs'] }}</div>
                    <div class="product-footer">
                        <span class="product-price">{{ $p['starting_price'] }}</span>
                        <a href="#estimator" class="btn btn-outline" style="padding: 5px 12px; font-size: 11px;">Enquire Now</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Interactive Cost Estimator -->
    <section id="estimator" class="section" style="background: rgba(18, 24, 36, 0.45);">
        <div class="section-header">
            <div class="section-tag"><i class="fa-solid fa-calculator"></i> Real-Time Pricing Estimator</div>
            <h2 class="section-title">Architectural Glazing Calculator</h2>
            <p style="color: var(--lux-muted); max-width: 600px; margin: 10px auto 0; font-size: 14px;">Configure opening width, glass specifications, and installation to calculate an immediate guide estimate.</p>
        </div>

        <div class="calculator-box">
            @if(session('success'))
                <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #6EE7B7; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('lead.enquiry') }}" method="POST" id="calculatorForm">
                @csrf
                <div class="calc-grid">
                    <div>
                        <label class="form-label">System Type</label>
                        <select name="service_type" id="calcService" class="form-select" onchange="updateEstimate()">
                            <option value="Bifold Doors" data-base="3400">Aluminium Bifold Doors (Schüco / Aluk)</option>
                            <option value="Sliding Doors" data-base="4800">Ultra-Slim Sliding Doors (Cortizo 20mm)</option>
                            <option value="Heritage/Steel Doors" data-base="2900">Heritage Steel-Look French Doors</option>
                            <option value="Aluminium Windows" data-base="1800">Aluminium Flush Casement Suite</option>
                            <option value="Rooflights" data-base="2100">Structural Glass Roof Lantern</option>
                            <option value="Commercial Glazing" data-base="7500">Commercial Storefront / Curtain Wall</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Opening Width (Metres)</label>
                        <select id="calcWidth" class="form-select" onchange="updateEstimate()">
                            <option value="1">2.4m - 3.0m (Standard 3-Panel)</option>
                            <option value="1.4">3.6m - 4.2m (4-Panel)</option>
                            <option value="1.8">4.8m - 5.5m (5-Panel)</option>
                            <option value="2.3">6.0m+ (Panoramic Grand Opening)</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Glass Specification</label>
                        <select id="calcGlass" class="form-select" onchange="updateEstimate()">
                            <option value="1">28mm Toughened Low-E Argon (A++ Rating)</option>
                            <option value="1.2">Acoustic Laminated & Solar Control</option>
                            <option value="1.35">Triple Glazed Ultra-Thermal (0.8 U-Value)</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Installation & Site Survey</label>
                        <select id="calcInstall" class="form-select" onchange="updateEstimate()">
                            <option value="850">Full Turnkey FENSA Installation Included</option>
                            <option value="0">Supply Only (Self-Installation)</option>
                        </select>
                    </div>
                </div>

                <div class="calc-result-box">
                    <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--lux-muted);">Estimated Project Guide Price (Inc. UK 20% VAT)</div>
                    <div class="calc-price" id="calcEstimatedPrice">£4,932</div>
                    <div style="font-size: 11px; color: var(--lux-gold);"><i class="fa-solid fa-circle-info"></i> Subject to final laser site survey and bespoke RAL finish confirmation.</div>
                </div>

                <div style="margin-top: 26px; border-top: 1px solid var(--lux-border-light); padding-top: 22px;">
                    <h4 style="font-family: 'Cinzel', serif; color: #FFF; margin-bottom: 14px;"><i class="fa-solid fa-ruler"></i> Request Formal Proposal & Site Survey</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                        <input type="text" name="name" class="form-input" placeholder="Your Name" required>
                        <input type="text" name="phone" class="form-input" placeholder="Phone (+44)" required>
                        <input type="email" name="email" class="form-input" placeholder="Email (For Quote Confirmation)">
                        <input type="text" name="postcode" class="form-input" placeholder="UK Postcode (e.g. SW3 4RY)" required>
                    </div>
                    <input type="hidden" name="estimated_budget" id="formBudget" value="£5,000 - £15,000">
                    <input type="hidden" name="timeframe" value="1-3 months">
                    <div style="text-align: center; margin-top: 20px;">
                        <button type="submit" class="btn btn-gold" style="padding: 12px 30px; font-size: 14px;">
                            <i class="fa-solid fa-paper-plane"></i> Submit for Consultation & Site Survey →
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Floating AI Chatbot Widget -->
    <div class="chat-widget-btn" onclick="toggleChat()" title="Open Glazing AI Assistant">
        <i class="fa-solid fa-comments"></i>
    </div>

    <div class="chat-panel" id="chatPanel">
        <div class="chat-header">
            <div>
                <strong style="color: var(--lux-gold); font-size: 14px;"><i class="fa-solid fa-robot"></i> Lux Glazing AI</strong><br>
                <span style="font-size: 10px; color: var(--lux-muted);">OpenAI Powered Technical Assistant</span>
            </div>
            <button onclick="toggleChat()" style="background: transparent; border: none; color: #FFF; font-size: 16px; cursor: pointer;">✕</button>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="chat-bubble chat-bot">
                Hello! Welcome to Lux Glazing UK. Are you planning an architectural glazing project (e.g. bifold doors, ultra-slim sliding glass, or heritage windows)? Tell me your project location or requirements!
            </div>
        </div>
        <div class="chat-input-bar">
            <input type="text" id="chatInput" class="form-input" style="padding: 8px 12px; font-size: 13px;" placeholder="Type your project enquiry..." onkeydown="if(event.key==='Enter') sendChatMessage()">
            <button class="btn btn-gold" style="padding: 8px 14px;" onclick="sendChatMessage()">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-grid">
            <div class="brand-logo">
                <i class="fa-solid fa-gem" style="color: var(--lux-gold);"></i>
                <span>LUX GLAZING UK</span>
            </div>
            <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                <a href="{{ route('setup.database') }}" style="color: var(--lux-gold); text-decoration: none;"><i class="fa-solid fa-database"></i> Database Migration URL</a>
                <a href="{{ route('login') }}" style="color: var(--lux-muted); text-decoration: none;"><i class="fa-solid fa-lock"></i> CRM Login</a>
                <a href="https://luxglazing.co.uk/" target="_blank" style="color: var(--lux-muted); text-decoration: none;"><i class="fa-solid fa-globe"></i> Live Website</a>
            </div>
        </div>
        <div style="text-align: center; font-size: 12px;">
            © 2026 Lux Glazing UK. All Rights Reserved. Architectural Glazing Specialists. FENSA Certified.
        </div>
    </footer>

    <script>
        function updateEstimate() {
            const service = document.getElementById('calcService');
            const base = parseFloat(service.options[service.selectedIndex].getAttribute('data-base'));
            const widthMultiplier = parseFloat(document.getElementById('calcWidth').value);
            const glassMultiplier = parseFloat(document.getElementById('calcGlass').value);
            const install = parseFloat(document.getElementById('calcInstall').value);

            const netTotal = (base * widthMultiplier * glassMultiplier) + install;
            const incVat = netTotal * 1.20; // 20% UK VAT

            const formatted = '£' + Math.round(incVat).toLocaleString();
            document.getElementById('calcEstimatedPrice').innerText = formatted;
            document.getElementById('formBudget').value = formatted;
        }

        function toggleChat() {
            const panel = document.getElementById('chatPanel');
            panel.classList.toggle('active');
        }

        function sendChatMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            if (!message) return;

            const messagesDiv = document.getElementById('chatMessages');

            // Append user bubble
            const userBubble = document.createElement('div');
            userBubble.className = 'chat-bubble chat-user';
            userBubble.innerText = message;
            messagesDiv.appendChild(userBubble);
            input.value = '';
            messagesDiv.scrollTop = messagesDiv.scrollHeight;

            fetch('{{ route("api.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            })
            .then(res => res.json())
            .then(data => {
                const botBubble = document.createElement('div');
                botBubble.className = 'chat-bubble chat-bot';
                botBubble.innerText = data.reply;
                messagesDiv.appendChild(botBubble);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            });
        }
    </script>
</body>
</html>
