<?php
session_start();
include 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Check if passwords match
    if ($password_input !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            // Hash password
            $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                // Redirect to login page after successful registration
                header("Location: login.php");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ChapChapPay - Registration</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #87CEEB;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .register-box {
      background: white;
      padding: 25px 30px;
      border-radius: 10px;
      box-shadow: 0px 0px 15px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 400px;
    }

    .register-box h2 {
      margin-bottom: 20px;
      text-align: center;
    }
  </style>
</head>
<body>

  <div class="register-box">
    <h2>User Registration</h2>

    <?php if (!empty($error)) : ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
      <div class="form-group">
        <label for="name">Full Name:</label>
        <input type="text" class="form-control" id="name" name="name" required>
      </div>

      <div class="form-group">
        <label for="email">Email Address:</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>

      <div class="form-group">
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
      </div>

      <div class="form-group">
        <label for="role">Select Role:</label>
        <select class="form-control" id="role" name="role" required>
          <option value="member">Member</option>
          <option value="treasurer">Treasurer</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary btn-block">Register</button>
      <p class="mt-3 text-center"><a href="login.php">Already have an account? Login</a></p>
    </form>
  </div>

</body>
</html>

