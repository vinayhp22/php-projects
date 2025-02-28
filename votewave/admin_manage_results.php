<?php
// admin_manage_results.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include 'includes/header.php';
include 'db.php';

$result = $conn->query("SELECT * FROM polls ORDER BY created_at DESC");
?>
<main>
    <div class="vw-container">
        <h2 class="vw-heading">Manage Polls and Results</h2>
        <div class="vw-grid">
            <?php while($poll = $result->fetch_assoc()): ?>
                <div class="vw-card">
                    <h3>
                        <a class="vw-edit-link" href="admin_edit_poll.php?poll_id=<?php echo $poll['id']; ?>">
                            <?php echo htmlspecialchars($poll['title']); ?>
                        </a>
                    </h3>
                    <p><?php echo htmlspecialchars($poll['description']); ?></p>
                    <div class="vw-card-footer">
                        <a class="vw-btn" href="poll_results.php?poll_id=<?php echo $poll['id']; ?>">View Results</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
