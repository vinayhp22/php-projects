<?php
// public/download_qr.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

if (!isset($_GET['id'])) {
    die("No link specified.");
}

$link_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM urls WHERE id = ? AND user_id = ?");
$stmt->execute([$link_id, $user_id]);
$link = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$link) {
    die("Link not found or you don't have permission to download its QR code.");
}

$short_code = $link['short_code'];

// Retrieve the server's IP address; if not available, fallback to '127.0.0.1'.
$server_ip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '127.0.0.1';
$short_link_ip = "http://{$server_ip}/linklynx/" . $short_code;

// Define the QR code file path for the IP-based URL.
$qr_file = __DIR__ . "/../vendor/phpqrcode/qrcodes/" . $short_code . "_ip.png";

// Generate the QR code if it doesn't exist.
if (!file_exists($qr_file)) {
    generateQRCode($short_link_ip, $qr_file);
}

if (file_exists($qr_file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($qr_file) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($qr_file));
    readfile($qr_file);
    exit();
} else {
    die("QR code file not found.");
}
?>
