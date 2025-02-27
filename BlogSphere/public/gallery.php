<?php
require_once '../includes/config.php';
$pageTitle = "Gallery - BlogSphere";
$metaDescription = "Image gallery of BlogSphere posts.";
include '../includes/header.php';

$sql = "SELECT * FROM images ORDER BY uploaded_at DESC";
$result = $conn->query($sql);
?>
<div class="mb-5">
  <h2 class="mb-4">Gallery</h2>
  <div class="row">
    <?php if($result && $result->num_rows > 0): ?>
      <?php while($image = $result->fetch_assoc()): ?>
        <div class="col-md-3 mb-4">
          <div class="card shadow-sm">
            <img src="/BlogSphere/<?php echo htmlspecialchars($image['filename']); ?>" class="card-img-top" alt="Gallery Image">
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12">
        <p>No images found.</p>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
