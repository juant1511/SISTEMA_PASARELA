<?php
/* ============================================================
   🔥 ANTI-CACHE + VERSIONADOR PRO
============================================================ */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
include 'conexion.php';

/* Función para cache-busting real */
function ver($file) {
    $path = __DIR__ . '/' . $file;
    return file_exists($path) ? filemtime($path) : time();
}

/* ============================================================
   FUNCIONES AUXILIARES (estrellas)
============================================================ */
function renderStars($rating) {
    $full  = floor($rating);
    $half  = ($rating - $full) >= 0.5 ? 1 : 0;
    $empty = 5 - ($full + $half);

    return str_repeat("<span class='star full'></span>", $full)
         . str_repeat("<span class='star half'></span>", $half)
         . str_repeat("<span class='star empty'></span>", $empty);
}

/* ============================================================
   CONFIG TIENDA
============================================================ */
$config = $pdo->query("SELECT * FROM config_tienda WHERE id=1")->fetch(PDO::FETCH_ASSOC);
$logo_url = (!empty($config['logo'])) ? $config['logo'] : 'imagenes/logos.png';

/* ============================================================
   INICIALIZAR CARRITO
============================================================ */
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

/* ============================================================
   ORDENAMIENTO Y BÚSQUEDA
============================================================ */
$orden  = $_GET['orden']  ?? 'destacados';
$buscar = $_GET['buscar'] ?? '';

$where = "";
$orderBy = "";

if (!empty($buscar)) {
    $buscarSQL = "%$buscar%";
    $where = "WHERE p.nombre LIKE :buscar OR p.descripcion LIKE :buscar";
}

switch ($orden) {
    case 'precio_asc':  $orderBy = "ORDER BY p.precio ASC";  break;
    case 'precio_desc': $orderBy = "ORDER BY p.precio DESC"; break;
    case 'az':          $orderBy = "ORDER BY p.nombre ASC"; break;
    case 'za':          $orderBy = "ORDER BY p.nombre DESC"; break;
    case 'rating':      $orderBy = "ORDER BY estrellas DESC, calificaciones DESC"; break;
    case 'destacados':
    default:            $orderBy = "ORDER BY p.destacado DESC, p.id DESC";
}

/* ============================================================
   CONSULTA FINAL
============================================================ */
$query = "
    SELECT 
        p.*,
        COALESCE(ROUND(AVG(o.estrellas), 1), 0) AS estrellas,
        COUNT(o.id) AS calificaciones
    FROM productos p
    LEFT JOIN opiniones o ON p.id = o.producto_id
    $where
    GROUP BY p.id
    $orderBy
";

$stmt = $pdo->prepare($query);

if (!empty($buscar)) {
    $stmt->bindParam(':buscar', $buscarSQL);
}

$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  <?php $ver = time(); ?>

  <title>Todos los productos</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="css/principal.css?v=<?= ver('css/principal.css') ?>">
<link rel="stylesheet" href="css/productos.css?v=<?= ver('css/productos.css') ?>">
<link rel="stylesheet" href="css/root.css?v=<?= ver('css/root.css') ?>">

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

  <div class="nav-icons">
    <button id="btnBuscarMobile" class="icon-btn">
      <img src="imagenes/lupa.svg" alt="Buscar">
    </button>
  <a href="#" id="abrirCarrito" class="icon-btn">
    <img src="imagenes/carrito.svg" alt="Carrito">
    <span class="contador-carrito">
  <?= isset($_SESSION['carrito']) ? array_sum($_SESSION['carrito']) : 0 ?>
</span>
  </a>
  </div>
</nav>

<aside id="menuLateral" class="menu-lateral">
  <button id="cerrarMenu" class="cerrar-menu">✕</button>
  <a href="index.php">Inicio</a>
  <a href="productos.php">Todos los productos</a>
</aside>

<div id="modalBusquedaMobile">
  <div class="top-bar" id="cerrarModalBuscar">Volver</div>
  <div class="search-box">
    <input type="text" id="inputBuscarMobile" placeholder="Buscar productos...">
    <button id="btnSubmitBuscarMobile">
      <img src="imagenes/lupa.svg" alt="Buscar">
    </button>
  </div>
</div>
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
                      <?= '$' . number_format($prod['precio'], 0, ',', '.') . ' COP'; ?>
                    </span>
                    <span class="precio-descuento">
                      <?= '$' . number_format($precio_desc, 0, ',', '.') . ' COP'; ?>
                    </span>
                  </div>
                <?php else: ?>
                  <strong class="producto-precio">
                    <?= '$' . number_format($prod['precio'], 0, ',', '.') . ' COP'; ?>
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
<section class="productos-filtros">
  <div class="contenedor-filtros">

    <form method="GET" id="formFiltro" class="filtro-shopify">
      <select name="orden" onchange="document.getElementById('formFiltro').submit()">
        <option value="destacados"  <?= $orden==='destacados' ? 'selected':'' ?>>Destacados</option>
        <option value="precio_asc"  <?= $orden==='precio_asc' ? 'selected':'' ?>>Precio: menor a mayor</option>
        <option value="precio_desc" <?= $orden==='precio_desc' ? 'selected':'' ?>>Precio: mayor a menor</option>
        <option value="az"          <?= $orden==='az' ? 'selected':'' ?>>A → Z</option>
        <option value="za"          <?= $orden==='za' ? 'selected':'' ?>>Z → A</option>
        <option value="rating"      <?= $orden==='rating' ? 'selected':'' ?>>Mejor calificados</option>
      </select>

      <?php if (!empty($buscar)): ?>
        <input type="hidden" name="buscar" value="<?= htmlspecialchars($buscar); ?>">
      <?php endif; ?>
    </form>
  </div>
</section>


<section class="productos">
  <div class="productos-filtros">
    <div class="contenedor-filtros">
      <h2 class="titulo-productos">Todos los productos</h2>
      </div>
  </div>

  <?php 
  // 🏷️ Descuento global
  $promo_activa = true;
  $promo_porcentaje = 40;
  ?>

  <div class="productos-grid">
    <?php if ($productos): ?>
      <?php foreach ($productos as $p): ?>
        <div class="producto-card">
          
          <?php if ($promo_activa): ?>
            <div class="badge-descuento"><?= $promo_porcentaje ?>%</div>
          <?php endif; ?>

          <div class="producto-card-img">
            <a href="producto.php?id=<?= $p['id']; ?>">
              <img src="<?= htmlspecialchars($p['imagen_url']); ?>" alt="<?= htmlspecialchars($p['nombre']); ?>">
            </a>
          </div>

          <div class="producto-info">
            <h3><?= htmlspecialchars($p['nombre']); ?></h3>

            <div class="estrellas-php">
              <?= renderStars($p['estrellas']); ?>
              <span>(<?= $p['calificaciones']; ?>)</span>
            </div>

            <?php 
              $precio_original = $p['precio'];
              $precio_desc = $promo_activa 
                ? ($precio_original - ($precio_original * ($promo_porcentaje / 100)))
                : $precio_original;
            ?>

            <?php if ($promo_activa): ?>
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
                <?= '$' . number_format($precio_original, 0, ',', '.'); ?>
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
    <?php else: ?>
      <p>No se encontraron productos.</p>
    <?php endif; ?>
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
    <div class="footer-logo"><img src="<?= htmlspecialchars($logo_url); ?>"></div>
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
      <img src="imagenes/pse.webp">
      <img src="imagenes/contraentrega.png">
    </div>
  </div>

  <div class="footer-derechos">
    <p>Todos los derechos reservados © 2025</p>
  </div>
</footer>
<script src="js/principal.js?v=<?= $ver ?>"></script>
</body>
</html>