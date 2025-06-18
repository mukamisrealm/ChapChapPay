<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // secure hash
    $role     = $_POST['role'];

    // Prepare the insert statement
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        echo "<script>alert('User registered successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Registration failed: " . $conn->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
  <h2>Register New User</h2>
  <form method="POST" action="register.php">
    <input type="text" name="name" required placeholder="Name"><br>
    <input type="email" name="email" required placeholder="Email"><br>
    <input type="password" name="password" required placeholder="Password"><br>
    <select name="role" required>
      <option value="admin">Admin</option>
      <option value="treasurer">Treasurer</option>
      <option value="member">Member</option>
    </select><br>
    <button type="submit">Register</button>
  </form>
</body>
</html>


