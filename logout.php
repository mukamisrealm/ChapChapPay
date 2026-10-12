<?php
session_start();
include 'db.php';

// Log the logout activity if user details exist in the session
if (isset($_SESSION['name']) && !empty($_SESSION['name'])) {
    $username = $_SESSION['name'];
    $activity = "Logged out of the system";
    $status = "DONE";

    // Insert logout entry into activity_logs using current system timestamp
    $stmt = $conn->prepare("INSERT INTO activity_logs (username, activity, status, timestamp) VALUES (?, ?, ?, NOW())");
    if ($stmt) {
        $stmt->bind_param("sss", $username, $activity, $status);
        $stmt->execute();
        $stmt->close();
    }
}

// Clear and destroy session
session_unset();
session_destroy();

// Redirect to login page
header("Location: register_login.php");
exit();
?>