<?php 
// If user is already logged in, redirect
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: ../public/index.php');
    exit;
}

// Include your header & classes
include_once '../includes/header.php';
require_once '../database/db_connect.php';
require_once '../classes/Auth.php';

// Setup
$auth = new Auth($pdo);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name']     ?? '';
    $email    = $_POST['email']    ?? '';
    $password = $_POST['password'] ?? '';

    // If "Register as Admin" was chosen, we also expect an OTP
    $otp = null;
    if (isset($_POST['is_admin']) && $_POST['is_admin'] === 'on') {
        $otp = $_POST['admin_otp'] ?? null;
    }

    $result = $auth->register($name, $email, $password, $otp);
    if ($result['success']) {
        $message = $result['message'] . ' You can now <a href="login.php">login</a>.';
    } else {
        $message = $result['message'];
    }
}
?>

<div class="container">
  <div class="row justify-content-center align-items-center" style="min-height:70vh;">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
          <h4 class="card-title mb-0">Register</h4>
        </div>
        <div class="card-body">
          <?php if($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
          <?php endif; ?>

          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input 
                required 
                type="text" 
                name="name" 
                class="form-control"
                placeholder="Enter your name..."
              >
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input 
                required 
                type="email" 
                name="email" 
                class="form-control"
                placeholder="Enter your email..."
              >
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input 
                required 
                type="password" 
                name="password" 
                class="form-control"
                placeholder="Enter your password..."
              >
            </div>
            <div class="form-check mb-3">
              <input 
                class="form-check-input" 
                type="checkbox" 
                name="is_admin" 
                id="adminCheck"
              >
              <label for="adminCheck" class="form-check-label">
                Register as Admin
              </label>
            </div>
            <div class="mb-3" id="otpField" style="display:none;">
              <label class="form-label">Admin OTP</label>
              <input 
                type="text" 
                name="admin_otp" 
                class="form-control"
                placeholder="Enter admin OTP..."
              >
              <small class="text-muted">
                Default OTP is 112233
              </small>
            </div>
            <button type="submit" class="btn btn-success w-100">Register</button>
          </form>

          <hr>
          <p class="text-center mb-0">
            Already have an account? 
            <a href="login.php">Login here</a>
          </p>
        </div>
      </div> <!-- end card -->
    </div>
  </div>
</div>

<?php include_once '../includes/footer.php'; ?>

<script>
// Show/hide the OTP field if user checks 'Register as Admin'
document.addEventListener('DOMContentLoaded', function() {
  const adminCheck = document.getElementById('adminCheck');
  const otpField = document.getElementById('otpField');

  adminCheck.addEventListener('change', function() {
    otpField.style.display = adminCheck.checked ? 'block' : 'none';
  });
});
</script>
