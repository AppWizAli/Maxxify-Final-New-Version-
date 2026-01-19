<?php
session_start(); // Start the session

// Destroy all session data
$_SESSION = [];
session_unset();
session_destroy();

// Redirect to login page
header("Location: signin.php");
exit();
?>
