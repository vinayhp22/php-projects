<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check for Admin/Editor access
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['Admin','Editor'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: manage_posts.php");
    exit;
}

$postId = intval($_GET['id']);
$query = "SELECT * FROM posts WHERE id = $postId LIMIT 1";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $post = $result->fetch_assoc();
} else {
    header("Location: manage_posts.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string(trim($_POST['title']));
    $slug = $conn->real_escape_string(trim($_POST['slug'])) ?: generateSlug($title);
    $content = $conn->real_escape_string($_POST['content']);
    $meta_description = $conn->real_escape_string(trim($_POST['meta_description']));
    $meta_keywords = $conn->real_escape_string(trim($_POST['meta_keywords']));
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    $sql = "UPDATE posts SET title='$title', slug='$slug', content='$content', meta_description='$meta_description', meta_keywords='$meta_keywords', is_featured=$is_featured WHERE id = $postId";
    if ($conn->query($sql)) {
        // Process image upload if provided
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = '../uploads/';
            $fileName = basename($_FILES['image']['name']);
            $targetFile = $uploadDir . time() . '_' . $fileName;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowed = array('jpg', 'jpeg', 'png', 'gif');
            if (in_array($imageFileType, $allowed)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $targetFileRel = str_replace('../', '', $targetFile);
                    $sqlImg = "INSERT INTO images (post_id, filename) VALUES ($postId, '$targetFileRel')";
                    $conn->query($sqlImg);
                }
            }
        }
        header("Location: manage_posts.php");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}
$pageTitle = "Edit Post - BlogSphere Admin";
include '../includes/header.php';
?>

<div class="container">
  <div class="card mt-5 shadow-sm">
    <div class="card-header bg-warning text-dark">
      <h3 class="card-title mb-0">Edit Post</h3>
    </div>
    <div class="card-body">
      <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <form method="post" action="" enctype="multipart/form-data">
        <div class="form-group">
          <label for="title">Title:</label>
          <input type="text" name="title" class="form-control" id="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        </div>
        <div class="form-group">
          <label for="slug">Slug (optional):</label>
          <input type="text" name="slug" class="form-control" id="slug" value="<?php echo htmlspecialchars($post['slug']); ?>">
        </div>
        <div class="form-group">
          <label for="content">Content:</label>
          <textarea name="content" class="rich-text form-control" rows="10" id="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
        </div>
        <div class="form-group">
          <label for="meta_description">Meta Description:</label>
          <input type="text" name="meta_description" class="form-control" id="meta_description" value="<?php echo htmlspecialchars($post['meta_description']); ?>">
        </div>
        <div class="form-group">
          <label for="meta_keywords">Meta Keywords:</label>
          <input type="text" name="meta_keywords" class="form-control" id="meta_keywords" value="<?php echo htmlspecialchars($post['meta_keywords']); ?>">
        </div>
        <div class="form-check mb-3">
          <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured" <?php echo $post['is_featured'] ? 'checked' : ''; ?>>
          <label class="form-check-label" for="is_featured">Featured</label>
        </div>
        <div class="form-group">
          <label for="image">Upload New Image (optional):</label>
          <input type="file" name="image" class="form-control-file" id="image">
        </div>
        <button type="submit" class="btn btn-warning">Update Post</button>
      </form>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
