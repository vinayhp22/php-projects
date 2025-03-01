<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
require_once '../config/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];
    $role     = $_POST['role'];
    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "Username already taken.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hashedPassword, $role]);
            $success = "User added successfully.";
        }
    }
}
include '../includes/header.php';
?>
<h2>Add New User</h2>
<?php if(isset($error)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php elseif(isset($success)): ?>
  <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<form method="POST">
    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" required class="form-control" placeholder="User username">
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required class="form-control" placeholder="User password">
    </div>
    <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm" required class="form-control" placeholder="Re-enter password">
    </div>
    <div class="form-group">
        <label>Role</label>
        <select name="role" required class="form-control">
            <option value="user" selected>User</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Add User</button>
</form>
<?php include '../includes/footer.php'; ?>
