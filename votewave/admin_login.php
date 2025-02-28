<?php
// admin_login.php
session_start();
include 'includes/header.php';
include 'db.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    $result = $conn->query("SELECT * FROM admins WHERE username='$username' LIMIT 1");
    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: admin_dashboard.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Admin not found.";
    }
}
?>
<main>
    <div class="vw-login-container">
        <div class="vw-login-card">
            <h2>Admin Login</h2>
            <?php if($error) { echo "<p class='vw-error'>$error</p>"; } ?>
            <form method="post" action="admin_login.php">
                <div class="vw-form-group">
                    <label for="admin-username">Username:</label>
                    <input type="text" id="admin-username" name="username" required>
                </div>
                <div class="vw-form-group">
                    <label for="admin-password">Password:</label>
                    <input type="password" id="admin-password" name="password" required>
                </div>
                <div class="vw-form-group">
                    <input type="submit" class="vw-btn" value="Login">
                </div>
            </form>
            <p class="vw-register-msg">
                Not registered? Connect with an admin or email <a href="mailto:register@votewave.com">register@votewave.com</a> for registration.
            </p>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
