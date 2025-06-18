<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];

    // Fetch user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result && password_verify($password_input, $result['password'])) {
        // Correct login – set session
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['role'] = $result['role'];
        $_SESSION['name'] = $result['name'];

        // Redirect to dashboard
        if ($row['role'] == 'Member') {
            header("Location: ../dashboard/member_dashboard.php");
        } elseif ($row['role'] == 'Treasurer') {
            header("Location: ../dashboard/treasurer_dashboard.php");
        }
        exit();
    } else {
        echo "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
  <h2>User Login</h2>
  <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
  <form method="POST" action="login.php">
    <input type="email" name="email" required placeholder="Email"><br>
    <input type="password" name="password" required placeholder="Password"><br>
    <button type="submit">Login</button>
  </form>
</body>
</html>
