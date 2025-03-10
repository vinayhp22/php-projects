<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();
$title = "Manage Links - LinkLynx";

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM urls WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$urls = $stmt->fetchAll(PDO::FETCH_ASSOC);
$server_ip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '127.0.0.1';
include __DIR__ . '/../includes/header.php';
?>
<div class="mb-4">
  <h2>Your Shortened URLs</h2>
  <div class="d-flex justify-content-between mb-3">
    <a href="index.php" class="btn btn-secondary">Back to Shortener</a>
    <a href="logout.php" class="btn btn-danger">Logout</a>
  </div>
</div>
<?php if (count($urls) > 0): ?>
  <table class="table table-bordered table-striped">
    <thead class="thead-dark">
      <tr>
        <th>S.No.</th>
        <th>Original URL</th>
        <th>Short Link</th>
        <th>QR Code (IP)</th>
        <th>Clicks</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php $serial = 1; ?>
      <?php foreach ($urls as $url): ?>
      <?php 
        $short_code = htmlspecialchars($url['short_code']);
        $short_link_ip = "http://{$server_ip}/linklynx/" . $short_code;
        $qr_file_path_ip = "../vendor/phpqrcode/qrcodes/" . $short_code . "_ip.png";
        if (!file_exists($qr_file_path_ip)) {
            generateQRCode($short_link_ip, $qr_file_path_ip);
        }
      ?>
      <tr>
        <td><?php echo $serial++; ?></td>
        <td><?php echo htmlspecialchars($url['original_url']); ?></td>
        <td>
          <a href="<?php echo $short_link_ip; ?>" target="_blank"><?php echo $short_link_ip; ?></a>
        </td>
        <td>
          <?php if (file_exists($qr_file_path_ip)): ?>
            <img src="<?php echo $qr_file_path_ip; ?>" alt="QR Code" style="max-width: 80px;">
          <?php else: ?>
            <span>No QR code</span>
          <?php endif; ?>
        </td>
        <td><?php echo htmlspecialchars($url['click_count']); ?></td>
        <td><?php echo htmlspecialchars($url['created_at']); ?></td>
        <td>
          <a href="edit.php?id=<?php echo $url['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
          <a href="delete.php?id=<?php echo $url['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this link?');">Delete</a>
          <a href="download_qr.php?id=<?php echo $url['id']; ?>" class="btn btn-sm btn-info">Download QR</a>
          <a href="analytics.php?c=<?php echo $short_code; ?>" class="btn btn-sm btn-success">Analytics</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <p>No links found. Create some using the shortener!</p>
<?php endif; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
