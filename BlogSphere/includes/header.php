<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <!-- Dynamic meta tags for SEO -->
  <meta name="description" content="<?php echo isset($metaDescription) ? $metaDescription : 'BlogSphere - A Modern Blog System'; ?>">
  <meta name="keywords" content="<?php echo isset($metaKeywords) ? $metaKeywords : 'blog, posts, articles'; ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?php echo isset($pageTitle) ? $pageTitle : 'BlogSphere'; ?></title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="/BlogSphere/assets/css/style.css">
  <!-- Open Graph / Social meta tags -->
  <meta property="og:title" content="<?php echo isset($pageTitle) ? $pageTitle : 'BlogSphere'; ?>">
  <meta property="og:description" content="<?php echo isset($metaDescription) ? $metaDescription : 'A modern blog system'; ?>">
  <!-- TinyMCE Rich Text Editor -->
  <script src="https://cdn.tiny.cloud/1/ew0430ymo96xdh2vasap4libno25n36wb5jzg7gyqow8dqmg/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    tinymce.init({
      selector: 'textarea.rich-text',
      menubar: false
    });
  </script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
  <header class="bg-primary text-white">
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="/BlogSphere/public/index.php">BlogSphere</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="/BlogSphere/public/index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="/BlogSphere/public/gallery.php">Gallery</a></li>
            <?php if(isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['Admin','Editor'])): ?>
              <li class="nav-item"><a class="nav-link" href="/BlogSphere/admin/manage_posts.php">Admin Panel</a></li>
            <?php endif; ?>
            <?php if(isset($_SESSION['username'])): ?>
              <li class="nav-item"><a class="nav-link" href="/BlogSphere/admin/logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
            <?php else: ?>
              <li class="nav-item"><a class="nav-link" href="/BlogSphere/admin/login.php">Login</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </nav>
    </div>
  </header>
  <main class="container my-4">
