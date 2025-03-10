<?php
// public/redirect.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_GET['c'])) {
    die("No short code provided.");
}

$short_code = $_GET['c'];

// Fetch URL details from the database.
$stmt = $pdo->prepare("SELECT * FROM urls WHERE short_code = ?");
$stmt->execute([$short_code]);
$url = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$url) {
    die("URL not found.");
}

// Check expiration.
if (isExpired($url['expiration_date'])) {
    die("This URL has expired.");
}

// Password protection.
if (!empty($url['password'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'])) {
        if ($_POST['password'] !== $url['password']) {
            echo "<p>Incorrect password. Try again.</p>";
            showPasswordForm($short_code);
            exit;
        }
    } else {
        showPasswordForm($short_code);
        exit;
    }
}

// Record click and redirect.
recordClick($url['id']);
header("Location: " . $url['original_url']);
exit();

function showPasswordForm($short_code) {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
      <meta charset='UTF-8'>
      <title>Password Protected</title>
      <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
    </head>
    <body>
    <div class='container mt-5'>
      <h3>This URL is password protected.</h3>
      <form action='redirect.php?c={$short_code}' method='POST'>
        <div class='form-group'>
          <label for='password'>Enter Password</label>
          <input type='password' name='password' id='password' class='form-control' required>
        </div>
        <button type='submit' class='btn btn-primary'>Submit</button>
      </form>
    </div>
    </body>
    </html>";
}
?>
