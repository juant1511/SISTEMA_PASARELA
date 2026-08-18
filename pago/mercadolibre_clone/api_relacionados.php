<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$query = trim($_GET['q'] ?? $_GET['producto'] ?? 'camara dji');
$slug = trim($_GET['slug'] ?? '');

$fallback_data = [
    [
        'titulo' => 'Cámara Insta360 Luna Ultra Combo Mic Air Gris Oscuro',
        'img' => 'img_related/insta360_luna_combo.webp',
        'precio_original' => 4999900,
        'precio_final' => 3588903,
        'descuento' => 28,
        'cuotas' => '12 cuotas de $ 299.075 con 0% interés',
        'envio' => 'Envío gratis por ser tu primera compra',
        'link' => 'https://listado.mercadolibre.com.co/insta360'
    ],
    [
        'titulo' => 'Cámara Dji Osmo 360 Adventure Combo Color Negro',
        'img' => 'img_related/dji_osmo_adventure.webp',
        'precio_original' => 2800000,
        'precio_final' => 2200000,
        'descuento' => 21,
        'cuotas' => '12 cuotas de $ 183.333 con 0% interés',
        'envio' => 'Envío gratis por ser tu primera compra',
        'link' => 'https://listado.mercadolibre.com.co/dji-osmo'
    ],
    [
        'titulo' => 'Cámara Dji Osmo Action 6 Combo Estándar Incluye...',
        'img' => 'img_related/dji_action_cam.webp',
        'precio_original' => 2599900,
        'precio_final' => 1949900,
        'descuento' => 25,
        'cuotas' => '12 cuotas de $ 162.492 con 0% interés',
        'envio' => 'Envío gratis por ser tu primera compra',
        'link' => 'https://listado.mercadolibre.com.co/dji-action'
    ],
    [
        'titulo' => 'Micrófono Inalámbrico DJI Mic 2 Pack Dual Transmisor...',
        'img' => 'img_related/dji_mic_wireless.webp',
        'precio_original' => 1890000,
        'precio_final' => 1450000,
        'descuento' => 23,
        'cuotas' => '12 cuotas de $ 120.833 con 0% interés',
        'envio' => 'Envío gratis por ser tu primera compra',
        'link' => 'https://listado.mercadolibre.com.co/dji-mic'
    ]
];

if (empty($query)) {
    echo json_encode($fallback_data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Intentar consumir API de Mercado Libre Colombia (MCO)
$url = "https://api.mercadolibre.com/sites/MCO/search?q=" . urlencode($query) . "&limit=6";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT => 4,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
    CURLOPT_HTTPHEADER => [
        'Accept: application/json'
    ]
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200 && $response) {
    $data = json_decode($response, true);
    if (!empty($data['results'])) {
        $results = [];
        foreach (array_slice($data['results'], 0, 6) as $item) {
            $price = $item['price'] ?? 0;
            $original_price = $item['original_price'] ?? null;
            $discount = 0;
            
            if ($original_price && $original_price > $price) {
                $discount = round((($original_price - $price) / $original_price) * 100);
            } elseif ($price > 0) {
                $discount = rand(15, 30);
                $original_price = round($price / (1 - ($discount / 100)));
            }

            $cuota = $price > 0 ? round($price / 12) : 0;
            
            $img = $item['thumbnail'] ?? '';
            // Reemplazar http por https y versión de mayor resolución si viene de mlstatic
            $img = str_replace('http://', 'https://', $img);
            $img = str_replace('-I.jpg', '-O.webp', $img);
            $img = str_replace('-I.webp', '-O.webp', $img);

            $results[] = [
                'titulo' => $item['title'] ?? 'Producto relacionado',
                'img' => $img,
                'precio_original' => $original_price,
                'precio_final' => $price,
                'descuento' => $discount,
                'cuotas' => '12 cuotas de $ ' . number_format($cuota, 0, ',', '.') . ' con 0% interés',
                'envio' => 'Envío gratis por ser tu primera compra',
                'link' => $item['permalink'] ?? ('https://listado.mercadolibre.com.co/' . urlencode($query))
            ];
        }

        if (count($results) > 0) {
            echo json_encode($results, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}

echo json_encode($fallback_data, JSON_UNESCAPED_UNICODE);
