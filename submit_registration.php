<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "chapchap_db");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize and fetch input
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Optional: hash password for better security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if email already exists
$check = $conn->prepare("SELECT * FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('Email already registered. Try logging in.'); window.location.href='login.php';</script>";
    exit();
}

// Insert user into the database
$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashed_password);

if ($stmt->execute()) {
    echo "<script>alert('Registration successful! You can now log in.'); window.location.href='login.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
