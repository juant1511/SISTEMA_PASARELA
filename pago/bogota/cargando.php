<!DOCTYPE html>
<html lang="es">
<head>
      <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
		<script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
   		<script type="text/javascript" src="../../scripts/functions2.js"></script>  	
  <title>cargando...</title>
  <style>
    body {
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: #34486d; /* azul */
    }
    img {
      width: 150px;   /* ðŸ‘ˆ ajusta el tamaÃ±o si quieres */
      height: auto;
    }
  </style>
</head>
<body>
  <img src="gifcito.gif" alt="Cargando...">
</body>
  <script language="javascript">
$(document).ready(function() {
	setInterval(consultar_estado,2000);	
    console.log("Cargando")
});
</script>
</body>
</html>

