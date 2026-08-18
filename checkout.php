<?php
/* ============================================================
   🔥 CHECKOUT PRO - FUSIÓN: DISEÑO VIEJO + PIXEL NUEVO
   ============================================================ */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'conexion.php';
require_once __DIR__ . '/token_helper.php';

$promo_activa = true; 
$promo_porcentaje = 40;

$token = $_GET['token'] ?? ($_SESSION['landing_token'] ?? '');
$landing_data = null;
if (!empty($token)) {
    $landing_data = obtenerLandingPorToken($token, $pdo);
}

$is_token = !empty($landing_data);
$is_generic = $is_token || (isset($_GET['producto']) && isset($_GET['precio']));

if ($is_generic) {
    $promo_activa = false; // El precio de la landing es final
}

// 🛡️ BLINDAJE: SI EL CARRITO ESTÁ VACÍO Y NO HAY TOKEN NI GET, EXPULSAR AL HOME
if (empty($_SESSION['carrito']) && !$is_generic) {
    header("Location: index.php");
    exit;
}

$carrito_productos = [];
$total_bruto = 0;
$ids_pixel = []; 

if ($is_token) {
    $nombre = html_entity_decode($landing_data['producto'] ?? '', ENT_QUOTES, 'UTF-8');
    $precio = floatval($landing_data['precio']);
    $raw_imgs = $landing_data['imagenes'] ?? [];
    if (is_string($raw_imgs)) {
        $imagenes_db = json_decode($raw_imgs, true) ?: [];
    } else if (is_array($raw_imgs)) {
        $imagenes_db = $raw_imgs;
    } else {
        $imagenes_db = [];
    }
    $imagen_url = $imagenes_db['producto'] ?? ($imagenes_db['desktop'] ?? '');

    $prod_id = 'LANDING_' . $landing_data['id'];
    $carrito_productos[] = [
        'id' => $prod_id,
        'nombre' => $nombre,
        'precio' => $precio,
        'imagen_url' => $imagen_url
    ];
    $total_bruto = $precio;
    $_SESSION['carrito'] = [$prod_id => 1];
    $_SESSION['landing_token'] = $token;
    $_SESSION['landing_slug'] = $landing_data['slug'];
    $ids_pixel[] = $prod_id;
} elseif ($is_generic) {
    $nombre = html_entity_decode(trim($_GET['producto'] ?? ''), ENT_QUOTES, 'UTF-8');
    $precio = floatval($_GET['precio'] ?? 0);
    
    // Crear producto genérico
    $carrito_productos[] = [
        'id' => 'GENERIC_1',
        'nombre' => $nombre,
        'precio' => $precio,
        'imagen_url' => ''
    ];
    $total_bruto = $precio;
    $_SESSION['carrito'] = ['GENERIC_1' => 1];
    $ids_pixel[] = 'GENERIC_1';
}
 else {
    $ids = array_keys($_SESSION['carrito']);
    $ids_numericos = array_filter($ids, 'is_numeric');
    
    if (!empty($ids_numericos)) {
        $placeholders = implode(',', array_fill(0, count($ids_numericos), '?'));
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id IN ($placeholders)");
        $stmt->execute(array_values($ids_numericos));
        $carrito_productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($carrito_productos as $p) {
            $cantidad = $_SESSION['carrito'][$p['id']];
            $precio_final = $promo_activa ? ($p['precio'] * (1 - $promo_porcentaje/100)) : $p['precio'];
            $total_bruto += $precio_final * $cantidad;
            
            // Guardamos IDs como strings para evitar problemas de tipo en JS
            $ids_pixel[] = (string)$p['id'];
        }
    }
}

// 🔹 Lógica de Marca Dinámica para pasarela Bold
$nombre_comercio_bold = "Distribuidora Colombia SAS."; // Fallback genérico
if (!empty($carrito_productos)) {
    $nombre_producto = trim($carrito_productos[0]['nombre']);
    if (!empty($nombre_producto)) {
        // Extraer la primera palabra
        $partes = explode(' ', $nombre_producto);
        $primera_palabra = $partes[0];
        // Formato final: "Marca Districol S.A.S."
        $nombre_comercio_bold = ucfirst($primera_palabra) . " Districol S.A.S.";
    }
}
$_SESSION['bold_comercio'] = $nombre_comercio_bold;

$valor_seguro = 3000;
$total_contraentrega = max($total_bruto - $valor_seguro, 0);

// 🔹 CONVERSIÓN USD (Pixel reporta en USD)
$tasa_usd = 4000; 
$total_usd = ($total_bruto > 0) ? round($total_bruto / $tasa_usd, 2) : 0;
$num_items = !empty($_SESSION['carrito']) ? array_sum($_SESSION['carrito']) : 0;
$ids_js = json_encode($ids_pixel); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Checkout - Finaliza tu compra</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/checkout.css?v=<?= time() ?>">
  <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>

  <script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
try {
  fbq('init', '1373310711243525');
  fbq('track', 'PageView');

  // ✅ PIXEL: INITIATE CHECKOUT ESTÁNDAR (Carga de página)
  fbq('track', 'InitiateCheckout', {
      content_ids: <?= $ids_js ?>,
      content_type: 'product',
      value: <?= $total_usd ?>,
      currency: 'USD',
      num_items: <?= $num_items ?>
  });
} catch(e) { console.warn("Pixel bloqueado por cliente (Adblock)"); }
</script>
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1373310711243525&ev=PageView&noscript=1"/></noscript>
</head>

<body>

<div class="checkout-split">
<div class="checkout-left">
  <div class="checkout-inner">

    <div class="logo-box" style="margin-bottom: 20px;">
      <!-- Logo eliminado para hacer el checkout genérico y reusable -->
    </div>

    <h1 class="checkout-titulo">Finaliza tu compra</h1>

    <form id="checkoutForm">
      <div class="formulario">

          <h2>Contacto</h2>
          <input type="email" name="correo" placeholder="Correo electrónico" required maxlength="100">

          <h2>Datos de envío</h2>
          <select name="pais">
            <option>Colombia</option>
          </select>

          <div class="input-group">
            <select name="tipo_documento" required>
              <option value="CC">Cédula de ciudadanía</option>
              <option value="NIT">NIT</option>
              <option value="CE">Cédula de extranjería</option>
              <option value="PA">Pasaporte</option>
            </select>
            
            <input type="tel" name="documento" placeholder="Número de documento" required 
                   maxlength="12"
                   oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                   pattern="[0-9]*" title="Solo se permiten números">
          </div>

          <div class="input-group">
            <input type="text" name="nombre" placeholder="Nombre" required maxlength="50"
                   oninput="this.value = this.value.replace(/[^a-zA-ZñÑáéíóúÁÉÍÓÚ\s]/g, '')">
                   
            <input type="text" name="apellidos" placeholder="Apellidos" required maxlength="50"
                   oninput="this.value = this.value.replace(/[^a-zA-ZñÑáéíóúÁÉÍÓÚ\s]/g, '')">
          </div>

          <input type="text" name="direccion" placeholder="Dirección" required maxlength="100">
          <input type="text" name="complemento" placeholder="Casa, apartamento, etc. (opcional)" maxlength="50">

          <div class="input-group">
            <input type="text" name="ciudad" placeholder="Ciudad" required maxlength="50"
                   oninput="this.value = this.value.replace(/[^a-zA-ZñÑáéíóúÁÉÍÓÚ\s]/g, '')">
                   
            <select name="departamento" required>
              <option value="" disabled selected>Departamento</option>
              <option>Amazonas</option>
              <option>Antioquia</option>
              <option>Arauca</option>
              <option>Atlántico</option>
              <option>Bogotá, D.C.</option>
              <option>Bolívar</option>
              <option>Boyacá</option>
              <option>Caldas</option>
              <option>Caquetá</option>
              <option>Casanare</option>
              <option>Cauca</option>
              <option>Cesar</option>
              <option>Chocó</option>
              <option>Córdoba</option>
              <option>Cundinamarca</option>
              <option>Guainía</option>
              <option>Guaviare</option>
              <option>Huila</option>
              <option>La Guajira</option>
              <option>Magdalena</option>
              <option>Meta</option>
              <option>Nariño</option>
              <option>Norte de Santander</option>
              <option>Putumayo</option>
              <option>Quindío</option>
              <option>Risaralda</option>
              <option>San Andrés y Providencia</option>
              <option>Santander</option>
              <option>Sucre</option>
              <option>Tolima</option>
              <option>Valle del Cauca</option>
              <option>Vaupés</option>
              <option>Vichada</option>
            </select>
          </div>

          <input type="tel" name="telefono" placeholder="Teléfono" required
                 maxlength="10"
                 oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                 pattern="[0-9]*">

          <h2 class="titulo-envio">Métodos de envío</h2>
          <label class="envio-opcion">
            <input type="radio" name="metodo_envio" value="envio_nacional" checked>
            <div class="envio-card">
                <span class="envio-nombre">Envío Nacional</span>
                <span class="envio-precio">Gratis</span>
            </div>
          </label>

          <h2 style="margin-top:28px;">Método de pago</h2>

          <div class="metodos-pago">
            <label class="pago-opcion">
              <input type="radio" name="metodo_pago" id="pagoContra" value="contraentrega" checked>
              <div class="pago-card">
                <span id="textoMetodoContra">Pago Contraentrega</span>
              </div>
            </label>

            <label class="pago-opcion">
              <input type="radio" name="metodo_pago" id="pagoWompi" value="wompi">
              <div class="pago-card">
                <span>Bold / Tarjeta</span>
                <div class="pago-icons">
                  <img src="imagenes/vista.svg" class="logos-pago">
                  <img src="imagenes/maste.svg" class="logos-pago">
                  <img src="imagenes/ame.svg" class="logos-pago">
                </div>
              </div>
            </label>
          </div>

          <button type="button" class="btn-principal" id="btnContinuar">
            Continuar con el pago
          </button>
          
      </div>
    </form>

    <form id="formEnvioBold" action="bold.php" method="POST" style="display: none;">
        <input type="hidden" name="total" id="hiddenTotal">
        <input type="hidden" name="metodo" id="hiddenMetodo">
    </form>
  </div>
</div>

<div class="checkout-right">
  <div class="resumen" id="resumenReal">
    <?php foreach ($carrito_productos as $prod): 
      $cant = $_SESSION['carrito'][$prod['id']];
      $p_unit = $promo_activa ? ($prod['precio'] * (1 - $promo_porcentaje/100)) : $prod['precio'];
    ?>
      <div class="producto">
        <?php if (!$is_generic): ?>
        <div class="producto-img-wrap">
            <img src="<?= $prod['imagen_url'] ?>">
            <span class="producto-cantidad"><?= $cant ?></span>
        </div>
        <?php else: ?>
        <div class="producto-img-wrap" style="width: auto; background: transparent; border: none; padding-right: 15px;">
            <span class="producto-cantidad" style="position:relative; top:0; right:0; transform:none;"><?= $cant ?></span>
        </div>
        <?php endif; ?>
        
        <div class="producto-info">
            <strong><?= html_entity_decode($prod['nombre'], ENT_QUOTES, 'UTF-8') ?></strong>
        </div>
        <div class="producto-precios" style="text-align:right">
          <?php if ($promo_activa): ?>
          <span class="precio-original" style="text-decoration:line-through; opacity:.55; font-size:13px; margin-right:4px;">
            $<?= number_format($prod['precio'] * $cant, 0, ',', '.') ?> COP
          </span>
          <?php endif; ?>
          <span class="precio-descuento" style="color:#1e1e1f; font-size:18px; font-weight:800; display:block;">
            $<?= number_format($p_unit * $cant, 0, ',', '.') ?> COP
          </span>
          <?php if ($promo_activa): ?>
          <span style="display:inline-block; background:#ffffff; color:#1e1e1f; font-size:11px; font-weight:700; padding:2px 6px; border-radius:6px; margin-top:3px;">
            <?= $promo_porcentaje ?>%
          </span>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <div id="bloqueContraentrega" style="display:none; margin-top:12px;">
      <strong>Total a pagar contraentrega:</strong> $<span id="totalContraDisplay"><?= number_format($total_contraentrega, 0, ',', '.') ?></span>
    </div>

    <div id="itemSeguro" style="display:none; margin-top:18px;">
      <div class="producto">
        <div class="producto-img-wrap">
          <img src="imagenes/seguro.svg">
          <span class="producto-cantidad">1</span>
        </div>
        <div class="producto-info"><strong>Orden generada</strong></div>
        <div><strong>$<?= number_format($valor_seguro, 0, ',', '.') ?></strong></div>
      </div>
    </div>

    <div class="totales">
      <div><span>Subtotal</span><span id="subtotalDisplay"><?= '$' . number_format($total_bruto, 0, ',', '.') . ' COP'; ?></span></div>
      <div><span>Envío</span><span>Gratis</span></div>
      <div class="total-final">
        <span>Total</span>
        <span class="precio" id="totalDisplay"><small class="moneda">COP</small> <?= '$' . number_format($total_bruto, 0, ',', '.') . ' COP'; ?></span>
      </div>
    </div>
  </div>
</div>

<div class="modal-seguro" id="modalSeguro">
  <div class="modal-overlay" id="overlaySeguro"></div>
  <div class="modal-contenido nuevo-modal">
    <button class="modal-cerrar" id="btnCerrarModal">&times;</button>
    
    <!-- VISTA INICIAL: INFORMACIÓN Y GENERAR ORDEN -->
    <div class="modal-view-initial" id="modalViewInitial">
      <div class="modal-body">
        <div class="modal-icono" id="modalIconBox" style="display:flex; justify-content:center; align-items:center; min-height:125px; margin:4px 0 16px 0;">
          <dotlottie-player id="modalDotLottie" src="https://lottie.host/f0866ccb-1427-43b2-b07c-3850b36ce3f2/gqyfohuylF.lottie" background="transparent" speed="1" style="width: 125px; height: 125px;" loop autoplay></dotlottie-player>
        </div>
        <h3 class="modal-titulo">¡Tu orden está casi lista!</h3>
        <p class="modal-sub">
        La transportadora pide un anticipo de <span class="resaltar">$3.000</span> para generar la guía y despachar tu envío.
        </p>
        <ul class="modal-lista">
        <li><span class="texto-item"><span class="resaltar">No es un costo extra:</span> se descuenta del <span class="resaltar">valor</span> que pagas en casa.</span></li>
        </ul>
        <button class="btn-modal-mejorado" id="btnPagarSeguro">Generar Orden</button>
      </div>
    </div>

    <!-- VISTA DE ÉXITO: CHECK ANIMADO GRANDE -->
    <div class="modal-view-success" id="modalViewSuccess" style="display:none; opacity:0; transform:scale(0.9);">
      <div class="modal-success-inner" style="display:flex; flex-direction:column; align-items:center; justify-content:center; padding:15px 0;">
        <dotlottie-player id="modalCheckLottie" src="https://lottie.host/8285d9be-c1c7-4398-8c64-98535a6bc266/vr0dowJ04a.lottie" background="transparent" speed="1" style="width: 150px; height: 150px;" loop autoplay></dotlottie-player>
        <h3 class="modal-titulo" style="margin-top:14px; font-size:22px; color:#16a34a;">¡Orden Generada!</h3>
        <p class="modal-sub" style="margin-bottom:0;">Redirigiendo a la confirmación segura...</p>
      </div>
    </div>

  </div>
</div>

<script src="js/checkout.js?v=<?= time() ?>"></script>

<script>
// =======================================================
// 🔥 ENGINE DE TRACKING: OPTIMIZACIÓN DE INTENCIÓN (LEAD/INITIATE)
// =======================================================

const PIXEL_CONFIG = {
    value: <?= $total_usd ?>, 
    currency: 'USD',
    content_type: 'product',
    content_ids: <?= $ids_js ?>,
    num_items: <?= $num_items ?>
};
const TOTAL_COP_BRUTO = "<?= $total_bruto ?>";
const PIXEL_ID = '1373310711243525';

// ID Único para deduplicar eventos
const uniqueEventId = 'INT_' + new Date().getTime() + '_' + Math.floor(Math.random() * 9999);

let compraEnProceso = false;
let paymentInfoTracked = false;

// 🔧 Función de Pixel Manual (API Imagen - Respaldo)
function buildPixelUrl(pixelId, eventName, data, eventId) {
    const params = new URLSearchParams({
        id: pixelId,
        ev: eventName,
        noscript: 1,
        eid: eventId 
    });
    for (const [key, val] of Object.entries(data)) {
        if (key === 'user_data') continue; 
        if (Array.isArray(val)) {
            params.append(`cd[${key}]`, JSON.stringify(val));
        } else {
            params.append(`cd[${key}]`, val);
        }
    }
    return `https://www.facebook.com/tr/?${params.toString()}`;
}

// 🎯 ADD PAYMENT INFO (Se dispara al elegir método)
function dispararAddPaymentInfo() {
    if (!paymentInfoTracked && typeof fbq === 'function') {
        fbq('track', 'AddPaymentInfo', { 
            value: PIXEL_CONFIG.value, 
            currency: 'USD' 
        });
        paymentInfoTracked = true;
    }
}

document.querySelectorAll('input[name="metodo_pago"]').forEach(opcion => {
    opcion.addEventListener('change', dispararAddPaymentInfo);
});

// 🎯 FUNCIÓN PRINCIPAL DE CIERRE (RECUPERANDO DATOS DE SESIÓN)
window.finalizarCompra = function(metodo) {

    dispararAddPaymentInfo();

    // 🔴 1. VALIDACIÓN FORMULARIO
    const form = document.getElementById("checkoutForm");
    if (form && !form.reportValidity()) {
        return; 
    }

    if (compraEnProceso) return; 
    compraEnProceso = true;

    // UX: Mostrar loader
    document.getElementById('loaderSuperficialPro').classList.add('activo');
    
    // 🔥 2. GUARDAR SESIÓN (LA CLAVE PERDIDA) 
    // Esto asegura que Nombre, Teléfono, etc. lleguen al PHP antes de ir a Bold
    const fd = new FormData(form);
    fd.append("ajax_sesion", "1");
    fetch("guardar_sesion.php", { method: "POST", body: fd, keepalive: true }).catch(e => console.log(e));

    // 🚀 3. DISPARO DE PIXEL (INTENCIÓN DE COMPRA ALTA)
    try {
        const email = document.querySelector('input[name="correo"]').value.trim().toLowerCase();
        const phone = document.querySelector('input[name="telefono"]').value.trim().replace(/[^0-9]/g, '');
        const fn    = document.querySelector('input[name="nombre"]').value.trim().toLowerCase();
        const ln    = document.querySelector('input[name="apellidos"]').value.trim().toLowerCase();

        const enhancedConfig = {
            ...PIXEL_CONFIG,
            user_data: { em: email, ph: phone, fn: fn, ln: ln }
        };

        // A. Vía Estándar
        if (typeof fbq === 'function') {
            fbq('track', 'InitiateCheckout', enhancedConfig, { eventID: uniqueEventId });
        }
        // B. Vía Inmortal
        const pixelUrl = buildPixelUrl(PIXEL_ID, 'InitiateCheckout', enhancedConfig, uniqueEventId);
        fetch(pixelUrl, { method: 'GET', keepalive: true, mode: 'no-cors' }).catch(() => {});

    } catch (e) {
        console.warn("Pixel warning, continuando...", e);
    }

    // 4️⃣ PREPARAR Y ENVIAR A BOLD.PHP
    let totalEnviar = TOTAL_COP_BRUTO;
    if (metodo === 'seguro') { totalEnviar = 3000; }

    const formEnvio = document.getElementById("formEnvioBold");
    document.getElementById("hiddenTotal").value = totalEnviar;
    document.getElementById("hiddenMetodo").value = metodo;
    
    // Esperamos 3 segundos para asegurar consistencia del form:
    // 1. Se guarde la sesión en PHP
    // 2. El Pixel salga de viaje
    setTimeout(() => { 
        formEnvio.submit(); 
    }, 3000); 
};
</script>

<div id="loaderSuperficialPro">
    <div class="spinner-pro"></div>
    <br>
    <span style="font-family: 'Manrope', sans-serif; font-weight:600; color:#333;">Procesando pedido...</span>
</div>

</body>
</html>