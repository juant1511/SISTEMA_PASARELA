<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$q = trim($_GET['q'] ?? '');
if (empty($q)) {
    echo json_encode([]);
    exit;
}

$query_encoded = urlencode($q);
$url = "https://suggestqueries.google.com/complete/search?client=chrome&q=" . $query_encoded;

$suggestions = [];

if (function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response) {
        $data = json_decode($response, true);
        if (!empty($data[1]) && is_array($data[1])) {
            $suggestions = array_slice($data[1], 0, 7);
        }
    }
}

if (empty($suggestions)) {
    $ctx = stream_context_create([
        'http' => ['header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n", 'timeout' => 3],
        'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
    ]);
    $response = @file_get_contents($url, false, $ctx);
    if ($response) {
        $data = json_decode($response, true);
        if (!empty($data[1]) && is_array($data[1])) {
            $suggestions = array_slice($data[1], 0, 7);
        }
    }
}

if (!empty($suggestions)) {
    if (strtolower($suggestions[0]) !== strtolower($q)) {
        array_unshift($suggestions, $q);
        $suggestions = array_slice($suggestions, 0, 7);
    }
} else {
    $suggestions = [$q, "{$q} inalambrico", "{$q} pro", "{$q} gamer", "{$q} original", "{$q} oferta"];
}

echo json_encode(array_values(array_unique($suggestions)));
