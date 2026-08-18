<?php
session_start();

$id = $_POST['id'] ?? null;
$accion = $_POST['accion'] ?? null;
$cantidadDirecta = $_POST['cantidad_directa'] ?? null;

if ($id !== null) {

    if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];

    // Si la cantidad viene escrita manualmente
    if ($cantidadDirecta !== null) {
        $cantidad = intval($cantidadDirecta);

        if ($cantidad <= 0) {
            unset($_SESSION['carrito'][$id]);
        } else {
            $_SESSION['carrito'][$id] = $cantidad;
        }
    }

    // Botón +
    elseif ($accion === 'sumar') {
        $_SESSION['carrito'][$id]++;
    }

    // Botón -
    elseif ($accion === 'restar') {
        if ($_SESSION['carrito'][$id] > 1) {
            $_SESSION['carrito'][$id]--;
        } else {
            unset($_SESSION['carrito'][$id]);
        }
    }

    // Quitar producto
    elseif ($accion === 'eliminar') {
        unset($_SESSION['carrito'][$id]);
    }

    // 👇 Calcular cantidad total para actualizar burbujita
    $totalItems = array_sum($_SESSION['carrito'] ?? []);

    echo json_encode([
        'success' => true,
        'items' => $totalItems // 👈 Esto es lo nuevo que necesitabas
    ]);
    exit;
}

echo json_encode(['success' => false]);
exit;
?>
