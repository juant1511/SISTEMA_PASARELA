<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Guardar todos los datos del formulario de checkout en la sesión
    $_SESSION['envio'] = [
        'correo' => $_POST['correo'] ?? '',
        'nombre' => trim(($_POST['nombre'] ?? '') . ' ' . ($_POST['apellidos'] ?? '')),
        'cedula' => $_POST['documento'] ?? '',
        'celular' => $_POST['telefono'] ?? '',
        'direccion' => $_POST['direccion'] ?? '',
        'ciudad' => $_POST['ciudad'] ?? '',
        'departamento' => $_POST['departamento'] ?? ''
    ];

    // Variables directas por compatibilidad con diferentes flujos
    $_SESSION['cliente_correo'] = $_POST['correo'] ?? '';
    $_SESSION['cliente_nombre'] = trim(($_POST['nombre'] ?? '') . ' ' . ($_POST['apellidos'] ?? ''));
    $_SESSION['cliente_documento'] = $_POST['documento'] ?? '';
    $_SESSION['cliente_telefono'] = $_POST['telefono'] ?? '';

    // Si se hace por AJAX
    if (isset($_POST['ajax_sesion'])) {
        echo json_encode(['status' => 'ok']);
        exit;
    }
}
