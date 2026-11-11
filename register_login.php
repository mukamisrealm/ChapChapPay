<?php
session_start();
include 'db.php';

$error = '';
$success = '';
$mode = isset($_GET['mode']) && $_GET['mode'] === 'register' ? 'register' : 'login';

// Handle Registration (RBAC: Forced to Member role)
if ($mode === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $role = 'member'; // Hardcoded default role for public self-registration

    // Password criteria check
    $has_min_length = strlen($password) >= 8;
    $has_uppercase  = preg_match('/[A-Z]/', $password);
    $has_number     = preg_match('/[0-9]/', $password);
    $has_special    = preg_match('/[\W_]/', $password);

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (!$has_min_length || !$has_uppercase || !$has_number || !$has_special) {
        $error = "Password must be at least 8 characters long and include an uppercase letter, a number, and a special character.";
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
                $success = "Registration successful as Member! Please log in.";
                header("Location: register_login.php?mode=login");
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
        $_SESSION['role'] = strtolower($user['role']);
        $_SESSION['name'] = $user['name'];

        $stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();

        switch (strtolower($user['role'])) {
            case 'admin': header("Location: admindashboard.php"); break;
            case 'treasurer': header("Location: treasurerdashboard.php"); break;
            case 'member': header("Location: memberdashboard.php"); break;
            default: $error = "Invalid role assigned.";
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
    body { background: #8172f8; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
    .app-card { width: 100%; max-width: 900px; min-height: 550px; background-color: #061118; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45); border: 1px solid rgba(255, 255, 255, 0.08); display: flex; align-items: center; justify-content: center; padding: 40px 20px; position: relative; overflow: hidden; }
    .app-card::before { content: ''; position: absolute; top: -150px; right: -100px; width: 500px; height: 500px; background: radial-gradient(circle, rgba(111, 76, 255, 0.25) 0%, rgba(6, 17, 24, 0) 70%); pointer-events: none; }
    .box { background: #ffffff; padding: 35px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3); width: 100%; max-width: 420px; position: relative; z-index: 10; }
    h2 { text-align: center; font-weight: 800; color: #1a202c; margin-bottom: 25px; }
    .btn-primary { background-color: #007bff; border-color: #007bff; font-weight: 700; padding: 12px; border-radius: 10px; }
  </style>
</head>
<body>

<div class="app-card">
  <div class="box">
    <h2><?= $mode === 'login' ? 'Login' : 'Register' ?></h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger py-2 fs-6 rounded-3 mb-3"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <div class="alert alert-success py-2 fs-6 rounded-3 mb-3"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="register_login.php?mode=<?= $mode ?>">
      <?php if ($mode === 'register'): ?>
        <div class="mb-3">
          <label class="form-label fw-semibold text-dark">Full Name</label>
          <input type="text" name="name" class="form-control" required placeholder="John Doe">
        </div>
      <?php endif; ?>

      <div class="mb-3">
        <label class="form-label fw-semibold text-dark">Email Address</label>
        <input type="email" name="email" class="form-control" required placeholder="name@example.com">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold text-dark">Password</label>
        <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••" onkeyup="checkPasswordCriteria()">
        <?php if ($mode === 'register'): ?>
          <div id="pwd-help" class="form-text text-muted fs-7 mt-1">
            Must be 8+ chars (1 uppercase, 1 number, 1 special).
          </div>
        <?php endif; ?>
      </div>

      <?php if ($mode === 'register'): ?>
        <div class="mb-3">
          <label class="form-label fw-semibold text-dark">Confirm Password</label>
          <input type="password" name="confirm_password" class="form-control" required placeholder="••••••••">
        </div>
      <?php endif; ?>

      <button type="submit" class="btn btn-primary w-100 mb-3">
        <?= $mode === 'login' ? 'Login' : 'Register' ?>
      </button>

      <p class="text-center mt-3 mb-0 text-secondary">
        <?php if ($mode === 'login'): ?>
          Don't have an account? <a href="register_login.php?mode=register" class="fw-bold text-primary text-decoration-none">Register</a>
        <?php else: ?>
          Already have an account? <a href="register_login.php?mode=login" class="fw-bold text-primary text-decoration-none">Login</a>
        <?php endif; ?>
      </p>
    </form>
  </div>
</div>

<script>
function checkPasswordCriteria() {
  const pwdField = document.getElementById('password');
  const helpText = document.getElementById('pwd-help');
  if (!helpText) return;

  const pwd = pwdField.value;
  const minLen = pwd.length >= 8;
  const hasUpper = /[A-Z]/.test(pwd);
  const hasNum = /[0-9]/.test(pwd);
  const hasSpec = /[\W_]/.test(pwd);

  if (minLen && hasUpper && hasNum && hasSpec) {
    helpText.className = "form-text text-success fw-semibold mt-1";
    helpText.innerHTML = "✓ Strong Password";
  } else {
    helpText.className = "form-text text-muted mt-1";
    helpText.innerText = "Must be 8+ chars (1 uppercase, 1 number, 1 special).";
  }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>