<?php
session_start();
require_once '../config/db.php';

// If user is already logged in, redirect them to the home page.
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Using MD5 for demonstration. For production, use password_hash() and password_verify().
    if ($user && md5($password) == $user['password_hash']) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] === 'admin') {
            header('Location: ../admin/index.php');
        } else {
            header('Location: index.php');
        }
        exit;
    } else {
        $error = 'Invalid credentials.';
    }
}

$pageTitle = "Login - Quizify";
include 'header.php';
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-header text-center">
         <h2>Login</h2>
      </div>
      <div class="card-body">
         <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
         <?php endif; ?>
         <form method="POST">
           <div class="mb-3">
             <label class="form-label">Username</label>
             <input type="text" name="username" class="form-control" required>
           </div>
           <div class="mb-3">
             <label class="form-label">Password</label>
             <input type="password" name="password" class="form-control" required>
           </div>
           <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
         </form>
         <div class="mt-3 text-center">
           <p>Don't have an account? <a href="register.php">Register here</a>.</p>
         </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
