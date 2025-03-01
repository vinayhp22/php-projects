<?php
session_start();
require_once '../config/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username       = trim($_POST['username']);
    $password       = $_POST['password'];
    $confirm        = $_POST['confirm'];
    $role           = $_POST['role'];
    $adminOTP       = isset($_POST['admin_otp']) ? trim($_POST['admin_otp']) : '';

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        if ($role === 'admin' && $adminOTP !== '112233') {
            $error = "Invalid OTP for admin registration.";
        } else {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = "Username already taken.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                $stmt->execute([$username, $hashedPassword, $role]);
                header("Location: login.php");
                exit;
            }
        }
    }
}
include '../includes/header.php';
?>
<h2>Register</h2>
<?php if(isset($error)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="POST">
    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" required class="form-control" placeholder="Choose a username">
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required class="form-control" placeholder="Enter a password">
    </div>
    <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm" required class="form-control" placeholder="Re-enter your password">
    </div>
    <div class="form-group">
        <label>Role</label><br>
        <input type="radio" name="role" value="user" checked onclick="toggleOTPField()"> User &nbsp;
        <input type="radio" name="role" value="admin" onclick="toggleOTPField()"> Admin
    </div>
    <div class="form-group" id="otpField" style="display: none;">
        <label>Admin OTP</label>
        <input type="text" name="admin_otp" class="form-control" placeholder="Enter OTP for admin registration">
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
</form>
<p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>
<script>
  function toggleOTPField() {
      var role = document.querySelector('input[name="role"]:checked').value;
      document.getElementById('otpField').style.display = (role === 'admin') ? 'block' : 'none';
  }
</script>
<?php include '../includes/footer.php'; ?>
