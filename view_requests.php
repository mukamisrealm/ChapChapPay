<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'treasurer') {
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

// Handle approval
if (isset($_GET['approve']) || isset($_GET['reject'])) {
    $id = isset($_GET['approve']) ? $_GET['approve'] : $_GET['reject'];
    $status = isset($_GET['approve']) ? 'approved' : 'rejected';

    $stmt = $conn->prepare("UPDATE requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
}

$result = $conn->query("
    SELECT r.id, u.name, r.request_type, r.reason, r.status, r.request_date
    FROM requests r
    JOIN users u ON r.user_id = u.id
    ORDER BY r.request_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>Member Requests</h3>
    <table class="table table-bordered table-striped bg-white">
        <thead class="table-dark">
            <tr>
                <th>Member Name</th>
                <th>Type</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Requested On</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= ucfirst(str_replace('_', ' ', $row['request_type'])) ?></td>
                    <td><?= htmlspecialchars($row['reason']) ?></td>
                    <td><?= ucfirst($row['status']) ?></td>
                    <td><?= $row['request_date'] ?></td>
                    <td>
                        <?php if ($row['status'] === 'pending'): ?>
                            <a href="?approve=<?= $row['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                            <a href="?reject=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Reject</a>
                        <?php else: ?>
                            <span class="text-muted">No action</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
