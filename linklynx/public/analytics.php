<?php
// public/analytics.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_GET['c'])) {
    die("No short code provided.");
}

$short_code = $_GET['c'];

$stmt = $pdo->prepare("SELECT * FROM urls WHERE short_code = ?");
$stmt->execute([$short_code]);
$url = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$url) {
    die("URL not found.");
}

$stmt = $pdo->prepare("SELECT * FROM clicks WHERE url_id = ? ORDER BY click_time DESC");
$stmt->execute([$url['id']]);
$clicks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$qr_url = "../vendor/phpqrcode/qrcodes/{$short_code}.png";
$short_url = "http://localhost/linklynx/" . $short_code;
?>
<?php
session_start();
$title = "Page Title Here";
include __DIR__ . '/../includes/header.php';
?>

<div class="container mt-5">
  <h2>Analytics for Short URL: <?php echo htmlspecialchars($short_code); ?></h2>
  <p>Original URL: <a href="<?php echo $url['original_url']; ?>" target="_blank"><?php echo $url['original_url']; ?></a></p>
  <p>Short URL: <a href="redirect.php?c=<?php echo htmlspecialchars($short_code); ?>" target="_blank"><?php echo $short_url; ?></a></p>
  <p>Total Clicks: <?php echo $url['click_count']; ?></p>
  <p>QR Code:</p>
  <img src="<?php echo $qr_url; ?>" alt="QR Code">
  
  <h3 class="mt-4">Click Details</h3>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Click Time</th>
        <th>IP Address</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($clicks as $click): ?>
      <tr>
        <td><?php echo $click['click_time']; ?></td>
        <td><?php echo $click['ip_address']; ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($clicks)): ?>
      <tr>
        <td colspan="2">No clicks recorded.</td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>

