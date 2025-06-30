<?php
// submit_requestadm.php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO support_requests (user_id, subject, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $subject, $message);
    $stmt->execute();

    // Redirect or confirm
    header("Location: " . $_SERVER['HTTP_REFERER']); // Goes back to previous page
    exit();
} else {
    echo "Invalid request method.";
}
?>
