<?php
if(session_status() !== PHP_SESSION_ACTIVE){
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($pageTitle) ? $pageTitle : "Quizify" ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Quizify</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" >
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
       <ul class="navbar-nav ms-auto">
         <?php if(isset($_SESSION['user_id'])): ?>
         <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
         <li class="nav-item"><a class="nav-link text-white">Hello, <?= htmlspecialchars($_SESSION['username']) ?>!</a></li>
         <li class="nav-item"><a class="nav-link" href="../public/logout.php">Logout</a></li>
         <?php else: ?>
         <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
         <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
         <?php endif; ?>
       </ul>
    </div>
  </div>
</nav>
<!-- Start main content -->
<div class="container my-4 content">
