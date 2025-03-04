<?php 
// If you prefer to do a quick check before loading header:
session_start(); 
if (isset($_SESSION['user_id'])) {
    // If already logged in, redirect them somewhere
    header('Location: ../public/index.php');
    exit;
}

// Then include your shared header
include_once '../includes/header.php';

// Include or require the DB & Auth classes
require_once '../database/db_connect.php';
require_once '../classes/Auth.php';

$auth = new Auth($pdo);
$message = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = $auth->login($email, $password);
    if ($result['success']) {
        // Store user info in session
        $_SESSION['user_id']   = $result['user']['user_id'];
        $_SESSION['user_role'] = $result['user']['role'];
        $_SESSION['user_name'] = $result['user']['name'];
        $_SESSION['user_email'] = $result['user']['email']; // if you store email

        header('Location: ../public/index.php');
        exit;
    } else {
        $message = $result['message'];
    }
}
?>

<div class="container">
  <div class="row justify-content-center align-items-center" style="min-height:70vh;">
    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
          <h4 class="card-title mb-0">Login</h4>
        </div>
        <div class="card-body">
          <?php if($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
          <?php endif; ?>

          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input 
                required 
                name="email" 
                type="email" 
                class="form-control" 
                placeholder="Enter your email..."
                autofocus
              >
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input 
                required 
                name="password" 
                type="password" 
                class="form-control"
                placeholder="Enter your password..."
              >
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
          </form>
          <hr>
          <p class="text-center mb-0">
            Don’t have an account? 
            <a href="register.php">Register here</a>
          </p>
        </div>
      </div> <!-- end card -->
    </div>
  </div>
</div>

<?php include_once '../includes/footer.php'; ?>
