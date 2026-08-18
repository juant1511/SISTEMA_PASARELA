<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Cargando...</title>
    <!-- Incluir fuentes de Google -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Montserrat', sans-serif;
        }
        .loader-gif {
            width: 150px;
            max-width: 80%;
        }
        .loading-text {
            margin-top: 25px;
            font-size: 18px;
            color: #333333;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

    <img src="../../bold/1.gif" alt="Cargando" class="loader-gif">
    <div class="loading-text">Cargando...</div>

    <script>
        setTimeout(function() {
            window.location.href = "./usuario.php";
        }, 3000);
    </script>

</body>
</html>
