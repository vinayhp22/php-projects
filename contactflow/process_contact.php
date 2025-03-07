<?php
require 'vendor/autoload.php';
require 'db.php';

/**
 * Sanitize input data.
 */
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// --- reCAPTCHA v2 verification ---
$secretKey = "6LfsTewqAAAAAARJ1Rp-y6t0fA1OmagYpXH_EhxM";  // Replace with your secret key.
$responseToken = $_POST['g-recaptcha-response'] ?? "";
$remoteip = $_SERVER['REMOTE_ADDR'];

// Verify the token by sending a request to Google.
$verifyUrl = "https://www.google.com/recaptcha/api/siteverify?secret=" . urlencode($secretKey) 
           . "&response=" . urlencode($responseToken) 
           . "&remoteip=" . urlencode($remoteip);
$verifyResponse = file_get_contents($verifyUrl);
$responseData = json_decode($verifyResponse);

if (!$responseData->success) {
    die("reCAPTCHA verification failed. Please try again.");
}

// --- Continue with form processing ---

$name    = sanitizeInput($_POST['name'] ?? '');
$email   = sanitizeInput($_POST['email'] ?? '');
$subject = sanitizeInput($_POST['subject'] ?? '');
$message = sanitizeInput($_POST['message'] ?? '');
$attachmentFileName = null;

// Process file upload if provided.
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $temp = explode(".", $_FILES['attachment']['name']);
    $ext  = strtolower(end($temp));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
    if (!in_array($ext, $allowedExtensions)) {
        die("File type not allowed. Allowed types: " . implode(", ", $allowedExtensions));
    }
    $attachmentFileName = uniqid('file_', true) . "." . $ext;
    $uploadFilePath = $uploadDir . $attachmentFileName;
    if (!move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadFilePath)) {
        error_log("File upload failed for file: " . $_FILES['attachment']['name']);
        die("File upload failed.");
    }
}

// Insert the submission into the database.
$stmt = $pdo->prepare("INSERT INTO submissions (name, email, subject, message, attachment) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$name, $email, $subject, $message, $attachmentFileName]);

// Send an auto-responder email.
$to = $email;
$autoSubject = "Thank you for contacting us!";
$autoMessage = "Hi $name,\n\nThank you for reaching out. We have received your message and will get back to you shortly.\n\nBest regards,\nThe ContactFlow Team";
$headers = "From: no-reply@yourdomain.com";
mail($to, $autoSubject, $autoMessage, $headers);

// Redirect back to the contact form with a success flag.
header("Location: contact_form.php?success=1");
exit();
