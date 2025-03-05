<?php
session_start();
require_once '../config/db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$registrationError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($username === '' || $email === '' || $password === '') {
        $registrationError = 'All fields are required.';
    } else {
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmtCheck->execute([$username, $email]);
        $count = $stmtCheck->fetchColumn();
        if ($count > 0) {
            $registrationError = 'Username or email is already in use.';
        } else {
            // For production, consider using password_hash() instead of MD5.
            $stmtInsert = $pdo->prepare("INSERT INTO users (username, password_hash, email, role) VALUES (?, MD5(?), ?, 'user')");
            $stmtInsert->execute([$username, $password, $email]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'user';
            header('Location: index.php');
            exit;
        }
    }
}

$pageTitle = "Register - Quizify";
include 'header.php';
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-header text-center">
         <h2>Register</h2>
      </div>
      <div class="card-body">
         <?php if ($registrationError): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($registrationError) ?></div>
         <?php endif; ?>
         <form method="POST">
           <div class="mb-3">
             <label class="form-label">Username</label>
             <input type="text" name="username" class="form-control" required>
           </div>
           <div class="mb-3">
             <label class="form-label">Email</label>
             <input type="email" name="email" class="form-control" required>
           </div>
           <div class="mb-3">
             <label class="form-label">Password</label>
             <input type="password" name="password" class="form-control" required>
           </div>
           <button type="submit" class="btn btn-primary w-100">Register</button>
         </form>
         <div class="mt-3 text-center">
           <p>Already have an account? <a href="login.php">Login here</a>.</p>
         </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
