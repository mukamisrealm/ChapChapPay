<?php
session_start();
include 'db.php';
$today = date('Y-m-d');
$conn->query("UPDATE payouts SET status = 'completed' WHERE payout_date <= '$today' AND status = 'pending'");


if (!isset($_SESSION['user_id'])) {
    header("Location: register_login.php");
    exit();
}

$role = $_SESSION['role'];
$name = $_SESSION['name'];

// Fetch payouts (show all to everyone)
$stmt = $conn->prepare("
    SELECT payouts.amount, payouts.payout_date, payouts.status, users.name 
    FROM payouts 
    JOIN users ON payouts.user_id = users.id 
    ORDER BY payouts.payout_date ASC
");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Payouts</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h3>Payout Schedule</h3>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered table-hover bg-white">
      <thead class="thead-dark">
        <tr>
          <th>Member Name</th>
          <th>Amount (KES)</th>
          <th>Payout Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo number_format($row['amount']); ?></td>
            <td><?php echo htmlspecialchars($row['payout_date']); ?></td>
            <td><?php echo ucfirst($row['status']); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No payouts scheduled yet.</div>
  <?php endif; ?>

  <div class="text-center mt-4">
    <a href="<?php echo ($role === 'treasurer') ? 'treasurerdashboard.php' : 'memberdashboard.php'; ?>" class="btn btn-secondary">Back to Dashboard</a>
  </div>
</div>

</body>
</html>
