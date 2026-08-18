<?php
/* ============================================================
   🔥 ANTI-CACHE + VERSIONADOR PRO (VA ARRIBA DE TODO)
============================================================ */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
include 'conexion.php';

/* Función para filemtime() — evita JS/CSS viejos */
function ver($file) {
    $path = __DIR__ . '/' . $file;
    return file_exists($path) ? filemtime($path) : time();
}

/* ============================================================
   FUNCIÓN ESTRELLAS
============================================================ */
function renderStars($rating) {
    $full  = floor($rating);
    $half  = ($rating - $full) >= 0.5 ? 1 : 0;
    $empty = 5 - ($full + $half);

    return str_repeat("<span class='star full'></span>",  $full)
         . str_repeat("<span class='star half'></span>",  $half)
         . str_repeat("<span class='star empty'></span>", $empty);
}

/* ============================================================
   CONFIG TIENDA (logo)
============================================================ */
$config = $pdo->query("SELECT * FROM config_tienda WHERE id=1")->fetch(PDO::FETCH_ASSOC);
$logo_url = (!empty($config['logo'])) ? $config['logo'] : 'imagenes/logos.png';

/* ============================================================
   INICIALIZACIÓN DEL CARRITO
============================================================ */
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

/* ============================================================
   PRODUCTOS EN CARRITO
============================================================ */
$ids = array_keys($_SESSION['carrito']);
$carrito_productos = [];
$total_carrito = 0;

if (!empty($ids)) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $carrito_productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($carrito_productos as $prod) {
        $id       = $prod['id'];
        $cantidad = $_SESSION['carrito'][$id];
        $total_carrito += $prod['precio'] * $cantidad;
    }
}

/* ============================================================
   PRODUCTO
============================================================ */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    header('Location: index.php');
    exit;
}

/* GALERÍA DEL PRODUCTO */
$galeria = $pdo->prepare("SELECT * FROM producto_imagenes 
                          WHERE producto_id = ? ORDER BY id ASC");
$galeria->execute([$id]);
$galeria = $galeria->fetchAll(PDO::FETCH_ASSOC);

/* OPINIONES DEL PRODUCTO */
$opiniones = $pdo->prepare("
    SELECT * FROM opiniones 
    WHERE producto_id = ? 
    ORDER BY id DESC
");
$opiniones->execute([$id]);
$opiniones = $opiniones->fetchAll(PDO::FETCH_ASSOC);

/* ⭐ PROMEDIO DE ESTRELLAS */
$stats = $pdo->prepare("
    SELECT COUNT(*) AS total_calif,
           AVG(estrellas) AS promedio
    FROM opiniones
    WHERE producto_id = ?
");
$stats->execute([$id]);
$stats = $stats->fetch(PDO::FETCH_ASSOC);

$promedio        = $stats ? (float)$stats['promedio'] : 0;
$calificaciones  = $stats ? (int)$stats['total_calif'] : 0;

/* PRECIO Y DESCUENTO */
$promo_activa       = true;
$promo_porcentaje   = 40;
$precio_original    = $producto['precio'];
$precio_desc        = $promo_activa 
                      ? ($precio_original - ($precio_original * ($promo_porcentaje / 100)))
                      : $precio_original;

/* CUOTA */
$valor_cuota = floor($precio_desc / 36);
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

  <title><?= htmlspecialchars($producto['nombre']); ?></title>

  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="css/principal.css?v=<?= ver('css/principal.css') ?>">
<link rel="stylesheet" href="css/producto.css?v=<?= ver('css/producto.css') ?>">
<link rel="stylesheet" href="css/root.css?v=<?= ver('css/root.css') ?>">

  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- Meta Pixel Code -->
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
<!-- End Meta Pixel Code -->

  <script>
!function (w, d, t) {
  w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(
var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script")
;n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};
  ttq.load('D4NN02RC77UA1JCQ4NT0');
  ttq.page();
}(window, document, 'ttq');
</script>
</head>

<body>

<div class="top-header">
  <p>ENVIOS NACIONALES A TODO COLOMBIA</p>
</div>

<nav class="main-header">
  <button class="menu-movil" id="btnMenu"><img src="imagenes/menu.png" alt="menu"></button>
  <a href="index.php" class="logo"><img src="<?= htmlspecialchars($logo_url); ?>" alt="Logo"></a>

  <div class="search-box search-escritorio">
    <input type="text" id="inputBuscarDesktop" placeholder="Buscar productos...">
    <button id="btnSubmitBuscarDesktop"><img src="imagenes/lupa.svg" alt="Buscar"></button>
  </div>

  <ul class="nav-links">
    <li><a href="index.php">Inicio</a></li>
    <li><a href="productos.php">Todos los productos</a></li>
  </ul>

  <div class="nav-icons">
    <a href="#" id="abrirCarrito" class="icon-btn">
      <img src="imagenes/carrito.svg" alt="Carrito">
      <span class="contador-carrito"><?= isset($_SESSION['carrito']) ? array_sum($_SESSION['carrito']) : 0 ?></span>
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
    <button id="btnSubmitBuscarMobile"><img src="imagenes/lupa.svg" alt="Buscar"></button>
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
        $id_prod = $prod['id'];
        $cantidad = $_SESSION['carrito'][$id_prod];
        if ($promo_activa) {
          $p_orig = $prod['precio'];
          $p_desc = $p_orig - ($p_orig * ($promo_porcentaje / 100));
          $subtotal = $p_desc * $cantidad;
        } else {
          $p_desc = $prod['precio'];
          $subtotal = $p_desc * $cantidad;
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
                    <span class="precio-original"><?= '$' . number_format($prod['precio'], 0, ',', '.') . ' COP'; ?></span>
                    <span class="precio-descuento"><?= '$' . number_format($p_desc, 0, ',', '.') . ' COP'; ?></span>
                  </div>
                <?php else: ?>
                  <strong class="producto-precio"><?= '$' . number_format($prod['precio'], 0, ',', '.') . ' COP'; ?></strong>
                <?php endif; ?>
              </div>
              <small>CANTIDAD: <?= $cantidad; ?></small>
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
    <p class="nota-footer">
      IMPUESTO INCLUIDO. LOS <a href="#">GASTOS DE ENVÍO</a> SE CALCULAN EN LA PANTALLA DE PAGO.
    </p>
  </div>
</div>

<main class="ml-main-container">
    
    <div class="ml-header-mobile-only">
        <div class="header-row"><h1><?= htmlspecialchars($producto['nombre']); ?></h1></div>
        <div class="estrellas-block">
            <?= renderStars($promedio); ?>
            <span class="calif">(<?= $calificaciones; ?>)</span>
        </div>
    </div>
    
    <div class="ml-col-gallery">
        <div class="ml-gallery-wrapper">
            <div class="swiper miniSwiper ml-thumbs-desktop">
                <div class="swiper-wrapper">
                    <div class="swiper-slide mini-slide"><img src="<?= htmlspecialchars($producto['imagen_url']); ?>"></div>
                    <?php foreach ($galeria as $g): ?>
                    <div class="swiper-slide mini-slide"><img src="<?= htmlspecialchars($g['imagen_url']); ?>"></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="swiper mainSwiper ml-main-image">
                <div class="swiper-wrapper">
                    <div class="swiper-slide"><div class="zoom-container"><img src="<?= htmlspecialchars($producto['imagen_url']); ?>"></div></div>
                    <?php foreach ($galeria as $g): ?>
                    <div class="swiper-slide"><div class="zoom-container"><img src="<?= htmlspecialchars($g['imagen_url']); ?>"></div></div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>

    <div class="ml-col-center">
        
        <div class="ml-header-desktop">
            <h1><?= htmlspecialchars($producto['nombre']); ?></h1>
            <div class="estrellas-block">
                <?= renderStars($promedio); ?>
                <span class="calif">(<?= $calificaciones; ?> opiniones)</span>
            </div>
        </div>
        
        <div class="ml-section-specs">
             <h3>Lo que tienes que saber de este producto</h3>             
             <div class="descripcion-integrada">
                 <?= nl2br(htmlspecialchars($producto['descripcion'])); ?>
             </div>
        </div>
    </div>

 <aside class="ml-col-buybox">
        <div class="ml-card-buybox">
            
            <?php if ($promo_activa): ?>
                <div class="ml-price-container">
                     <span class="price-old"><?= '$' . number_format($precio_original, 0, ',', '.'); ?></span>
                     <div class="price-row">
                         <span class="price-main"><?= '$' . number_format($precio_desc, 0, ',', '.'); ?></span>
                         <span class="discount-tag"><?= $promo_porcentaje ?>% OFF</span>
                     </div>
                </div>
            <?php else: ?>
                <div class="ml-price-container">
                    <span class="price-main"><?= '$' . number_format($precio_original, 0, ',', '.'); ?></span>
                </div>
            <?php endif; ?>

            <div class="ml-installments">
                en 36x <span><?= '$ ' . number_format($precio_desc / 36, 0, ',', '.') ?></span>
            </div>

            <div class="ml-shipping-info">
                 <span class="green-txt">Llega gratis <strong>mañana</strong></span>
                 <span class="sub-txt">Comprando dentro de las próximas<span class="orange-count" id="countdown">--</span></span>
                 <a href="#" class="ml-link">Más detalles y formas de entrega</a>
            </div>

            <div class="stock-info-mattel">
                <strong>Stock disponible</strong>
                <div class="full-badge">
                    Almacenado y enviado por 
                    <span class="full-icon">
                        <svg viewBox="0 0 14 14" width="12" height="12"><path d="M11.5 5.5h-5l1.5-5.5h-5l-1.5 8h4l-1 5.5 7-8z" fill="#00a650"></path></svg>
                        <i>FULL</i>
                    </span>
                </div>
            </div>

            <div class="ml-actions">
                <button class="btn-ml-primary btn-agregar-carrito" data-id="<?= $producto['id']; ?>">Comprar ahora</button>
                <button class="btn-agregar-carrito btn-ml-secondary" data-id="<?= $producto['id']; ?>">
                    Agregar al carrito
                </button>
            </div>

            <div class="seller-official-box">
                <div class="seller-logo-container">
                    <img src="imagenes/logos.png" alt="Logo Tienda">
                </div>
                <div class="seller-info-text">
                    <p class="seller-title"><?= htmlspecialchars($config['nombre'] ?? 'Calendarios Adviento'); ?> <img src="imagenes/verificado.svg" class="check-icon" alt="✔"></p> 
                    <p class="seller-sales">+3mil ventas</p>
                </div>
            </div>

            <div class="ml-trust-badges">
                 <div class="badge-row">
                     <img src="imagenes/regreso.svg" class="badge-icon"> <p><a href="#">Devolución gratis</a>. Tienes 30 días desde que lo recibes.</p>
                 </div>
                 <div class="badge-row">
                     <img src="imagenes/proteger.svg" class="badge-icon"> <p><a href="#">Compra Protegida</a>. Recibe el producto que esperabas o te devolvemos tu dinero.</p>
                 </div>
            </div>

        </div>
    </aside>

</main>

<section class="opiniones-externas-container">
    <div class="opiniones-content-wrapper">
        <h3>Opiniones del producto</h3>
        
        <div class="ml-rating-summary">
             <div class="big-rating"><?= number_format($promedio, 1); ?></div>
             <div class="stars-static"><?= renderStars($promedio); ?></div>
             <div class="count-rating"><?= $calificaciones ?> calificaciones</div>
        </div>

        <div class="opiniones-lista-full">
            <?php if ($opiniones): ?>
                <?php foreach ($opiniones as $o): ?>
                    <div class="opinion-item-row review-item">
                        <div class="opinion-header">
                            <div class="estrellas-php"><?= renderStars($o['estrellas']); ?></div>
                        </div>
                        <p class="opinion-texto">
                            <strong><?= htmlspecialchars($o['titulo']); ?></strong><br>
                            <?= htmlspecialchars($o['comentario']); ?>
                        </p>
                        
                        <div class="opinion-footer-row">
                            <img src="<?= htmlspecialchars($o['imagen_cliente'] ?: 'imagenes/user_default.png'); ?>" class="opinion-avatar" alt="Foto">
                            
                            <span><?= htmlspecialchars($o['nombre_cliente']); ?></span>
                            <?php if ($o['verificado']): ?>
                                <span class="verificado">Compra verificada</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <button id="btnVerMasOpiniones" class="btn-ver-todas">Ver todas las opiniones</button>

            <?php else: ?>
                <p class="no-reviews">Este producto aún no tiene opiniones.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<div id="fullScreenLightbox" class="lightbox-overlay">
    <button class="btn-close-lightbox">&times;</button>
    <div class="swiper lightboxSwiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="swiper-zoom-container">
                    <img src="<?= htmlspecialchars($producto['imagen_url']); ?>">
                </div>
            </div>
            <?php foreach ($galeria as $g): ?>
            <div class="swiper-slide">
                <div class="swiper-zoom-container">
                    <img src="<?= htmlspecialchars($g['imagen_url']); ?>">
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
</div>

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
      <img src="imagenes/pse.webp">
      <img src="imagenes/contraentrega.png">
    </div>
  </div>

  <div class="footer-derechos">
    <p>Todos los derechos reservados © 2025</p>
  </div>
</footer>

<script src="js/principal.js?v=<?= $ver ?>"></script>
<script>
const miniSwiper = new Swiper(".miniSwiper", {
  spaceBetween: 10, slidesPerView: 5, direction: 'vertical', freeMode: true, watchSlidesProgress: true,
  breakpoints: { 0: { direction: 'horizontal', slidesPerView: 4 }, 768: { direction: 'vertical', slidesPerView: 6 } }
});
const mainSwiper = new Swiper(".mainSwiper", {
  spaceBetween: 0, loop: true,
  pagination: { el: ".swiper-pagination", clickable: true },
  thumbs: { swiper: miniSwiper }
});
const lightboxSwiper = new Swiper(".lightboxSwiper", {
  spaceBetween: 20, zoom: true, loop: true,
  navigation: { nextEl: ".lightboxSwiper .swiper-button-next", prevEl: ".lightboxSwiper .swiper-button-prev", },
  pagination: { el: ".lightboxSwiper .swiper-pagination", type: "fraction", },
});
const lightboxOverlay = document.getElementById('fullScreenLightbox');
const closeBtn = document.querySelector('.btn-close-lightbox');
document.querySelectorAll('.mainSwiper .swiper-slide img').forEach((img, index) => {
    img.addEventListener('click', () => {
        lightboxOverlay.style.display = 'flex';
        lightboxSwiper.slideToLoop(mainSwiper.realIndex); 
    });
});
closeBtn.addEventListener('click', () => { lightboxOverlay.style.display = 'none'; });

// ===========================================
// 🔥 LÓGICA DE VER MÁS COMENTARIOS 🔥
// ===========================================
document.addEventListener("DOMContentLoaded", function() {
    const reviews = document.querySelectorAll('.review-item');
    const btnLoadMore = document.getElementById('btnVerMasOpiniones');
    const limit = 5;

    // Si hay más de 5, ocultamos las sobrantes
    if(reviews.length > limit) {
        for(let i = limit; i < reviews.length; i++) {
            reviews[i].style.display = 'none';
        }
        // Aseguramos que el botón se vea
        if(btnLoadMore) btnLoadMore.style.display = 'block';
    } else {
        // Si hay 5 o menos, no necesitamos botón
        if(btnLoadMore) btnLoadMore.style.display = 'none';
    }

    // Al hacer clic, mostramos todas
    if(btnLoadMore) {
        btnLoadMore.addEventListener('click', function() {
            reviews.forEach(r => r.style.display = 'block');
            this.style.display = 'none'; // Ocultar el botón
        });
    }
});

function actualizarCountdown() {
    const ahora = new Date(); 
    const finDia = new Date(); 
    finDia.setHours(23, 59, 59, 999);
    let diff = finDia - ahora;
    if (diff <= 0) { diff = 24 * 60 * 60 * 1000; }
    const horas = Math.floor(diff / (1000 * 60 * 60));
    const minutos = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const h = horas.toString().padStart(2, '0');
    const m = minutos.toString().padStart(2, '0');
    document.getElementById("countdown").textContent = `${h} h ${m} min`;
}
setInterval(actualizarCountdown, 1000);
actualizarCountdown();
</script>
</body>
</html>