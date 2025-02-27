<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['user_id']   = $user['id'];
            header("Location: manage_posts.php");
            exit;
        } else {
            $error = "Invalid credentials.";
        }
    } else {
        $error = "Invalid credentials.";
    }
}
$pageTitle = "Login - BlogSphere Admin";
include '../includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Admin Login</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" name="username" class="form-control" id="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <a href="#" onclick="togglePassword('password', event)">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </form>
                    <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register here</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>