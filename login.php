<?php
// Handle the form when it's submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    // Just display submitted info for now (for testing)
    echo "<h3>Login Submitted</h3>";
    echo "Email: " . htmlspecialchars($email) . "<br>";
    echo "Password: " . htmlspecialchars($password) . "<br>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>User Login</h2>
    <form method="POST" action="login.php">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
