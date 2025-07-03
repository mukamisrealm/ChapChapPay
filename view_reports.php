<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
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

// Contributions
if ($role === 'member') {
    $contrib_stmt = $conn->prepare("SELECT amount, contribution_date, description FROM contributions WHERE user_id = ? ORDER BY contribution_date DESC");
    $contrib_stmt->bind_param("i", $user_id);
} else {
    $contrib_stmt = $conn->prepare("SELECT users.name, amount, contribution_date, description FROM contributions JOIN users ON contributions.user_id = users.id ORDER BY contribution_date DESC");
}
$contrib_stmt->execute();
$contributions = $contrib_stmt->get_result();

// Fines
if ($role === 'member') {
    $fine_stmt = $conn->prepare("SELECT amount, fine_date, reason FROM fines WHERE user_id = ? ORDER BY fine_date DESC");
    $fine_stmt->bind_param("i", $user_id);
} else {
    $fine_stmt = $conn->prepare("SELECT users.name, amount, fine_date, reason FROM fines JOIN users ON fines.user_id = users.id ORDER BY fine_date DESC");
}
$fine_stmt->execute();
$fines = $fine_stmt->get_result();

// Payout Schedule
if ($role === 'member') {
    $payout_stmt = $conn->prepare("SELECT amount, payout_date, status FROM payouts WHERE user_id = ? ORDER BY payout_date ASC");
    $payout_stmt->bind_param("i", $user_id);
} else {
    $payout_stmt = $conn->prepare("SELECT users.name, amount, payout_date, status FROM payouts JOIN users ON payouts.user_id = users.id ORDER BY payout_date ASC");
}
$payout_stmt->execute();
$payouts = $payout_stmt->get_result();

// Member Requests
if ($role === 'member') {
    $request_stmt = $conn->prepare("SELECT request_type, reason, request_date, status FROM member_request WHERE user_id = ? ORDER BY request_date DESC");
    $request_stmt->bind_param("i", $user_id);
} else {
    $request_stmt = $conn->prepare("SELECT users.name, request_type, reason, request_date, status FROM member_request JOIN users ON member_request.user_id = users.id ORDER BY request_date DESC");
}
$request_stmt->execute();
$requests = $request_stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chama Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2 class="mb-4">Chama Reports</h2>

    <!-- Contributions Section -->
    <h4>Contributions</h4>
    <?php if ($contributions->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <?php if ($role !== 'member') echo "<th>Name</th>"; ?>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $contributions->fetch_assoc()): ?>
                    <tr>
                        <?php if ($role !== 'member') echo "<td>" . htmlspecialchars($row['name']) . "</td>"; ?>
                        <td><?= htmlspecialchars($row['amount']) ?></td>
                        <td><?= htmlspecialchars($row['contribution_date']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No contributions found.</div>
    <?php endif; ?>

    <!-- Fines Section -->
    <h4 class="mt-5">Fines</h4>
    <?php if ($fines->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <?php if ($role !== 'member') echo "<th>Name</th>"; ?>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $fines->fetch_assoc()): ?>
                    <tr>
                        <?php if ($role !== 'member') echo "<td>" . htmlspecialchars($row['name']) . "</td>"; ?>
                        <td><?= htmlspecialchars($row['amount']) ?></td>
                        <td><?= htmlspecialchars($row['fine_date']) ?></td>
                        <td><?= htmlspecialchars($row['reason']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No fines found.</div>
    <?php endif; ?>

    <!-- Payout Schedule Section -->
    <h4 class="mt-5">Payout Schedule</h4>
    <?php if ($payouts->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <?php if ($role !== 'member') echo "<th>Name</th>"; ?>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $payouts->fetch_assoc()): ?>
                    <tr>
                        <?php if ($role !== 'member') echo "<td>" . htmlspecialchars($row['name']) . "</td>"; ?>
                        <td><?= htmlspecialchars($row['amount']) ?></td>
                        <td><?= htmlspecialchars($row['payout_date']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No payouts scheduled.</div>
    <?php endif; ?>

    <!-- Requests Section -->
    <h4 class="mt-5">Member Requests</h4>
    <?php if ($requests->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <?php if ($role !== 'member') echo "<th>Name</th>"; ?>
                    <th>Request Type</th>
                    <th>Reason</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $requests->fetch_assoc()): ?>
                    <tr>
                        <?php if ($role !== 'member') echo "<td>" . htmlspecialchars($row['name']) . "</td>"; ?>
                        <td><?= htmlspecialchars($row['request_type']) ?></td>
                        <td><?= htmlspecialchars($row['reason']) ?></td>
                        <td><?= htmlspecialchars($row['request_date']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No requests submitted.</div>
    <?php endif; ?>

</div>
</body>
</html>


