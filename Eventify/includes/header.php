<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Eventify</title>
  <!-- Bootstrap CSS -->
  <link 
    rel="stylesheet" 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
  >
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<!-- Fixed-top Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="../public/index.php">Eventify</a>
    <button 
      class="navbar-toggler" 
      type="button" 
      data-bs-toggle="collapse" 
      data-bs-target="#navbarNav" 
      aria-controls="navbarNav" 
      aria-expanded="false" 
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['user_id'])): ?>
          <!-- Example links for logged-in users -->
          <li class="nav-item">
            <a class="nav-link" href="index.php?action=list">All Events</a>
          </li>
          <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="index.php?action=create">Create Event</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?action=analytics">Analytics</a>
            </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php?action=my_registrations">My Registrations</a>
          </li>
          <li class="nav-item">
            <a class="nav-link">Welcome <?php echo $_SESSION['user_name'] ?> to Eventify!! </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../auth/logout.php">Logout</a>
          </li>
        <?php else: ?>
          <!-- If not logged in -->
          <li class="nav-item">
            <a class="nav-link" href="../auth/login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../auth/register.php">Register</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Main content wrapper with top & bottom padding 
     to avoid overlap from the fixed navbar & footer. -->
<div class="content-wrapper" style="padding-top: 70px; padding-bottom: 70px;">
  <div class="container">
