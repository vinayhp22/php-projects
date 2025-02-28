<?php
// index.php
include 'includes/header.php';
include 'db.php';

$result = $conn->query("SELECT * FROM polls ORDER BY created_at DESC");
?>
<main>
    <div class="vw-container">
        <h2 class="vw-heading">Welcome to VoteWave</h2>
        <p class="vw-subheading">Participate in the latest polls and cast your vote now!</p>
        <div class="vw-grid">
            <?php while($poll = $result->fetch_assoc()): ?>
                <div class="vw-card">
                    <h3><?php echo htmlspecialchars($poll['title']); ?></h3>
                    <p><?php echo htmlspecialchars($poll['description']); ?></p>
                    <div class="vw-poll-action">
                        <a class="vw-btn vw-vote-btn" href="vote.php?poll_id=<?php echo $poll['id']; ?>">Vote Now</a>
                        <a class="vw-btn vw-results-btn" href="poll_results.php?poll_id=<?php echo $poll['id']; ?>">View Results</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
