<?php
// public/bulk_shorten.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $urls = $_POST['urls']; // Expect one URL per line.
    $urls = explode("\n", $urls);
    $results = [];
    foreach($urls as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        // Validate URL.
        if (!filter_var($line, FILTER_VALIDATE_URL)) {
            $results[] = ["original" => $line, "error" => "Invalid URL"];
            continue;
        }
        // Generate a unique short code.
        $short_code = generateShortCode();
        $stmt = $pdo->prepare("SELECT * FROM urls WHERE short_code = ?");
        $stmt->execute([$short_code]);
        while($stmt->rowCount() > 0) {
            $short_code = generateShortCode();
            $stmt->execute([$short_code]);
        }
        $stmt = $pdo->prepare("INSERT INTO urls (original_url, short_code, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$line, $short_code]);
        $results[] = ["original" => $line, "short_code" => $short_code];
    }
}
?>

<?php
session_start();
$title = "Bulk URL Shortening";
include __DIR__ . '../includes/header.php';
?>
 
<div class="container mt-5">
  <h2>Bulk URL Shortening</h2>
  <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
    <h4>Results:</h4>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Original URL</th>
          <th>Short URL</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($results as $res): ?>
          <tr>
            <td><?php echo htmlspecialchars($res['original']); ?></td>
            <td>
              <?php 
              if(isset($res['error'])) {
                  echo $res['error'];
              } else {
                  $short_url = "http://localhost/redirect.php?c=" . $res['short_code'];
                  echo "<a href='redirect.php?c=" . htmlspecialchars($res['short_code']) . "' target='_blank'>{$short_url}</a>";
              }
              ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <a href="bulk_shorten.php" class="btn btn-primary">Shorten More URLs</a>
  <?php else: ?>
  <form action="bulk_shorten.php" method="POST">
    <div class="form-group">
      <label for="urls">Enter URLs (one per line):</label>
      <textarea name="urls" id="urls" class="form-control" rows="10" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Shorten URLs</button>
  </form>
  <?php endif; ?>
</div>
</body>
</html>
