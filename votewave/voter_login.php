<?php
// voter_login.php
session_start();
include 'includes/header.php';
include 'db.php';

if (isset($_SESSION['voter_id'])) {
    header("Location: voter_dashboard.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    $result = $conn->query("SELECT * FROM voters WHERE username='$username' LIMIT 1");
    if ($result->num_rows == 1) {
        $voter = $result->fetch_assoc();
        if (password_verify($password, $voter['password'])) {
            $_SESSION['voter_id'] = $voter['id'];
            $_SESSION['voter_username'] = $voter['username'];
            header("Location: voter_dashboard.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>
<main>
    <div class="vw-login-container">
        <div class="vw-login-card">
            <h2>Voter Login</h2>
            <?php if($error) { echo "<p class='vw-error'>$error</p>"; } ?>
            <form method="post" action="voter_login.php">
                <div class="vw-form-group">
                    <label for="voter-username">Username:</label>
                    <input type="text" id="voter-username" name="username" required>
                </div>
                <div class="vw-form-group">
                    <label for="voter-password">Password:</label>
                    <input type="password" id="voter-password" name="password" required>
                </div>
                <div class="vw-form-group">
                    <input type="submit" class="vw-btn" value="Login">
                </div>
            </form>
            <p class="vw-register-msg">
                Not registered? Connect with your admin or email <a href="mailto:register@votewave.com">register@votewave.com</a> for registration.
            </p>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
