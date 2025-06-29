<?php
session_start();
include 'db.php';

$error = '';
$success = '';
$mode = isset($_GET['mode']) && $_GET['mode'] === 'register' ? 'register' : 'login';

// Handle Registration
if ($mode === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $role = $_POST['role'];

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashed, $role);

            if ($stmt->execute()) {
                $success = "Registration successful. Please log in.";
                header("Location: auth.php?mode=login");
                exit();
            } else {
                $error = "Registration failed.";
            }
        }
    }
}

// Handle Login
if ($mode === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        switch ($user['role']) {
            case 'admin': header("Location: admindashboard.php"); break;
            case 'treasurer': header("Location: treasurerdashboard.php"); break;
            case 'member': header("Location: memberdashboard.php"); break;
            default: $error = "Invalid role.";
        }
        exit();
    } else {
        $error = "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= ucfirst($mode) ?> | ChapChapPay</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #87CEEB;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .box {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 400px;
    }
    h2 { text-align: center; }
  </style>
</head>
<body>

<div class="box">
  <h2><?= $mode === 'login' ? 'Login' : 'Register' ?></h2>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST" action="register_login.php?mode=<?= $mode ?>">
    <?php if ($mode === 'register'): ?>
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
    <?php endif; ?>

    <div class="form-group">
      <label>Email Address</label>
      <input type="email" name="email" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <?php if ($mode === 'register'): ?>
      <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Select Role</label>
        <select name="role" class="form-control" required>
          <option value="member">Member</option>
          <option value="treasurer">Treasurer</option>
          <option value="admin">Admin</option>
        </select>
      </div>
    <?php endif; ?>

    <button type="submit" class="btn btn-primary btn-block">
      <?= $mode === 'login' ? 'Login' : 'Register' ?>
    </button>

    <p class="text-center mt-3">
      <?php if ($mode === 'login'): ?>
        Don't have an account? <a href="register_login.php?mode=register">Register</a>
      <?php else: ?>
        Already have an account? <a href="register_login.php?mode=login">Login</a>
      <?php endif; ?>
    </p>
  </form>
</div>

</body>
</html>
