<?php
session_start();

// Destroy session
session_unset();
session_destroy();

// Remove cookie
setcookie("username", "", time() - 3600);

header("Location: login.php");
exit();
?>
