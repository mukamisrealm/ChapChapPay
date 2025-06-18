<?php
session_start();
if (!isset($_SESSION['role'])) {
    die("Access denied.");
}

$role = $_SESSION['role'];

echo "<h2>Welcome to ChapChapPay!</h2>";

if ($role == 'admin') {
    echo "You are an Admin. Go to <a href='admin/manage_users.php'>Manage Users</a>";
} elseif ($role == 'treasurer') {
    echo "You are a Treasurer. Go to <a href='treasurer/manage_contributions.php'>Contributions</a>";
} elseif ($role == 'member') {
    echo "You are a Member. Go to <a href='member/view_reports.php'>Your Reports</a>";
}
?>
<a href="logout.php">Logout</a>
