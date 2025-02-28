<?php
// voter_dashboard.php
session_start();
if (!isset($_SESSION['voter_id'])) {
    header("Location: voter_login.php");
    exit;
}
include 'includes/header.php';
include 'db.php';
?>
<main>
    <div class="vw-container">
        <h2 class="vw-heading">Welcome, <?php echo htmlspecialchars($_SESSION['voter_username']); ?>!</h2>
        <h3 class="vw-subheading">Available Polls</h3>
        <div class="vw-grid">
            <?php
            $result = $conn->query("SELECT * FROM polls ORDER BY created_at DESC");
            if ($result && $result->num_rows > 0):
                while ($poll = $result->fetch_assoc()):
                    $voter_id = $_SESSION['voter_id'];
                    $poll_id = $poll['id'];
                    $voteQuery = "SELECT COUNT(*) as count FROM votes WHERE poll_id = $poll_id AND voter_id = $voter_id";
                    $voteResult = $conn->query($voteQuery);
                    $voteRow = $voteResult->fetch_assoc();
                    $hasVoted = ($voteRow['count'] > 0);
            ?>
                    <div class="vw-card">
                        <h4><?php echo htmlspecialchars($poll['title']); ?></h4>
                        <p><?php echo htmlspecialchars($poll['description']); ?></p>
                        <p class="vw-poll-date"><small>Created on: <?php echo date("M d, Y", strtotime($poll['created_at'])); ?></small></p>
                        <div class="vw-poll-action">
                            <?php if ($hasVoted): ?>
                                <p class="vw-voted-msg">Thanks for voting, every vote matters. See real-time vote count <a href="poll_results.php?poll_id=<?php echo $poll['id']; ?>">here</a>.</p>
                            <?php else: ?>
                                <a class="vw-btn" href="vote.php?poll_id=<?php echo $poll['id']; ?>">Vote Now</a>
                            <?php endif; ?>
                        </div>
                    </div>
            <?php
                endwhile;
            else:
                echo "<p class='vw-no-polls'>No polls available at the moment.</p>";
            endif;
            ?>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
