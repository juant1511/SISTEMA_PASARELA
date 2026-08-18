<?php
session_start();
include 'conexion.php';

// ============================================================
// 🏷️ DESCUENTO GLOBAL (editar aquí)
// ============================================================
$promo_activa = true;
$promo_porcentaje = 40;

// Inicializar si no existe carrito
if (!isset($_SESSION['carrito'])) {
  $_SESSION['carrito'] = [];
}

$ids = array_keys($_SESSION['carrito']);
$carrito_productos = [];
$total_carrito = 0;

if (count($ids) > 0) {
  $placeholders = implode(',', array_fill(0, count($ids), '?'));
  $stmt = $pdo->prepare("SELECT * FROM productos WHERE id IN ($placeholders)");
  $stmt->execute($ids);
  $carrito_productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($carrito_productos as $producto) {
    $id = $producto['id'];
    $cantidad = $_SESSION['carrito'][$id];

    if ($promo_activa) {
      $precio_original = $producto['precio'];
      $precio_desc = $precio_original - ($precio_original * ($promo_porcentaje / 100));
      $subtotal = $precio_desc * $cantidad;
    } else {
      $precio_desc = $producto['precio'];
      $subtotal = $precio_desc * $cantidad;
    }

    $total_carrito += $subtotal;
  }
}
?>

<!-- ======== CONTENIDO DEL CARRITO (sin body ni html) ======== -->
<div class="carrito-header">
  <span class="cerrar-carrito" id="cerrarCarrito">&times;</span>
  <div class="titulo-carrito">
    <img src="imagenes/carrito.svg" alt="Carrito">
    <span>CARRITO DE COMPRAS (<?= array_sum($_SESSION['carrito']); ?>)</span>
  </div>
</div>

<div class="carrito-contenido">
  <?php if (count($carrito_productos) > 0): ?>
    <?php foreach ($carrito_productos as $prod): ?>
      <?php 
        $cantidad = $_SESSION['carrito'][$prod['id']];
        $precio_original = $prod['precio'];
        $precio_desc = $promo_activa ? ($precio_original - ($precio_original * ($promo_porcentaje / 100))) : $precio_original;
      ?>
      <div class="producto" data-id="<?= $prod['id']; ?>">
        <div class="producto-wrap">
          <img src="<?= htmlspecialchars($prod['imagen_url']); ?>" alt="<?= htmlspecialchars($prod['nombre']); ?>">

          <div class="producto-detalle">
            
            <div class="info-superior">
              <h4><?= htmlspecialchars($prod['nombre']); ?></h4>

              <?php if ($promo_activa): ?>
              <div class="precio promo">
                <span class="precio-original">
                  <?= '$' . number_format($precio_original, 0, ',', '.') . ' COP'; ?>
                </span>
                <span class="precio-descuento">
                  <?= '$' . number_format($precio_desc, 0, ',', '.') . ' COP'; ?>
                </span>
              </div>
              <?php else: ?>
              <strong class="producto-precio">
                <?= '$' . number_format($precio_original, 0, ',', '.') . ' COP'; ?>
              </strong>
              <?php endif; ?>
            </div>

            <div class="acciones">
              <div class="contador" data-id="<?= $prod['id']; ?>">
                <button class="btn-disminuir">-</button>

                <input type="number" 
                  class="input-cantidad" 
                  data-id="<?= $prod['id']; ?>" 
                  value="<?= $cantidad; ?>" 
                  min="0" />

                <button class="btn-aumentar">+</button>
              </div>

              <div class="boton-eliminar" data-id="<?= $prod['id']; ?>">
                <svg class="icono-eliminar" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 17" width="14" height="14" fill="none" stroke="#999" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M2 4.5h12M3.5 4.5h9v10h-9v-10ZM6.5 7v5M9.5 7v5M5.5 4.5a2.5 2.5 0 1 1 5 0" />
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

<input type="hidden" id="total-items-header" value="<?= array_sum($_SESSION['carrito'] ?? []) ?>">
