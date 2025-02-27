<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check for Admin/Editor access
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['Admin', 'Editor'])) {
    header("Location: login.php");
    exit;
}

// Ensure a valid user ID exists; if not, use a default (or force login)
$author_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title            = $conn->real_escape_string(trim($_POST['title']));
    $slug             = $conn->real_escape_string(trim($_POST['slug'])) ?: generateSlug($title);
    $content          = $conn->real_escape_string($_POST['content']);
    $meta_description = $conn->real_escape_string(trim($_POST['meta_description']));
    $meta_keywords    = $conn->real_escape_string(trim($_POST['meta_keywords']));
    $is_featured      = isset($_POST['is_featured']) ? 1 : 0;

    $sql = "INSERT INTO posts (title, slug, content, author_id, meta_description, meta_keywords, is_featured)
            VALUES ('$title', '$slug', '$content', $author_id, '$meta_description', '$meta_keywords', $is_featured)";
    if ($conn->query($sql)) {
        $post_id = $conn->insert_id;
        // Process image upload if provided
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = '../uploads/';
            $fileName  = basename($_FILES['image']['name']);
            $targetFile = $uploadDir . time() . '_' . $fileName;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowed = array('jpg', 'jpeg', 'png', 'gif');
            if (in_array($imageFileType, $allowed)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    // Store the image path relative to the project
                    $targetFileRel = str_replace('../', '', $targetFile);
                    $sqlImg = "INSERT INTO images (post_id, filename) VALUES ($post_id, '$targetFileRel')";
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
$pageTitle = "Create New Post - BlogSphere Admin";
include '../includes/header.php';
?>

<div class="container">
  <div class="card mt-5 shadow-sm">
    <div class="card-header bg-success text-white">
      <h3 class="card-title mb-0">Create New Post</h3>
    </div>
    <div class="card-body">
      <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <form method="post" action="" enctype="multipart/form-data">
        <div class="form-group">
          <label for="title">Title:</label>
          <input type="text" name="title" class="form-control" id="title" required>
        </div>
        <div class="form-group">
          <label for="slug">Slug (optional):</label>
          <input type="text" name="slug" class="form-control" id="slug">
        </div>
        <div class="form-group">
          <label for="content">Content:</label>
          <textarea name="content" class="rich-text form-control" rows="10" id="content"></textarea>
        </div>
        <div class="form-group">
          <label for="meta_description">Meta Description:</label>
          <input type="text" name="meta_description" class="form-control" id="meta_description">
        </div>
        <div class="form-group">
          <label for="meta_keywords">Meta Keywords:</label>
          <input type="text" name="meta_keywords" class="form-control" id="meta_keywords">
        </div>
        <div class="form-check mb-3">
          <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured">
          <label class="form-check-label" for="is_featured">Featured</label>
        </div>
        <div class="form-group">
          <label for="image">Upload Image:</label>
          <input type="file" name="image" class="form-control-file" id="image">
        </div>
        <button type="submit" class="btn btn-success">Create Post</button>
      </form>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
