<?php
session_start();
$title = "Home - LinkLynx";
include __DIR__ . '\..\includes\header.php';
?>
<div class="container">
  <h2 class="mb-4">Welcome to LinkLynx URL Shortener</h2>
  <?php if (isset($_SESSION['user_id'])): ?>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! <a href="logout.php">Logout</a></p>
  <?php else: ?>
    <p><a href="login.php">Login</a> or <a href="register.php">Register</a> to manage your links.</p>
  <?php endif; ?>
  <form action="shorten.php" method="POST" class="mt-4">
    <div class="form-group">
      <label for="original_url">Original URL</label>
      <input type="url" name="original_url" id="original_url" class="form-control" required placeholder="Enter URL">
    </div>
    <div class="form-group">
      <label for="custom_alias">Custom Short URL (Optional)</label>
      <input type="text" name="custom_alias" id="custom_alias" class="form-control" placeholder="Enter custom alias">
    </div>
    <div class="form-group">
      <label for="expiration_date">Expiration Date (Optional)</label>
      <input type="datetime-local" name="expiration_date" id="expiration_date" class="form-control">
    </div>
    <div class="form-group">
      <label for="password">Password Protection (Optional)</label>
      <input type="password" name="password" id="password" class="form-control" placeholder="Enter password">
    </div>
    <button type="submit" class="btn btn-primary">Shorten URL</button>
  </form>
  <hr>
  <a href="bulk_shorten.php" class="btn btn-secondary">Bulk URL Shortening</a>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
