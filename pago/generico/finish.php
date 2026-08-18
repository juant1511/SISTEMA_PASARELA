<?php
session_start();
include '../../conexion.php';

/* ======================================
   CARGAR CONFIG TIENDA (logo + hero)
====================================== */
$config = $pdo->query("SELECT * FROM config_tienda WHERE id=1")->fetch(PDO::FETCH_ASSOC);

$logo_url = (!empty($config['logo'])) ? $config['logo'] : 'imagenes/logo.avif';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pedido Confirmado | Buffbunny</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../css/principal.css">
  <link rel="stylesheet" href="../../css/root.css">
  <link rel="stylesheet" href="finish.css">
</head>
<body>

<!-- 🔹 HEADER -->
<div class="top-header">
  <p>ENVIOS NACIONALES A TODO COLOMBIA</p>
</div>

<nav class="main-header">

  <!-- Botón menú móvil -->
  <button class="menu-movil" id="btnMenu">
    <img src="../../imagenes/menu.png" alt="menu">
  </button>

  <!-- Logo Tienda -->
  <a href="../../index.php" class="logo">
    <img src="../../<?= htmlspecialchars($logo_url); ?>" alt="Logo">
  </a>

  <!-- Buscador Escritorio -->
  <div class="search-box search-escritorio">
    <input type="text" placeholder="Buscar productos...">
    <button><img src="../../imagenes/lupa.svg"></button>
  </div>

  <!-- Enlaces Escritorio -->
  <ul class="nav-links">
    <li><a href="../../index.php">Inicio</a></li>
    <li><a href="../../productos.php">Todos los productos</a></li>
  </ul>

</nav>

<!-- Menú móvil -->
<aside id="menuLateral" class="menu-lateral">
  <div class="menu-content">
    <button id="cerrarMenu" class="cerrar-menu">✕</button>
    <a href="../../index.php">Inicio</a>
    <a href="../../productos.php">Todos los productos</a>
  </div>
</aside>


<!-- ======================== -->
<!-- 🛍️ PEDIDO REALIZADO -->
<!-- ======================== -->
<section class="pedido-realizado">
  <div class="pedido-container">

    <div class="pedido-check">
      <span>✔</span>
    </div>

    <h2>¡Gracias por tu compra!</h2>
    <p class="pedido-sub">Tu pedido se ha confirmado correctamente.</p>

    <div class="pedido-detalle">
      <div class="dato">
        <span>Número de pedido</span>
        <strong>#<?= rand(100000,999999); ?></strong>
      </div>
      <div class="dato">
        <span>Estado</span>
        <strong>Confirmado</strong>
      </div>
    </div>

    <a href="../../productos.php" class="btn-seguir">Seguir comprando</a>

  </div>
</section>



<!-- ======================== -->
<!-- 📩 SUSCRIPCIÓN -->
<!-- ======================== -->
<section class="suscripcion">
  <div class="suscripcion-container">

    <div class="suscripcion-texto">
      <h2>Suscríbete y recibe el 10% de descuento en tu siguiente compra.</h2>
      <p class="suscripcion-nota">*Aplican condiciones.</p>
    </div>

    <form class="suscripcion-form">
      <div class="suscripcion-input">
        <input type="email" placeholder="Tu correo...">
        <button type="button">➤</button>
      </div>

      <label class="suscripcion-check">
        <input type="checkbox">
        Acepto el <a href="#">tratamiento de datos</a>
      </label>

      <label class="suscripcion-check">
        <input type="checkbox">
        Acepto recibir ofertas comerciales
      </label>
    </form>

  </div>
</section>



<!-- ======================== -->
<!-- FOOTER -->
<!-- ======================== -->
<footer class="footer">
  <div class="footer-contenedor">

    <div class="footer-logo">
      <img src="../../<?= htmlspecialchars($logo_url); ?>">
    </div>

    <div class="footer-columnas">
      <div class="columna">
        <h3>Acerca de Nosotros</h3>
        <ul>
          <li><a href="#">Compra segura</a></li>
          <li><a href="#">Términos y condiciones</a></li>
          <li><a href="#">Formas de pago</a></li>
        </ul>
      </div>

      <div class="columna">
        <h3>Superintendencia de Industria y Comercio</h3>
        <div class="logos-sic">
          <img src="../../imagenes/super.png">
          <img src="../../imagenes/sic.png">
        </div>
      </div>

    </div>
  </div>

  <div class="footer-linea"></div>

  <div class="footer-pagos">
    <h4>Medios de pago</h4>
    <div class="pagos-logos">
      <img src="../../imagenes/maste.svg">
      <img src="../../imagenes/vista.svg">
      <img src="../../imagenes/pse.webp">
      <img src="../../imagenes/contraentrega.png">
    </div>
  </div>

  <div class="footer-derechos">
    <p>Todos los derechos reservados © 2025</p>
  </div>
</footer>

<script src="../../js/principal.js"></script>

</body>
</html>
