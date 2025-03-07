<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ContactFlow - Contact Form</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <!-- Load reCAPTCHA v2 API -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="index.php">ContactFlow</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Form Container -->
  <div class="container mt-5">
    <h2>Contact Us</h2>
    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success">Your message has been sent successfully!</div>
    <?php endif; ?>
    <form id="contactForm" action="process_contact.php" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" name="name" id="name" required>
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" name="email" id="email" required>
      </div>
      <div class="form-group">
        <label for="subject">Subject</label>
        <input type="text" class="form-control" name="subject" id="subject" required>
      </div>
      <div class="form-group">
        <label for="message">Message</label>
        <textarea class="form-control" name="message" id="message" rows="5" required></textarea>
      </div>
      <div class="form-group">
        <label for="attachment">File Attachment (optional)</label>
        <input type="file" class="form-control-file" name="attachment" id="attachment">
      </div>
      <!-- reCAPTCHA v2 widget -->
      <div class="g-recaptcha mb-3" data-sitekey="6LfsTewqAAAAAMZfjkuJLymY0EBxrqmtweF6ihzy"></div>
      <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
  </div>

  <!-- Footer -->
  <footer class="bg-light text-center py-3 mt-5">
    <div class="container">
      <p class="mb-0">&copy; 2025 ContactFlow. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
