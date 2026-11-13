<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lux Glazing UK – Luxury Architectural Windows & Doors</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --lux-gold: #D4AF37;
            --lux-gold-light: #F3E5AB;
            --lux-black: #0B0E14;
            --lux-dark: #121824;
            --lux-card: #182030;
            --lux-border: rgba(212, 175, 55, 0.25);
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

        /* Navigation */
        .navbar {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            background: rgba(11, 14, 20, 0.85);
            backdrop-filter: blur(12px);
            position: fixed;
            top: 0; width: 100%;
            z-index: 100;
            border-bottom: 1px solid var(--lux-border-light);
        }
        .brand-logo {
            font-family: 'Cinzel', serif;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--lux-gold);
            text-transform: uppercase;
            text-decoration: none;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
        }
        .nav-link {
            color: var(--lux-muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.2s;
        }
        .nav-link:hover { color: var(--lux-gold); }

        .btn {
            padding: 10px 22px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
        }
        .btn-gold {
            background: linear-gradient(135deg, #D4AF37 0%, #AA820A 100%);
            color: #000;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4); }
        .btn-outline {
            border: 1px solid var(--lux-border);
            color: var(--lux-gold);
            background: transparent;
        }
        .btn-outline:hover { background: rgba(212, 175, 55, 0.1); }

        /* Hero Section */
        .hero {
            min-height: 90vh;
            padding: 140px 5% 80px;
            display: flex;
            align-items: center;
            background: radial-gradient(circle at 75% 30%, rgba(212, 175, 55, 0.12) 0%, transparent 60%),
                        linear-gradient(to bottom, rgba(11, 14, 20, 0.6), #0B0E14),
                        url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
        }
        .hero-content {
            max-width: 720px;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 9999px;
            background: rgba(212, 175, 55, 0.12);
            border: 1px solid var(--lux-border);
            color: var(--lux-gold);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        .hero-title {
            font-family: 'Cinzel', serif;
            font-size: 48px;
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
            max-width: 600px;
        }
        .hero-badges {
            display: flex;
            gap: 24px;
            margin-top: 40px;
            border-top: 1px solid var(--lux-border-light);
            padding-top: 24px;
        }
        .hero-trust-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--lux-muted);
        }
        .hero-trust-item strong {
            color: #FFF;
        }

        /* Products Grid */
        .section {
            padding: 90px 5%;
        }
        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }
        .section-tag {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--lux-gold);
            font-weight: 700;
            margin-bottom: 8px;
        }
        .section-title {
            font-family: 'Cinzel', serif;
            font-size: 34px;
            color: #FFFFFF;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
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
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }
        .product-img {
            width: 100%;
            height: 240px;
            object-fit: cover;
        }
        .product-body {
            padding: 24px;
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
            font-size: 20px;
            color: #FFF;
            margin-bottom: 10px;
        }
        .product-desc {
            font-size: 13px;
            color: var(--lux-muted);
            margin-bottom: 16px;
            min-height: 40px;
        }
        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--lux-border-light);
            padding-top: 16px;
        }
        .product-price {
            font-weight: 700;
            color: var(--lux-gold-light);
            font-size: 14px;
        }

        /* Interactive Cost Estimator */
        .calculator-box {
            background: var(--lux-dark);
            border: 1px solid var(--lux-border);
            border-radius: 16px;
            padding: 48px;
            max-width: 900px;
            margin: 0 auto;
            box-shadow: 0 20px 50px rgba(0,0,0,0.6);
        }
        .calc-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-top: 24px;
        }
        .form-label {
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--lux-muted);
            margin-bottom: 8px;
        }
        .form-select, .form-input {
            width: 100%;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--lux-border-light);
            color: #FFF;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 14px;
        }
        .form-select:focus, .form-input:focus {
            outline: none;
            border-color: var(--lux-gold);
        }
        .calc-result-box {
            background: rgba(212, 175, 55, 0.08);
            border: 1px solid var(--lux-border);
            border-radius: 10px;
            padding: 24px;
            text-align: center;
            margin-top: 30px;
        }
        .calc-price {
            font-size: 36px;
            font-weight: 700;
            color: var(--lux-gold);
            font-family: 'Cinzel', serif;
            margin: 10px 0;
        }

        /* AI Floating Chatbot Widget */
        .chat-widget-btn {
            position: fixed;
            bottom: 28px;
            right: 28px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #D4AF37 0%, #AA820A 100%);
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            box-shadow: 0 8px 24px rgba(212, 175, 55, 0.4);
            cursor: pointer;
            z-index: 999;
            transition: transform 0.2s;
        }
        .chat-widget-btn:hover { transform: scale(1.08); }
        .chat-panel {
            display: none;
            position: fixed;
            bottom: 100px;
            right: 28px;
            width: 380px;
            height: 520px;
            background: var(--lux-dark);
            border: 1px solid var(--lux-border);
            border-radius: 14px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.8);
            flex-direction: column;
            z-index: 999;
            overflow: hidden;
        }
        .chat-panel.active { display: flex; }
        .chat-header {
            padding: 16px 20px;
            background: rgba(0, 0, 0, 0.4);
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
            max-width: 82%;
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
            color: #000;
            font-weight: 500;
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
            padding: 60px 5% 30px;
            font-size: 13px;
            color: var(--lux-muted);
        }
    </style>
</head>
<body>

    <!-- Top Navigation -->
    <header class="navbar">
        <a href="{{ url('/') }}" class="brand-logo">LUX GLAZING</a>
        <nav class="nav-links">
            <a href="#products" class="nav-link">Architectural Solutions</a>
            <a href="#estimator" class="nav-link">Cost Calculator</a>
            <a href="{{ route('setup.database') }}" class="nav-link" style="color: var(--lux-gold); font-size: 11px; text-transform: uppercase;">⚡ DB Init Route</a>
            <a href="{{ route('crm.dashboard') }}" class="btn btn-outline">CRM Portal ↗</a>
            <a href="#estimator" class="btn btn-gold">Get Instant Quote</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge">
                ★ Precision Architectural Glazing UK
            </div>
            <h1 class="hero-title">
                Elegance in Glass.<br>
                <span>Engineered for Luxury.</span>
            </h1>
            <p class="hero-desc">
                Specialists in ultra-slimline aluminium sliding doors, panoramic bifolds, heritage steel-look screens, and high-performance flush windows across the United Kingdom.
            </p>
            <div style="display: flex; gap: 16px;">
                <a href="#estimator" class="btn btn-gold">Instant Project Estimator →</a>
                <button onclick="toggleChat()" class="btn btn-outline">Speak with Glazing AI 💬</button>
            </div>

            <div class="hero-badges">
                <div class="hero-trust-item">
                    <span>🛡️</span>
                    <span><strong>10-Year</strong> Warranty</span>
                </div>
                <div class="hero-trust-item">
                    <span>📐</span>
                    <span><strong>FENSA</strong> Certified</span>
                </div>
                <div class="hero-trust-item">
                    <span>⚡</span>
                    <span><strong>Part L & Q</strong> Compliant</span>
                </div>
                <div class="hero-trust-item">
                    <span>📍</span>
                    <span><strong>Nationwide</strong> UK Surveys</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Showcase -->
    <section id="products" class="section">
        <div class="section-header">
            <div class="section-tag">Bespoke Architectural Engineering</div>
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
                    <div style="font-size: 11px; color: var(--lux-gold); margin-bottom: 12px;">{{ $p['specs'] }}</div>
                    <div class="product-footer">
                        <span class="product-price">{{ $p['starting_price'] }}</span>
                        <a href="#estimator" class="btn btn-outline" style="padding: 6px 12px; font-size: 11px;">Enquire Now</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Interactive Cost Estimator -->
    <section id="estimator" class="section" style="background: rgba(18, 24, 36, 0.4);">
        <div class="section-header">
            <div class="section-tag">Instant Pricing Tool</div>
            <h2 class="section-title">Architectural Glazing Estimator</h2>
            <p style="color: var(--lux-muted); max-width: 600px; margin: 12px auto 0;">Configure your project requirements to calculate an estimated supply and installation guide price.</p>
        </div>

        <div class="calculator-box">
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
                    <div style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: var(--lux-muted);">Estimated Project Guide Price (Inc. UK 20% VAT)</div>
                    <div class="calc-price" id="calcEstimatedPrice">£4,932</div>
                    <div style="font-size: 12px; color: var(--lux-gold);">*Subject to laser site survey and final RAL finish selection.</div>
                </div>

                <div style="margin-top: 30px; border-top: 1px solid var(--lux-border-light); padding-top: 24px;">
                    <h4 style="font-family: 'Cinzel', serif; color: #FFF; margin-bottom: 16px;">Request Formal Quotation & Laser Site Survey</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px;">
                        <input type="text" name="name" class="form-input" placeholder="Your Name" required>
                        <input type="text" name="phone" class="form-input" placeholder="Phone Number" required>
                        <input type="text" name="postcode" class="form-input" placeholder="UK Postcode" required>
                    </div>
                    <input type="hidden" name="estimated_budget" id="formBudget" value="£5,000 - £15,000">
                    <input type="hidden" name="timeframe" value="1-3 months">
                    <div style="text-align: center; margin-top: 20px;">
                        <button type="submit" class="btn btn-gold" style="padding: 14px 32px; font-size: 14px;">Submit for Instant Engineering Quote →</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Floating AI Chatbot Widget -->
    <div class="chat-widget-btn" onclick="toggleChat()">
        💬
    </div>

    <div class="chat-panel" id="chatPanel">
        <div class="chat-header">
            <div>
                <strong style="color: var(--lux-gold); font-size: 14px;">Lux Glazing AI Assistant</strong><br>
                <span style="font-size: 11px; color: var(--lux-muted);">OpenAI Powered Specification Guide</span>
            </div>
            <button onclick="toggleChat()" style="background: transparent; border: none; color: #FFF; font-size: 18px; cursor: pointer;">✕</button>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="chat-bubble chat-bot">
                Hello! Welcome to Lux Glazing UK. Are you planning an architectural glazing project (e.g. bifold doors, ultra-slim sliding glass, or heritage windows)? Tell me your project location or requirements!
            </div>
        </div>
        <div class="chat-input-bar">
            <input type="text" id="chatInput" class="form-input" style="padding: 8px 12px; font-size: 13px;" placeholder="Type your project enquiry..." onkeydown="if(event.key==='Enter') sendChatMessage()">
            <button class="btn btn-gold" style="padding: 8px 14px;" onclick="sendChatMessage()">Send</button>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--lux-border-light); padding-bottom: 30px; margin-bottom: 20px;">
            <div class="brand-logo">LUX GLAZING UK</div>
            <div style="display: flex; gap: 20px;">
                <a href="{{ route('setup.database') }}" style="color: var(--lux-gold); text-decoration: none;">Database Migration URL</a>
                <a href="{{ route('crm.dashboard') }}" style="color: var(--lux-muted); text-decoration: none;">Admin CRM</a>
                <a href="https://luxglazing.co.uk/" target="_blank" style="color: var(--lux-muted); text-decoration: none;">Live Site</a>
            </div>
        </div>
        <div style="text-align: center; font-size: 12px;">
            © 2026 Lux Glazing UK. All Rights Reserved. Engineered with Laravel & OpenAI. FENSA Registered.
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
