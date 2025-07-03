<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: register_login.php");
    exit();
}
$role = $_SESSION['role'];
if ($role === 'treasurer') {
    include 'navbar_treasurer.php';
} elseif ($role === 'member') {
    include 'navbar_member.php';
} elseif ($role === 'admin') {
    include 'navbar_admin.php';
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$name = $_SESSION['name'] ?? '';

// Use different query depending on role
if ($role === 'member') {
    $stmt = $conn->prepare("SELECT amount, contribution_date, description FROM contributions WHERE user_id = ? ORDER BY contribution_date DESC");
    $stmt->bind_param("i", $user_id);
} else {
    // Admin or Treasurer
    $stmt = $conn->prepare("SELECT u.name, c.amount, c.contribution_date, c.description 
                            FROM contributions c 
                            JOIN users u ON c.user_id = u.id 
                            ORDER BY c.contribution_date DESC");
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Contributions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Contribution History</h2>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered table-hover mt-4 bg-white">
            <thead class="thead-dark">
            <tr>
                <?php if ($role !== 'member') echo "<th>Member Name</th>"; ?>
                <th>Amount (KES)</th>
                <th>Date</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <?php if ($role !== 'member') echo "<td>" . htmlspecialchars($row['name']) . "</td>"; ?>
                    <td><?php echo htmlspecialchars($row['amount']); ?></td>
                    <td><?php echo htmlspecialchars($row['contribution_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No contributions found.</div>
    <?php endif; ?>

    <div class="mt-4 text-center">
        <a href="<?php echo ($role === 'treasurer') ? 'treasurerdashboard.php' : (($role === 'admin') ? 'admindashboard.php' : 'memberdashboard.php'); ?>" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>
</body>
</html>

