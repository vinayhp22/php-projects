<?php
// public/edit.php
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
    die("Link not found or you don't have permission to edit it.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $original_url = trim($_POST['original_url']);
    $expiration_date = !empty($_POST['expiration_date']) ? date('Y-m-d H:i:s', strtotime($_POST['expiration_date'])) : null;
    $password = trim($_POST['password']);

    if (!filter_var($original_url, FILTER_VALIDATE_URL)) {
        $error = "Invalid URL";
    } else {
        $stmt = $pdo->prepare("UPDATE urls SET original_url = ?, expiration_date = ?, password = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$original_url, $expiration_date, $password, $link_id, $user_id]);
        header("Location: manage.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Link - LinkLynx</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
  <h2>Edit Link</h2>
  <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>
  <form action="edit.php?id=<?php echo $link_id; ?>" method="POST">
    <div class="form-group">
      <label for="original_url">Original URL</label>
      <input type="url" name="original_url" id="original_url" class="form-control" required value="<?php echo htmlspecialchars($link['original_url']); ?>">
    </div>
    <div class="form-group">
      <label for="expiration_date">Expiration Date (Optional)</label>
      <input type="datetime-local" name="expiration_date" id="expiration_date" class="form-control" value="<?php echo $link['expiration_date'] ? date('Y-m-d\TH:i', strtotime($link['expiration_date'])) : ''; ?>">
    </div>
    <div class="form-group">
      <label for="password">Password Protection (Optional)</label>
      <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password if you want to change">
    </div>
    <button type="submit" class="btn btn-primary">Update Link</button>
    <a href="manage.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
