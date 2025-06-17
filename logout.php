<?php
session_start();
//to unset all session variables
$SESSION = array();
//to destroy the session
session_destroy();
//then redirects user to login page
header("Location: ../index.php");
exit();
?>