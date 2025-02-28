<?php
// admin_add_admin.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include 'includes/header.php';
include 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO admins (username, password) VALUES ('$username', '$passwordHash')";
    if ($conn->query($sql)) {
        $message = "Admin added successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>
<main>
    <div class="vw-container">
        <h2 class="vw-heading">Add Admin</h2>
        <?php if ($message) { echo "<p class='vw-error'>$message</p>"; } ?>
        <form method="post" action="admin_add_admin.php">
            <div class="vw-form-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="vw-form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <div class="vw-form-group">
                <input type="submit" class="vw-btn" value="Add Admin">
            </div>
        </form>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
