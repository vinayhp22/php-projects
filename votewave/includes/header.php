<?php
// includes/header.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VoteWave - Online Voting System</title>
    <link rel="stylesheet" href="assets/style.css">
    <!-- Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/script.js"></script>
</head>
<body>
    <header class="vw-header">
        <a href="index.php" class="vw-navbar-brand">VoteWave</a>
        <nav class="vw-nav-links">
            <?php if (isset($_SESSION['admin_id'])): ?>
                <a href="admin_dashboard.php">Admin Dashboard</a>
                <a href="admin_create_poll.php">Create Poll</a>
                <a href="admin_manage_results.php">Manage Results</a>
                <a href="admin_manage_users.php">Manage Users</a>
                <a href="admin_logout.php">Logout</a>
            <?php elseif (isset($_SESSION['voter_id'])): ?>
                <a href="voter_dashboard.php">Dashboard</a>
                <a href="voter_logout.php">Logout</a>
            <?php else: ?>
                <a href="index.php">Home</a>
                <a href="voter_login.php">Voter Login</a>
                <a href="admin_login.php">Admin Login</a>
            <?php endif; ?>
        </nav>
    </header>
