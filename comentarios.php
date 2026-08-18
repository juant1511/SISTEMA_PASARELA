<?php
// =============================
// Procesamiento al enviar form
// =============================
$titulo = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$cantidad = $_POST['cantidad'] ?? '';
$generado = false;

if (!empty($titulo) && !empty($descripcion) && !empty($cantidad)) {
    $generado = true;

    // Construimos el PROMT final
    $prompt = "
Directrices de Estilo y Tono (MUY IMPORTANTE):

Perspectiva Personal: Cada comentario debe contar una pequeña historia. ¿Por qué compró el producto? ¿Qué problema quería solucionar? ¿Cómo fue su experiencia real? ¿Superó sus expectativas o hubo algo inesperado?

Lenguaje Natural y Coloquial: Usa un español típico colombiano, cercano y espontáneo. No temas incluir algunas expresiones propias del día a día (ej. \"la verdad que me encantó\", \"quedé matada con la calidad\", \"súper bacano\", \"es que esto me salvó\").

Detalles Específicos: Siempre menciona por lo menos un detalle concreto del producto en base a su descripción (ej. \"el color es más vivo que en las fotos\", \"la tela es súper suave y ligera\", \"armarlo fue más fácil de lo que imaginé\").

Emoción Genuina: Que se note que la emoción es sincera: alegría, alivio, sorpresa, satisfacción. Que se sienta que lo dice alguien que realmente usó el producto.

Variedad en la Estructura: No todos los comentarios deben ser largos o formales. Algunos pueden ser cortos y directos, otros más extensos y detallados. Uno o dos pueden mencionar un pequeñísimo detalle que no arruina la opinión general (ej. \"la caja llegó un poco golpeada, pero el producto intacto y funciona perfecto\").

Reglas estrictas para los NOMBRES:

Cada nombre debe ser un nombre colombiano real, acompañado de un solo apellido colombiano real.
No repetir nunca la misma combinación nombre + apellido.
No repetir apellidos más de una vez (a menos que la cantidad supere el número de apellidos disponibles).
Alternar entre nombres masculinos y femeninos.
No usar nombres inventados, ni nombres sin apellido, ni apellidos repetidos constantemente.

Formato de Salida (OBLIGATORIO):
Solo texto plano, columnas separadas por barras verticales, sin nada extra.
Nombre Apellido | Título | Comentario | Estrellas

Datos del Producto:
Título del producto: {$titulo}
Descripción: {$descripcion}

Requisitos de Generación:
Cantidad: {$cantidad} comentarios positivos.
Calificación: Entre 4.5 y 5 estrellas. Permitir decimales (4.5, 4.8, 5).

Ejemplo perfecto de comentario:
\"Mariana Suárez | ¡Me salvó el regalo de aniversario! | La verdad estaba buscando un detalle de última hora y vi esto. Me arriesgué y no puedo estar más feliz. La calidad es excelente, mucho mejor de lo que se ve en las fotos. Mi esposo quedó encantado. ¡Súper recomendado! | 5\"
";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Generador de Promt para Comentarios</title>
<style>
    body { font-family: Arial, sans-serif; padding: 30px; background: #f6f6f6; }
    .box { background: #fff; padding: 20px; border-radius: 10px; max-width: 700px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    textarea { width: 100%; height: 400px; margin-top: 20px; padding: 15px; font-size: 14px; }
    input, button { width: 100%; padding: 12px; margin-top: 15px; font-size: 16px; }
    .reset-btn { background:#d9534f; color:#fff; border:none; cursor:pointer; margin-top:10px; }
    .reset-btn:hover { background:#c9302c; }
    .copy-btn { background:#4CAF50; color:#fff; border:none; cursor:pointer; margin-top:10px; }
    .copy-btn:hover { background:#45a049; }
    .msg { margin-top: 10px; color: green; font-weight: bold; display:none; }
</style>
</head>
<body>

<div class="box">

<?php if (!$generado): ?>

    <h2>Generador Automático de Promt</h2>
    <p>Ingresa los datos y el sistema te genera el promt listo.</p>

    <form method="POST">
        <label><b>Título del producto:</b></label>
        <input type="text" name="titulo" required>

        <label><b>Descripción del producto:</b></label>
        <input type="text" name="descripcion" required>

        <label><b>Cantidad de comentarios:</b></label>
        <input type="number" name="cantidad" min="1" required>

        <button type="submit">Generar Promt</button>
    </form>

<?php else: ?>

    <h2>Promt Generado</h2>
    <textarea id="promptText" readonly><?= htmlspecialchars($prompt) ?></textarea>

    <!-- BOTÓN COPIAR -->
    <button class="copy-btn" onclick="copiarTexto()">Copiar promt</button>
    <div id="copiadoMsg" class="msg">¡Copiado al portapapeles!</div>

    <!-- BOTÓN RESET -->
    <form method="POST">
        <button class="reset-btn">Empezar de nuevo</button>
    </form>

<?php endif; ?>

</div>

<script>
function copiarTexto() {
    const textarea = document.getElementById("promptText");
    textarea.select();
    textarea.setSelectionRange(0, 999999); // Compatible móviles

    document.execCommand("copy");

    document.getElementById("copiadoMsg").style.display = "block";

    setTimeout(() => {
        document.getElementById("copiadoMsg").style.display = "none";
    }, 2000);
}
</script>

</body>
</html>
