<?php
// process_contact_improved.php
require 'db.php';

// Function to sanitize user inputs
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// (Optional) reCAPTCHA validation – Uncomment if you have keys set up
/*
$recaptcha_secret = 'YOUR_SECRET_KEY';
$recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
$verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
$responseData = json_decode($verifyResponse);
if(!$responseData->success){
    die("CAPTCHA verification failed. Please try again.");
}
*/

$name    = sanitizeInput($_POST['name'] ?? '');
$email   = sanitizeInput($_POST['email'] ?? '');
$subject = sanitizeInput($_POST['subject'] ?? '');
$message = sanitizeInput($_POST['message'] ?? '');
$attachmentFileName = null;

// Process file upload if exists
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    // Create the uploads directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    // Validate file extension
    $temp = explode(".", $_FILES['attachment']['name']);
    $ext  = strtolower(end($temp));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
    if (!in_array($ext, $allowedExtensions)) {
        die("File type not allowed. Allowed types: " . implode(", ", $allowedExtensions));
    }
    // Generate a unique file name and move the file
    $attachmentFileName = uniqid('file_', true) . "." . $ext;
    $uploadFilePath = $uploadDir . $attachmentFileName;
    if (!move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadFilePath)) {
        error_log("File upload failed for file: " . $_FILES['attachment']['name']);
        die("File upload failed.");
    }
}

// Insert submission into the database using a prepared statement
$stmt = $pdo->prepare("INSERT INTO submissions (name, email, subject, message, attachment) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$name, $email, $subject, $message, $attachmentFileName]);

// Send an auto-responder email to the user
$to = $email;
$autoSubject = "Thank you for contacting us!";
$autoMessage = "Hi $name,\n\nThank you for reaching out. We have received your message and will get back to you shortly.\n\nBest regards,\nThe ContactFlow Team";
$headers = "From: no-reply@yourdomain.com";

mail($to, $autoSubject, $autoMessage, $headers);

// Redirect back to the contact form with a success flag
header("Location: contact_form.php?success=1");
exit();
?>
