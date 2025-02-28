<?php
// voter_logout.php
session_start();
session_destroy();
header("Location: voter_login.php");
exit;
?>
