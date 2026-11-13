<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lux Glazing UK – Secure CRM Portal Login</title>
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
            padding: 20px;
            background-image: radial-gradient(circle at 50% 0%, rgba(229, 210, 165, 0.09) 0%, transparent 60%);
        }

        .login-card {
            width: 100%;
            max-width: 480px;
            background: var(--lux-card);
            border: 1px solid var(--lux-border);
            border-radius: 16px;
            padding: 40px 36px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7), 0 0 35px rgba(229, 210, 165, 0.08);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, transparent, var(--lux-gold), transparent);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .brand-logo {
            font-family: 'Cinzel', serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--lux-gold);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .brand-sub {
            font-size: 11px;
            letter-spacing: 1.5px;
            color: var(--lux-muted);
            text-transform: uppercase;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--lux-muted);
            margin-bottom: 8px;
        }
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            color: var(--lux-gold);
            font-size: 14px;
            pointer-events: none;
        }
        .form-control {
            width: 100%;
            background: rgba(11, 14, 20, 0.6);
            border: 1px solid var(--lux-border-light);
            color: #FFFFFF;
            padding: 12px 44px 12px 40px;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--lux-gold);
            box-shadow: 0 0 10px rgba(229, 210, 165, 0.2);
            background: rgba(11, 14, 20, 0.85);
        }
        .password-toggle {
            position: absolute;
            right: 14px;
            color: var(--lux-muted);
            cursor: pointer;
            font-size: 15px;
            transition: color 0.2s;
            padding: 4px;
        }
        .password-toggle:hover {
            color: var(--lux-gold);
        }

        .remember-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: var(--lux-muted);
            margin-bottom: 24px;
        }
        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        .remember-label input {
            accent-color: var(--lux-gold);
        }

        .btn-submit {
            width: 100%;
            background: var(--lux-gold);
            color: #0B0E14;
            border: none;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(229, 210, 165, 0.3);
        }
        .btn-submit:hover {
            background: var(--lux-gold-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(229, 210, 165, 0.4);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #FCA5A5;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6EE7B7;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Demo credentials card */
        .creds-container {
            margin-top: 26px;
            border-top: 1px solid var(--lux-border-light);
            padding-top: 20px;
        }
        .creds-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--lux-gold);
            font-weight: 700;
            margin-bottom: 12px;
            text-align: center;
        }
        .creds-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .creds-pill {
            background: rgba(11, 14, 20, 0.7);
            border: 1px solid var(--lux-border-light);
            border-radius: 8px;
            padding: 10px 12px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .creds-pill:hover {
            border-color: var(--lux-gold);
            background: rgba(229, 210, 165, 0.05);
        }
        .creds-role {
            font-size: 11px;
            font-weight: 700;
            color: var(--lux-gold);
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .creds-user {
            font-size: 11px;
            color: var(--lux-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .creds-pass {
            font-size: 10px;
            color: #6B7280;
            font-family: monospace;
            margin-top: 2px;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
        }
        .back-link a {
            color: var(--lux-muted);
            text-decoration: none;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .back-link a:hover {
            color: var(--lux-gold);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-header">
            <h1 class="brand-logo"><i class="fa-solid fa-gem" style="color: var(--lux-gold); font-size: 20px; margin-right: 6px;"></i>LUX GLAZING</h1>
            <p class="brand-sub">Enterprise CRM & Sales Portal</p>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Authorized Email Address</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input type="email" name="email" id="loginEmail" class="form-control" required placeholder="name@luxglazing.co.uk" value="{{ old('email') }}" autofocus>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Account Password</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" name="password" id="loginPassword" class="form-control" required placeholder="Enter your password">
                    <span class="password-toggle" onclick="togglePasswordVisibility()" title="Show/Hide Password">
                        <i class="fa-solid fa-eye" id="toggleEyeIcon"></i>
                    </span>
                </div>
            </div>

            <div class="remember-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    <span>Keep me signed in</span>
                </label>
                <span style="font-size: 11px; color: var(--lux-gold);"><i class="fa-solid fa-shield-halved"></i> 256-Bit SSL</span>
            </div>

            <button type="submit" class="btn-submit">
                <span>Sign In to CRM Portal</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <div class="creds-container">
            <div class="creds-title"><i class="fa-solid fa-key" style="margin-right: 4px;"></i> Quick-Fill Authorized Access</div>
            <div class="creds-grid">
                <div class="creds-pill" onclick="fillCreds('admin@luxglazing.co.uk', 'LuxGlazing@2026')">
                    <div class="creds-role"><i class="fa-solid fa-crown"></i> Super Admin</div>
                    <div class="creds-user">admin@luxglazing.co.uk</div>
                    <div class="creds-pass">Pass: LuxGlazing@2026</div>
                </div>

                <div class="creds-pill" onclick="fillCreds('oliver@luxglazing.co.uk', 'LuxGlazing@2026')">
                    <div class="creds-role"><i class="fa-solid fa-user-tie"></i> Sales Agent</div>
                    <div class="creds-user">oliver@luxglazing.co.uk</div>
                    <div class="creds-pass">Pass: LuxGlazing@2026</div>
                </div>
            </div>
        </div>

        <div class="back-link">
            <a href="{{ url('/') }}"><i class="fa-solid fa-arrow-left"></i> Return to Public Website</a>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passInput = document.getElementById('loginPassword');
            const eyeIcon = document.getElementById('toggleEyeIcon');

            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        function fillCreds(email, password) {
            document.getElementById('loginEmail').value = email;
            document.getElementById('loginPassword').value = password;
        }
    </script>
</body>
</html>
