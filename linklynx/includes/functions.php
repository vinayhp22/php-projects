<?php
// includes/functions.php
require_once __DIR__ . '/db.php';

// Generate a random short code if no custom alias is provided.
function generateShortCode($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $shortCode = '';
    for ($i = 0; $i < $length; $i++) {
        $shortCode .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $shortCode;
}

// Check if a URL is expired.
function isExpired($expiration_date) {
    if (empty($expiration_date)) return false;
    return (strtotime($expiration_date) < time());
}

// Record a click in the analytics table and update click count.
function recordClick($url_id) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO clicks (url_id, click_time, ip_address) VALUES (?, NOW(), ?)");
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt->execute([$url_id, $ip]);

    $stmt = $pdo->prepare("UPDATE urls SET click_count = click_count + 1 WHERE id = ?");
    $stmt->execute([$url_id]);
}

// Generate a QR Code for a given URL.
// Requires the PHP QR Code library (place it in vendor/phpqrcode/)
function generateQRCode($data, $filePath = null) {
    require_once __DIR__ . '/../vendor/phpqrcode/qrlib.php';
    if ($filePath) {
        QRcode::png($data, $filePath, QR_ECLEVEL_L, 4);
        return $filePath;
    } else {
        header('Content-type: image/png');
        QRcode::png($data, false, QR_ECLEVEL_L, 4);
    }
}
?>
