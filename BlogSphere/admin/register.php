<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username         = $conn->real_escape_string(trim($_POST['username']));
    $email            = $conn->real_escape_string(trim($_POST['email']));
    $password         = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role             = $conn->real_escape_string(trim($_POST['role'])); // e.g., Subscriber, Editor, Admin

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', '$role')";
        if ($conn->query($sql)) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Registration failed: " . $conn->error;
        }
    }
}
$pageTitle = "Register - BlogSphere";
include '../includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title mb-0">User Registration</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" class="form-control" required>
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
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password:</label>
                            <div class="input-group">
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <a href="#" onclick="togglePassword('confirm_password', event)">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="role">Role:</label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="Subscriber">Subscriber</option>
                                <option value="Editor">Editor</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info btn-block">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>