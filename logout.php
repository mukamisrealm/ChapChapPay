<?php
session_start();          // Start session (to destroy it)
session_unset();          // Unset all session variables
session_destroy();        // Destroy the session

header("Location: register_login.php");  // Redirect to login
exit();                   // Make sure no other code runs
?>
