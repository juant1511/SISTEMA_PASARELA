<?php
// 🔥 CABECERAS PARA EVITAR CUALQUIER TIPO DE CACHE
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// 🚀 INICIAR SESIÓN
session_start();

// 📌 CONEXIÓN
include 'conexion.php';

// ⚡ Función para cache-busting real basada en modificación de archivo
function ver($file) {
    $path = __DIR__ . '/' . $file;
    return file_exists($path) ? filemtime($path) : time();
}

function renderStars($rating) {
    $full  = floor($rating);
    $half  = ($rating - $full) >= 0.5 ? 1 : 0;
    $empty = 5 - ($full + $half);

    return str_repeat("<span class='star full'></span>",  $full)
         . str_repeat("<span class='star half'></span>",  $half)
         . str_repeat("<span class='star empty'></span>", $empty);
}

/* ============================================================
   CONFIG TIENDA (logo + hero)
============================================================ */
$config = $pdo->query("SELECT * FROM config_tienda WHERE id=1")->fetch(PDO::FETCH_ASSOC);

$logo_url = (!empty($config['logo'])) ? $config['logo'] : 'imagenes/logos.png';
$hero_url = (!empty($config['hero'])) ? $config['hero'] : 'imagenes/hero.jpg';

/* ============================================================
   INICIALIZACIÓN DEL CARRITO
============================================================ */
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

/* ============================================================
   PRODUCTOS EN EL CARRITO
============================================================ */
$ids               = array_keys($_SESSION['carrito']);
$carrito_productos = [];
$total_carrito     = 0;

if (!empty($ids)) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt         = $pdo->prepare("SELECT * FROM productos WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $carrito_productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($carrito_productos as $producto) {
        $id       = $producto['id'];
        $cantidad = $_SESSION['carrito'][$id];
        $total_carrito += $producto['precio'] * $cantidad;
    }
}

/* ============================================================
   CONSULTAS PRINCIPALES
============================================================ */

/* ⭐ PRODUCTOS DESTACADOS */
$destacados = $pdo->query("
    SELECT p.*,
      COALESCE(AVG(o.estrellas), 0) AS promedio_estrellas,
      COUNT(o.id) AS total_calificaciones
    FROM productos p
    LEFT JOIN opiniones o ON o.producto_id = p.id
    WHERE p.destacado = 1
    GROUP BY p.id
    ORDER BY p.id DESC
    LIMIT 6
")->fetchAll(PDO::FETCH_ASSOC);

/* 💬 TODAS LAS OPINIONES */
$todas_opiniones = $pdo->query("
    SELECT * FROM opiniones
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);

/* ⭐ OPINIONES DESTACADAS PARA INDEX */
$opiniones_destacadas = $pdo->query("
    SELECT * FROM opiniones
    WHERE destacado_home = 1
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);

$opiniones_para_mostrar = $opiniones_destacadas;

/* ⭐ PROMEDIO GLOBAL DE ESTRELLAS */
$stats = $pdo->query("
    SELECT 
      COUNT(*) AS total_opiniones,
      AVG(estrellas) AS promedio
    FROM opiniones
")->fetch(PDO::FETCH_ASSOC);

$promedio_global    = $stats ? (float)$stats['promedio'] : 0;
$total_calif_global = $stats ? (int)$stats['total_opiniones'] : 0;

/* ============================================================
   DISTRIBUCIÓN DE ESTRELLAS
============================================================ */
$estrella_counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

foreach ($todas_opiniones as $o) {
    $e = round($o['estrellas']);
    if ($e < 1) $e = 1;
    if ($e > 5) $e = 5;
    $estrella_counts[$e]++;
}

$total_opiniones = array_sum($estrella_counts);

function pct($count, $total) {
    return $total > 0 ? round(($count / $total) * 100) : 0;
}

$porc_5 = pct($estrella_counts[5], $total_opiniones);
$porc_4 = pct($estrella_counts[4], $total_opiniones);
$porc_3 = pct($estrella_counts[3], $total_opiniones);
$porc_2 = pct($estrella_counts[2], $total_opiniones);
$porc_1 = pct($estrella_counts[1], $total_opiniones);

$promo_activa = true;
$promo_porcentaje = 40;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  <title>MUNDO GORRAS</title>

  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">

  <?php $ver = time(); ?>

  <link rel="stylesheet" href="css/principal.css?v=<?= $ver ?>">
  <link rel="stylesheet" href="css/root.css?v=<?= $ver ?>">

<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1373310711243525');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1373310711243525&ev=PageView&noscript=1"
/></noscript>

<style>
  #snow-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none; /* Importante: permite hacer clic a través de la nieve */
    z-index: 9999;
    overflow: hidden;
  }

  .snowflake {
    position: absolute;
    top: -10px;
    background-color: white;
    border-radius: 50%;
    opacity: 0.8;
    filter: blur(1px); /* Hace que se vea más natural */
    animation: fall linear infinite;
  }

  @keyframes fall {
    0% { transform: translateY(0) translateX(0); }
    100% { transform: translateY(110vh) translateX(50px); }
  }
</style>
</head>

<body>

<div class="top-header">
  <p>ENVIOS NACIONALES A TODO COLOMBIA</p>
</div>

<nav class="main-header">
  
  <button class="menu-movil" id="btnMenu">
    <img src="imagenes/menu.png" alt="menu">
  </button>

  <a href="index.php" class="logo">
    <img src="<?= htmlspecialchars($logo_url); ?>" alt="Logo">
  </a>

  <div class="search-box search-escritorio">
    <input type="text" id="inputBuscarDesktop" placeholder="Buscar productos...">
    <button id="btnSubmitBuscarDesktop">
      <img src="imagenes/lupa.svg" alt="Buscar">
    </button>
  </div>

  <ul class="nav-links">
    <li><a href="index.php">Inicio</a></li>
    <li><a href="productos.php">Todos los productos</a></li>
  </ul>

  <a href="#" id="abrirCarrito" class="icon-btn">
    <img src="imagenes/carrito.svg" alt="Carrito">
    <span class="contador-carrito">
  <?= isset($_SESSION['carrito']) ? array_sum($_SESSION['carrito']) : 0 ?>
</span>
  </a>

</nav>

<aside id="menuLateral" class="menu-lateral">
  <div class="menu-content">
    <button id="cerrarMenu" class="cerrar-menu">✕</button>
    <a href="index.php">Inicio</a>
    <a href="productos.php">Todos los productos</a>
  </div>
</aside>


<div id="carritoFlotante" class="carrito-flotante">
  <div class="carrito-header">
    <span class="cerrar-carrito" id="cerrarCarrito">&times;</span>

    <div class="titulo-carrito">
      <img src="imagenes/carrito.svg">
      <span>CARRITO DE COMPRAS (<?= array_sum($_SESSION['carrito']); ?>)</span>
    </div>
  </div>

  <div class="carrito-contenido">
    <?php if ($carrito_productos): ?>
      <?php 
      $total_carrito = 0; 
      foreach ($carrito_productos as $prod): 
        $id = $prod['id'];
        $cantidad = $_SESSION['carrito'][$id];

        if ($promo_activa) {
          $precio_original = $prod['precio'];
          $precio_desc = $precio_original - ($precio_original * ($promo_porcentaje / 100));
          $subtotal = $precio_desc * $cantidad;
        } else {
          $precio_desc = $prod['precio'];
          $subtotal = $precio_desc * $cantidad;
        }
        $total_carrito += $subtotal;
      ?>
        <div class="producto">
          <div class="producto-wrap">
            <img src="<?= htmlspecialchars($prod['imagen_url']); ?>">
            <div class="producto-detalle">

<div class="info-superior">
  <h4><?= htmlspecialchars($prod['nombre']); ?></h4>

  <?php if ($promo_activa): ?>
    <div class="precio promo">
      <span class="precio-original">
        <?= '$' . number_format($prod['precio'], 0, ',', '.'); ?>
      </span>
      <span class="precio-descuento">
        <?= '$' . number_format($precio_desc, 0, ',', '.'); ?>
      </span>
    </div>
  <?php else: ?>
    <strong class="producto-precio">
      <?= '$' . number_format($prod['precio'], 0, ',', '.'); ?>
    </strong>
  <?php endif; ?>
</div>

              <div class="acciones">
                <div class="contador">
                  <button class="btn-disminuir">-</button>
                  <span><?= $cantidad; ?></span>
                  <button class="btn-aumentar">+</button>
                </div>

                <div class="boton-eliminar">
                  <svg class="icono-eliminar" viewBox="0 0 16 17" width="14" height="14">
                    <path d="M2 4.5h12M3.5 4.5h9v10h-9v-10ZM6.5 7v5M9.5 7v5M5.5 4.5a2.5 2.5 0 1 1 5 0" stroke="#999" stroke-width="1.4" fill="none"/>
                  </svg>
                  <span>Quitar</span>
                </div>

              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Tu carrito está vacío.</p>
    <?php endif; ?>
  </div>

  <div class="carrito-footer">
    <div class="carrito-total">
      <span>TOTAL</span>
      <strong><?= '$' . number_format($total_carrito, 0, ',', '.') . ' COP'; ?></strong>
    </div>
    <button class="btn-finalizar">FINALIZAR COMPRA</button>
  </div>
</div>


<section class="hero-slider" id="heroSlider">
  <div class="hero-slides">

    <div class="hero-slide">
      <img src="imagenes/hero.png" class="hero-img">
    </div>

  </div>
</section>



<section class="productos">
  <h2 class="titulo-productos">Productos Destacados</h2>

  <?php 
  /* ⭐ DESCUENTO GLOBAL */
  $promo_activa = true;     // Cambia a false para quitar promo global
  $promo_porcentaje = 40;   // % de descuento global
  ?>

  <div class="productos-grid">
    <?php foreach ($destacados as $p): ?>
    <div class="producto-card">
      
      <?php if ($promo_activa): ?>
        <div class="badge-descuento"><?= $promo_porcentaje ?>%</div>
      <?php endif; ?>

      <div class="producto-card-img">
        <a href="producto.php?id=<?= $p['id']; ?>">
          <img src="<?= htmlspecialchars($p['imagen_url']); ?>">
        </a>
      </div>

      <div class="producto-info">
        <h3><?= htmlspecialchars($p['nombre']); ?></h3>

        <div class="estrellas-php">
          <?= renderStars($p['promedio_estrellas']); ?>
          <span>(<?= $p['total_calificaciones']; ?>)</span>
        </div>

<?php if ($promo_activa): ?>
  <?php 
    $precio_original = $p['precio'];
    $precio_desc = $precio_original - ($precio_original * ($promo_porcentaje / 100));
  ?>
  <div class="precio promo">
    <span class="precio-original">
      <?= '$' . number_format($precio_original, 0, ',', '.'); ?>
    </span>
    <span class="precio-descuento">
      <?= '$' . number_format($precio_desc, 0, ',', '.'); ?>
    </span>
  </div>
<?php else: ?>
  <p class="precio">
    <?= '$' . number_format($p['precio'], 0, ',', '.'); ?>
  </p>
<?php endif; ?>


<?php $precio_pixel_usd = round($p['precio'] * (isset($promo_activa) && $promo_activa ? (1 - $promo_porcentaje/100) : 1) / 4000, 2); ?>

<button class="btn-agregar-carrito" 
        data-id="<?= $p['id']; ?>"
        data-nombre="<?= htmlspecialchars($p['nombre']); ?>"
        data-precio="<?= $precio_pixel_usd; ?>">
   Agregar al carrito
</button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>




<section class="franja-sale-mod">
    <h2>Que te recuerden por la gorra que llevas puesta.</h2>
    <p class="sale-sub">Navidad es regalar estilo que se nota.</p>
    <a href="productos.php" class="btn-sale-mod">DESCUBRIR AHORA</a>
</section>


<section class="opiniones-layout">
    <div class="contenedor-opiniones">

      <div class="resumen-clientes">
        <h2>Opiniones de clientes</h2>

        <div class="estrellas-resumen">
            <span class="star full"></span>
            <span class="star full"></span>
            <span class="star full"></span>
            <span class="star full"></span>
            <span class="star half"></span>

            <span class="valor-promedio">
                <?= number_format($promedio_global, 1, ',', '.'); ?> de 5
            </span>
        </div>

        <p class="subtexto">
            <?= number_format($total_calif_global, 0, ',', '.'); ?> calificaciones nacionales
        </p>

        <div class="barras">
          <div class="fila">
              <span>5 estrellas</span>
              <div class="barra"><div class="relleno" style="width:<?= $porc_5 ?>%"></div></div>
              <span><?= $porc_5 ?>%</span>
          </div>
          <div class="fila">
              <span>4 estrellas</span>
              <div class="barra"><div class="relleno" style="width:<?= $porc_4 ?>%"></div></div>
              <span><?= $porc_4 ?>%</span>
          </div>
          <div class="fila">
              <span>3 estrellas</span>
              <div class="barra"><div class="relleno" style="width:<?= $porc_3 ?>%"></div></div>
              <span><?= $porc_3 ?>%</span>
          </div>
          <div class="fila">
              <span>2 estrellas</span>
              <div class="barra"><div class="relleno" style="width:<?= $porc_2 ?>%"></div></div>
              <span><?= $porc_2 ?>%</span>
          </div>
          <div class="fila">
              <span>1 estrella</span>
              <div class="barra"><div class="relleno" style="width:<?= $porc_1 ?>%"></div></div>
              <span><?= $porc_1 ?>%</span>
          </div>
        </div>

        <p class="basado">Basado en clientes verificados</p>
      </div>

      <div class="opiniones-imagenes">
        <h2>Opiniones con imágenes</h2>

        <div class="grid-opiniones">
          <?php foreach ($opiniones_para_mostrar as $o): ?>

            <?php
              // Link: si tiene producto_id va al producto, si no vuelve al index
              $linkOpinion = !empty($o['producto_id'])
                  ? 'producto.php?id=' . (int)$o['producto_id']
                  : 'index.php';
            ?>

            <a class="opinion"
               href="<?= $linkOpinion; ?>"
               style="text-decoration:none; color:inherit;">

              <div class="usuario">
                <img src="<?= htmlspecialchars($o['imagen_cliente'] ?: 'imagenes/user_default.png'); ?>" class="opinion-foto">
                <div>
                  <h4><?= htmlspecialchars($o['nombre_cliente']); ?></h4>
                  <div class="estrellas-php"><?= renderStars($o['estrellas']); ?></div>
                  <?php if ($o['verificado']): ?>
                    <span class="verificado">Compra verificada</span>
                  <?php endif; ?>
                </div>
              </div>

              <p class="opinion-titulo">“<?= htmlspecialchars($o['titulo']); ?>”</p>
              <p class="opinion-texto"><?= htmlspecialchars($o['comentario']); ?></p>

              <?php if (!empty($o['imagen_producto'])): ?>
              <div class="imagen-producto">
                <img src="<?= htmlspecialchars($o['imagen_producto']); ?>">
              </div>
              <?php endif; ?>

              <div class="acciones">
                <span>¿Te resultó útil?</span>
                <button>Útil</button>
              </div>

            </a>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
</section>

<section class="suscripcion">
    <div class="suscripcion-container">

        <div class="suscripcion-texto">
            <h2>Suscríbete y recibe el 10% de descuento en tu siguiente compra.</h2>
            <p class="suscripcion-nota">
                *Aplica en la próxima compra. No es acumulable con otras promociones. Exclusivo Online.
            </p>
        </div>

        <div class="suscripcion-form" id="formSuscripcion">
            <div class="suscripcion-input">
                <input type="email" id="correoSuscripcion">
                <button id="btnSuscribir" type="button">➤</button>
            </div>
            <p id="msg" class="msg"></p>

            <label class="suscripcion-check">
                <input type="checkbox" id="check1">
                He leído y acepto la autorización para 
                <a href="#">tratamiento de datos personales</a>.
            </label>

            <label class="suscripcion-check">
                <input type="checkbox" id="check2">
                He leído y aceptado la 
                <a href="#">autorización para uso de canales publicitarios</a>.
            </label>

        </div>

    </div>
</section>

<footer class="footer">
  <div class="footer-contenedor">

    <div class="footer-logo">
      <img src="<?= htmlspecialchars($logo_url); ?>">
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
          <img src="imagenes/super.png">
          <img src="imagenes/sic.png">
        </div>
      </div>

    </div>
  </div>

  <div class="footer-linea"></div>

  <div class="footer-pagos">
    <h4>Medios de pago</h4>
    <div class="pagos-logos">
      <img src="imagenes/maste.svg">
      <img src="imagenes/vista.svg">
      <img src="imagenes/pse.png">
      <img src="imagenes/contraentrega.png">
    </div>
  </div>

  <div class="footer-derechos">
    <p>Todos los derechos reservados © 2025</p>
  </div>
</footer>
<script src="js/principal.js?v=<?= $ver ?>"></script>

<div id="snow-container"></div>

<script>
  function createSnow() {
    const container = document.getElementById('snow-container');
    const count = 50; // Cantidad de copos (ajusta según prefieras)

    for (let i = 0; i < count; i++) {
      const snowflake = document.createElement('div');
      snowflake.className = 'snowflake';
      
      // Tamaño aleatorio entre 2px y 5px
      const size = Math.random() * 3 + 2;
      snowflake.style.width = `${size}px`;
      snowflake.style.height = `${size}px`;
      
      // Posición horizontal aleatoria
      snowflake.style.left = `${Math.random() * 100}%`;
      
      // Velocidad aleatoria (entre 5 y 15 segundos)
      const duration = Math.random() * 10 + 5;
      snowflake.style.animationDuration = `${duration}s`;
      
      // Retraso aleatorio para que no caigan todos al tiempo
      snowflake.style.animationDelay = `${Math.random() * 10}s`;
      
      // Opacidad aleatoria para dar profundidad
      snowflake.style.opacity = Math.random();

      container.appendChild(snowflake);
    }
  }

  // Ejecutar cuando la página cargue
  window.addEventListener('load', createSnow);
</script>
</body>
</html>