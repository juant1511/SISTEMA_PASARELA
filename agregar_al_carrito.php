<?php
session_start();
include 'conexion.php';

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Obtener el ID y cantidad
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;

if ($id > 0) {

    // Agregar o aumentar producto en el carrito
    if (!isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id] = $cantidad;
    } else {
        $_SESSION['carrito'][$id] += $cantidad;
    }

    // Obtener todos los productos del carrito actual
    $ids = array_keys($_SESSION['carrito']);
    
    if (count($ids) > 0) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $carrito_productos = [];
        $total_carrito = 0;

        foreach ($productos as $prod) {
            $pid = $prod['id'];
            $cantidadProducto = $_SESSION['carrito'][$pid];
            $total_carrito += $prod['precio'] * $cantidadProducto;

            $carrito_productos[] = [
                'id' => $pid,
                'nombre' => $prod['nombre'],
                'imagen_url' => $prod['imagen_url'],
                'precio' => $prod['precio'],
                'cantidad' => $cantidadProducto
            ];
        }

        // 👇 Calculamos el total de ítems para el contador del carrito
        $totalItems = array_sum($_SESSION['carrito']);

        echo json_encode([
            'success' => true,
            'carrito' => [
                'productos' => $carrito_productos,
                'total' => $total_carrito,
                'items' => $totalItems // 👈 Enviar total de la burbujita
            ]
        ]);
        exit;
    }
}

// Si algo falla
echo json_encode(['success' => false]);
exit;
?>
