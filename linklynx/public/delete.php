<?php
// public/delete.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

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
    die("Link not found or you don't have permission to delete it.");
}

$stmt = $pdo->prepare("DELETE FROM urls WHERE id = ? AND user_id = ?");
$stmt->execute([$link_id, $user_id]);

header("Location: manage.php");
exit();
?>
