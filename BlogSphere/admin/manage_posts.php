<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check for admin/editor access
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['Admin','Editor'])) {
    header("Location: login.php");
    exit;
}

$pageTitle = "Manage Posts - BlogSphere Admin";
include '../includes/header.php';

$query = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<div class="container mt-5">
  <h2 class="mb-4">Manage Posts</h2>
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Featured</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($post = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo $post['id']; ?></td>
              <td><?php echo htmlspecialchars($post['title']); ?></td>
              <td><?php echo $post['is_featured'] ? 'Yes' : 'No'; ?></td>
              <td><?php echo date("F j, Y", strtotime($post['created_at'])); ?></td>
              <td>
                <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this post?');">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center">No posts found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
