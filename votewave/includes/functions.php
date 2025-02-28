<?php
function sendNotificationEmail($to, $pollTitle) {
    $subject = "New vote received for your poll: " . $pollTitle;
    $message = "A new vote has been cast in your poll. Check the results on your poll page.";
    $headers = "From: noreply@votewave.com";
    mail($to, $subject, $message, $headers);
}
?>
