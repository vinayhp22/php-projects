<?php
$title = "Shorten URL - LinkLynx";
include __DIR__ . '/../includes/header.php';

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$user_id = isLoggedIn() ? $_SESSION['user_id'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $original_url = trim($_POST['original_url']);
    $custom_alias = trim($_POST['custom_alias']);
    $expiration_date = !empty($_POST['expiration_date']) ? date('Y-m-d H:i:s', strtotime($_POST['expiration_date'])) : null;
    $password = trim($_POST['password']);

    // Validate URL
    if (!filter_var($original_url, FILTER_VALIDATE_URL)) {
        echo "<div class='alert alert-danger container mt-5'>Invalid URL</div>";
        include __DIR__ . '/../includes/footer.php';
        exit;
    }

    // Use custom alias if provided; otherwise generate a random short code.
    $short_code = $custom_alias ? $custom_alias : generateShortCode();

    // Check if short code already exists.
    $stmt = $pdo->prepare("SELECT * FROM urls WHERE short_code = ?");
    $stmt->execute([$short_code]);
    if ($stmt->rowCount() > 0) {
        echo "<div class='alert alert-danger container mt-5'>Short code already exists. Please choose a different custom alias.</div>";
        include __DIR__ . '/../includes/footer.php';
        exit;
    }

    // Insert URL details into the database.
    $stmt = $pdo->prepare("INSERT INTO urls (original_url, short_code, expiration_date, password, user_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$original_url, $short_code, $expiration_date, $password, $user_id]);

    // Get the auto-incremented link id for later use (e.g., for QR download).
    $link_id = $pdo->lastInsertId();

    // Retrieve the server's IP address; if not available, default to '127.0.0.1'
    $server_ip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '127.0.0.1';
    // Build the short URL using the server IP.
    $short_url = "http://{$server_ip}/linklynx/" . $short_code;
    
    // Generate QR Code based on the IP-based URL.
    $qr_dir = __DIR__ . "/../vendor/phpqrcode/qrcodes/";
    if (!is_dir($qr_dir)) {
        mkdir($qr_dir, 0755, true);
    }
    $qr_file = $qr_dir . "{$short_code}.png";
    generateQRCode($short_url, $qr_file);
    ?>
    <div class="container mt-5">
      <div class="card shadow">
        <div class="card-header bg-success text-white">
          <h3 class="card-title mb-0">URL Shortened Successfully!</h3>
        </div>
        <div class="card-body">
          <p><strong>Original URL:</strong> <a href="<?php echo htmlspecialchars($original_url); ?>" target="_blank"><?php echo htmlspecialchars($original_url); ?></a></p>
          <p><strong>Short URL:</strong> <a href="redirect.php?c=<?php echo $short_code; ?>" target="_blank"><?php echo $short_url; ?></a></p>
          <p><strong>QR Code:</strong></p>
          <div class="text-center">
            <img src="../vendor/phpqrcode/qrcodes/<?php echo $short_code; ?>.png" alt="QR Code" class="img-thumbnail" style="max-width:200px;">
          </div>
          <div class="mt-4 d-flex flex-wrap justify-content-center">
            <?php if (isLoggedIn()): ?>
              <a href="download_qr.php?id=<?php echo $link_id; ?>" class="btn btn-info m-2">Download QR Code</a>
              <a href="manage.php" class="btn btn-primary m-2">Manage Your Links</a>
            <?php else: ?>
              <p class="m-2">Please <a href="login.php">login</a> to download the QR Code and manage your links.</p>
            <?php endif; ?>
            <a href="analytics.php?c=<?php echo $short_code; ?>" class="btn btn-success m-2">View Analytics</a>
          </div>
        </div>
      </div>
    </div>
    <?php
}
include __DIR__ . '/../includes/footer.php';
?>
