<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BookHaven</title>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
      <a class="navbar-brand" href="index.php">BookHaven</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
         aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav mr-auto">
              <li class="nav-item"><a class="nav-link" href="index.php">Inventory</a></li>
              <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li class="nav-item"><a class="nav-link" href="add_book.php">Add Book</a></li>
                <li class="nav-item"><a class="nav-link" href="add_user.php">Add User</a></li>
                <li class="nav-item"><a class="nav-link" href="receive_book.php">Receive Book</a></li>
              <?php endif; ?>
              <li class="nav-item"><a class="nav-link" href="borrow_book.php">Borrow Book</a></li>
              <li class="nav-item"><a class="nav-link" href="search.php">Search</a></li>
              <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
          </ul>
          <ul class="navbar-nav">
            <?php if(isset($_SESSION['username'])): ?>
              <li class="nav-item">
                <span class="navbar-text">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="edit_profile.php">My Profile</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
              </li>
            <?php else: ?>
              <li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="register.php">Register</a>
              </li>
            <?php endif; ?>
          </ul>
      </div>
  </nav>
  <div class="main-content container my-4">
