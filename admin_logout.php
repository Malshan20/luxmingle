<?php
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the login page or home page
header("Location: admin_signin.php"); // Redirect to the sign-in page
exit;
?>
