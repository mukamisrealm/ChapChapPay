<?php
// Start session first thing
session_start();

// Include DB connection
include 'db.php';

// Initialize error message
$error = '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];

    // Fetch user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result && password_verify($password_input, $result['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['role'] = $result['role'];
        $_SESSION['name'] = $result['name'];

        // Redirect based on role
        if ($result['role'] === 'Member') {
            header("Location: memberdashboard.php");
            exit();
        } elseif ($result['role'] === 'Treasurer') {
            header("Location: treasurerdashboard.php");
            exit();
        } elseif ($result['role'] === 'admin') {
            header("Location: admindashboard.php");
            exit();
        } else {
            $error = "Unknown role. Contact system admin.";
        }
    } else {
        $error = "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - ChapChapPay</title>
</head>
<body>
    <h2>User Login</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="login.php">
        <input type="email" name="email" required placeholder="Email"><br>
        <input type="password" name="password" required placeholder="Password"><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>

