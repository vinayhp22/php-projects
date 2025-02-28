<?php
// vote.php
session_start();
if (!isset($_SESSION['voter_id'])) {
    header("Location: voter_login.php");
    exit;
}
include 'includes/header.php';
include 'db.php';
include 'includes/functions.php';

$poll_id = isset($_GET['poll_id']) ? intval($_GET['poll_id']) : 0;

// Fetch poll details
$pollResult = $conn->query("SELECT * FROM polls WHERE id = $poll_id");
if($pollResult->num_rows == 0){
    echo "Poll not found.";
    exit;
}
$poll = $pollResult->fetch_assoc();

// Fetch options
$optionsResult = $conn->query("SELECT * FROM poll_options WHERE poll_id = $poll_id");
$options = [];
while($row = $optionsResult->fetch_assoc()){
    $options[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $option_id = intval($_POST['option']);
    $ip = $_SERVER['REMOTE_ADDR'];
    $voter_id = $_SESSION['voter_id'];
    // Insert vote
    $conn->query("INSERT INTO votes (poll_id, option_id, voter_id, ip_address) VALUES ($poll_id, $option_id, $voter_id, '$ip')");
    
    // Send notification email to poll creator
    sendNotificationEmail($poll['email'], $poll['title']);
    
    header("Location: poll_results.php?poll_id=$poll_id");
    exit;
}
?>
<main>
    <div class="vw-container">
        <h2 class="vw-heading"><?php echo htmlspecialchars($poll['title']); ?></h2>
        <p><?php echo htmlspecialchars($poll['description']); ?></p>
        <form method="post" action="vote.php?poll_id=<?php echo $poll_id; ?>">
            <?php foreach($options as $option): ?>
                <div class="vw-form-group">
                    <input type="radio" name="option" value="<?php echo $option['id']; ?>" required>
                    <label><?php echo htmlspecialchars($option['option_text']); ?></label>
                </div>
            <?php endforeach; ?>
            <input type="submit" class="vw-btn" value="Cast Vote">
        </form>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
