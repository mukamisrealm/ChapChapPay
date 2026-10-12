<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'chapchap';

// Set strict error reporting for MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $password, $dbname);
    
    // Set character set to standard UTF-8
    $conn->set_charset("utf8mb4");

    // Set database timezone to match system time (+03:00 for EAT)
    $conn->query("SET time_zone = '+03:00'");

} catch (mysqli_sql_exception $e) {
    // Log error internally and show clean error message
    error_log("Database Connection Error: " . $e->getMessage());
    die("Database connection failed. Please ensure XAMPP MySQL is running.");
}
?>