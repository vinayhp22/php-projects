<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in as Admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'Admin') {
    header("Location: login.php");
    exit;
}

$pageTitle = "Admin Dashboard - BlogSphere";
include '../includes/header.php';
?>

<div class="container mt-5">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h3 class="card-title mb-0">Admin Dashboard</h3>
    </div>
    <div class="card-body">
      <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
      <ul class="list-group">
        <li class="list-group-item"><a href="create_post.php">Create New Post</a></li>
        <li class="list-group-item"><a href="manage_posts.php">Manage Posts</a></li>
      </ul>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
