<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require_once '../config/config.php';
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
if (!$user) {
    echo "User not found!";
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    $params = [$username, $_SESSION['user_id']];
    $sql = "UPDATE users SET username = ?";
    if (!empty($newPassword)) {
        if ($newPassword !== $confirmPassword) {
            $error = "New passwords do not match.";
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql .= ", password = ?";
            $params = [$username, $hashedPassword, $_SESSION['user_id']];
        }
    }
    if (!isset($error)) {
        $sql .= " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $_SESSION['username'] = $username;
        $success = "Profile updated successfully.";
    }
}
include '../includes/header.php';
?>
<h2>Edit Profile</h2>
<?php if(isset($error)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php elseif(isset($success)): ?>
  <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<form method="POST">
    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" required class="form-control" value="<?= htmlspecialchars($user['username']) ?>">
    </div>
    <div class="form-group">
        <label>New Password (leave blank if not changing)</label>
        <input type="password" name="new_password" class="form-control">
    </div>
    <div class="form-group">
        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Update Profile</button>
</form>
<?php include '../includes/footer.php'; ?>
