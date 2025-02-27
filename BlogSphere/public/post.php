<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$postId = intval($_GET['id']);
$query = "SELECT * FROM posts WHERE id = $postId LIMIT 1";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $post = $result->fetch_assoc();
    $pageTitle = htmlspecialchars($post['title']);
    $metaDescription = substr(strip_tags($post['content']), 0, 150);
    $metaKeywords = "blog, article"; // Adjust as needed

    // Query for the featured image of the current post (if any)
    // Query for the latest image of the current post
    $imgQuery = "SELECT * FROM images WHERE post_id = $postId ORDER BY uploaded_at DESC LIMIT 1";
    $imgResult = $conn->query($imgQuery);
    $imageUrl = '';
    if ($imgResult && $imgResult->num_rows > 0) {
        $imgRow = $imgResult->fetch_assoc();
        $imageUrl = $imgRow['filename']; // relative path to the image
    }
} else {
    header("Location: index.php");
    exit;
}

include '../includes/header.php';
?>

<article class="post-full mb-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h2>
            <p class="card-text"><small class="text-muted">Published on <?php echo date("F j, Y", strtotime($post['created_at'])); ?></small></p>
            <?php if ($imageUrl != ''): ?>
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm">
                        <img src="/BlogSphere/<?php echo htmlspecialchars($imageUrl); ?>" class="img-fluid mb-3" alt="Post Image">
                    </div>
                </div>
            <?php endif; ?>
            <div class="card-text">
                <?php echo $post['content']; ?>
            </div>
        </div>
    </div>
</article>

<!-- Related Posts Section -->
<section class="related-posts my-5">
    <h3 class="mb-4">Related Posts</h3>
    <div class="row">
        <?php
        // Fetch a few recent posts that are not the current one.
        $relatedQuery = "SELECT * FROM posts WHERE id != $postId ORDER BY created_at DESC LIMIT 3";
        $relatedResult = $conn->query($relatedQuery);
        if ($relatedResult && $relatedResult->num_rows > 0):
            while ($related = $relatedResult->fetch_assoc()):
                // Query for an image for the related post
                $relPostId = $related['id'];
                $relImgQuery = "SELECT * FROM images WHERE post_id = $relPostId LIMIT 1";
                $relImgResult = $conn->query($relImgQuery);
                $relImageUrl = '';
                if ($relImgResult && $relImgResult->num_rows > 0) {
                    $relImgRow = $relImgResult->fetch_assoc();
                    $relImageUrl = $relImgRow['filename'];
                }
        ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if ($relImageUrl != ''): ?>
                            <img src="/BlogSphere/<?php echo htmlspecialchars($relImageUrl); ?>" class="card-img-top" alt="Related Post Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="/BlogSphere/public/post.php?id=<?php echo $related['id']; ?>">
                                    <?php echo htmlspecialchars($related['title']); ?>
                                </a>
                            </h5>
                            <p class="card-text"><?php echo substr(strip_tags($related['content']), 0, 80); ?>...</p>
                        </div>
                    </div>
                </div>
            <?php endwhile;
        else: ?>
            <div class="col-12">
                <p>No related posts found.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>