<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo isset($title) ? $title : 'LinkLynx'; ?></title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="/linklynx/css/style.css">
</head>
<body>
  <header class="bg-primary text-white py-3">
    <div class="container d-flex justify-content-between align-items-center">
      <h1 class="h3 mb-0">LinkLynx</h1>
      <!-- Optional Navigation -->
      <nav>
        <a href="/linklynx/public/index.php" class="text-white mr-3">Home</a>
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="/linklynx/public/manage.php" class="text-white mr-3">Manage Links</a>
          <a href="/linklynx/public/logout.php" class="text-white">Logout</a>
        <?php else: ?>
          <a href="/linklynx/public/login.php" class="text-white mr-3">Login</a>
          <a href="/linklynx/public/register.php" class="text-white">Register</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>
  <!-- Start of main content -->
  <main class="main-content container mt-4">
