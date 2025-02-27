<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Set page metadata for SEO
$pageTitle = "Home - BlogSphere";
$metaDescription = "Welcome to BlogSphere, a modern blog system with advanced features.";
$metaKeywords = "blog, articles, news";

include '../includes/header.php';

// Query for featured posts
$queryFeatured = "SELECT * FROM posts WHERE is_featured = 1 ORDER BY created_at DESC LIMIT 5";
$resultFeatured = $conn->query($queryFeatured);
?>
<div class="mb-5">
  <h2 class="mb-4">Featured Posts</h2>
  <?php if ($resultFeatured && $resultFeatured->num_rows > 0): ?>
    <div class="row">
      <?php while ($post = $resultFeatured->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">
                <a href="/BlogSphere/public/post.php?id=<?php echo $post['id']; ?>">
                  <?php echo htmlspecialchars($post['title']); ?>
                </a>
              </h5>
              <p class="card-text"><?php echo substr(strip_tags($post['content']), 0, 150); ?>...</p>
            </div>
            <div class="card-footer text-muted">
              Published on <?php echo date("F j, Y", strtotime($post['created_at'])); ?>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p>No featured posts available.</p>
  <?php endif; ?>
</div>

<div>
  <h2 class="mb-4">Latest Posts</h2>
  <?php
    $queryLatest = "SELECT * FROM posts ORDER BY created_at DESC LIMIT 10";
    $resultLatest = $conn->query($queryLatest);
  ?>
  <?php if ($resultLatest && $resultLatest->num_rows > 0): ?>
    <div class="list-group">
      <?php while ($post = $resultLatest->fetch_assoc()): ?>
        <a href="/BlogSphere/public/post.php?id=<?php echo $post['id']; ?>" class="list-group-item list-group-item-action">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1"><?php echo htmlspecialchars($post['title']); ?></h5>
            <small><?php echo date("F j, Y", strtotime($post['created_at'])); ?></small>
          </div>
          <p class="mb-1"><?php echo substr(strip_tags($post['content']), 0, 100); ?>...</p>
        </a>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p>No posts available.</p>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
