<?php
// admin_dashboard.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include 'includes/header.php';
include 'db.php';

// Fetch summary statistics
$pollCountResult  = $conn->query("SELECT COUNT(*) as total FROM polls");
$pollCountRow     = $pollCountResult->fetch_assoc();
$pollCount        = $pollCountRow['total'];

$voterCountResult = $conn->query("SELECT COUNT(*) as total FROM voters");
$voterCountRow    = $voterCountResult->fetch_assoc();
$voterCount       = $voterCountRow['total'];

$adminCountResult = $conn->query("SELECT COUNT(*) as total FROM admins");
$adminCountRow    = $adminCountResult->fetch_assoc();
$adminCount       = $adminCountRow['total'];
?>
<main>
    <div class="vw-container">
        <h2 class="vw-heading">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h2>
        <p class="vw-subheading">This is your admin dashboard. Use the options below to manage the voting system efficiently.</p>
        
        <div class="vw-dashboard">
            <div class="vw-dashboard-links">
                <h3 class="vw-dashboard-title">Management Options</h3>
                <ul>
                    <li><a class="vw-btn" href="admin_create_poll.php">Create New Poll</a></li>
                    <li><a class="vw-btn" href="admin_manage_results.php">Manage Poll Results</a></li>
                    <li><a class="vw-btn" href="admin_manage_users.php">Manage Admins &amp; Voters</a></li>
                    <li><a class="vw-btn" href="admin_add_admin.php">Add New Admin</a></li>
                    <li><a class="vw-btn" href="admin_add_voter.php">Add New Voter</a></li>
                </ul>
            </div>
            <div class="vw-dashboard-stats">
                <h3 class="vw-dashboard-title">System Statistics</h3>
                <ul>
                    <li><strong>Total Polls:</strong> <?php echo $pollCount; ?></li>
                    <li><strong>Total Voters:</strong> <?php echo $voterCount; ?></li>
                    <li><strong>Total Admins:</strong> <?php echo $adminCount; ?></li>
                </ul>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
