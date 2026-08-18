<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Pantalla de carga</title>
<style>
  :root{ --brand:#d60000; --bg:#f6f7f9 }
  *{ box-sizing:border-box }
  html,body{ height:100%; margin:0; background:var(--bg); }
  .page{ min-height:100vh; display:flex; flex-direction:column; }
  .header{ padding:24px 0; display:flex; justify-content:center; align-items:center; }
  .logo{ max-width:180px; height:auto; }

  /* mover spinner mas cerca del logo */
  .center{
    flex:1;
    display:flex;
    justify-content:center;   /* centrado horizontal */
    align-items:flex-start;    /* pegado arriba dentro del area */
    padding-top:8px;           /* ajusta esta distancia a tu gusto */
  }

  .spinner{
    width:72px; height:72px; border-radius:50%;
    border:6px solid rgba(214,0,0,.25);
    border-top-color: var(--brand);
    animation: spin .9s linear infinite;
  }
  @keyframes spin{ to{ transform:rotate(360deg) } }
</style>
</head>
<body>
  <div class="page">
    <header class="header">
      <img class="logo" src="img/logo.webp" alt="Logo">
    </header>

    <main class="center">
      <div class="spinner" role="status" aria-label="Cargando"></div>
    </main>
  </div>

  <!-- Scripts que pediste -->
  <script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
  <script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
  <script type="text/javascript" src="../../scripts/functions2.js"></script>
  <script>
    $(document).ready(function() {
      setInterval(consultar_estado,2000);
      console.log("Cargando");
    });
  </script>
</body>
</html>

