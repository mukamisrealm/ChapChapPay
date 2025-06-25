<?php
// login.php
session_start();
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result && password_verify($password_input, $result['password'])) {
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['role'] = $result['role'];

        // Redirect based on role
        switch ($result['role']) {
            case 'admin':
                header("Location: admindashboard.php");
                break;
            case 'treasurer':
                header("Location: treasurerdashboard.php");
                break;
            case 'member':
                header("Location: memberdashboard.php");
                break;
            default:
                $error = "Unknown user role. Contact administrator.";
        }
        exit();
    } else {
        $error = "Invalid credentials. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ChapChapPay - Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('images/background.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h3 class="text-center mb-4">Login to ChapChapPay</h3>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="email">Email address:</label>
            <input type="email" name="email" class="form-control" required placeholder="Enter your email">
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" class="form-control" required placeholder="Enter your password">
        </div>

        <button type="submit" class="btn btn-primary btn-block">Login</button>

        <p class="text-center mt-3">
            Don't have an account? <a href="registration.php">Register</a>
        </p>
    </form>
</div>

</body>
</html>
