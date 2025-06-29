<?php
session_start();
include 'db.php'; // make sure this exists and is correct

$error = '';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "✅ Form submitted<br>";

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
                $error = "Unknown role.";
        }
        exit();
    } else {
        $error = "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ChapChapPay - Login</title>
</head>
<body>

<h2>Login</h2>

<?php if (!empty($error)) : ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST" action="login.php">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>

<p><a href="register.php">Don't have an account? Register</a></p>

</body>
</html>


