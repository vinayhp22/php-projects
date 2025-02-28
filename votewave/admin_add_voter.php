<?php
// admin_add_voter.php
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
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO voters (username, email, password) VALUES ('$username', '$email', '$passwordHash')";
    if ($conn->query($sql)) {
        $message = "Voter added successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>
<main>
    <div class="vw-container">
        <h2 class="vw-heading">Add Voter</h2>
        <?php if ($message) { echo "<p class='vw-error'>$message</p>"; } ?>
        <form method="post" action="admin_add_voter.php">
            <div class="vw-form-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="vw-form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="vw-form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <div class="vw-form-group">
                <input type="submit" class="vw-btn" value="Add Voter">
            </div>
        </form>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
