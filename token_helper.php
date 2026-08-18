<?php
/**
 * token_helper.php — Helper de resolución de tokens en SISTEMA_PASARELA
 */

if (!function_exists('obtenerLandingPorToken')) {
    function obtenerLandingPorToken($token, $pdo = null) {
        if (!$pdo) {
            global $pdo;
        }
        if (!$pdo || empty($token)) return false;

        $token_clean = trim($token);
        $stmt = $pdo->prepare("SELECT * FROM landings WHERE token = ?");
        $stmt->execute([$token_clean]);
        return $stmt->fetch();
    }
}
?>
