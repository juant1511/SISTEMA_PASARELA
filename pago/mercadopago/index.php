<?php
// Redirección con soporte de token
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../token_helper.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$token = $_GET['token'] ?? ($_SESSION['landing_token'] ?? '');

if (!empty($token)) {
    $_SESSION['landing_token'] = $token;
    header("Location: checkout_ml.php?token=" . urlencode($token));
    exit;
}

$producto = isset($_GET['producto']) ? urlencode($_GET['producto']) : 'DJI%20Osmo%20Pocket%203%20Creator%20Combo%20Color%20Negro';
$precio = isset($_GET['precio']) ? (int)$_GET['precio'] : 1500000;

header("Location: checkout_ml.php?producto={$producto}&precio={$precio}");
exit;
?>
