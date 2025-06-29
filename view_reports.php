<?php
session_start();
include 'db.php';

// Restrict if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: register_login.php");
    exit();
}

$role = $_SESSION['role'];
$name = $_SESSION['name'];

// Fetch contributions summary
$contribs = $conn->query("SELECT users.name, SUM(contributions.amount) AS total_amount FROM contributions JOIN users ON contributions.user_id = users.id GROUP BY users.id");

// Fetch fines summary
$fines = $conn->query("SELECT users.name, SUM(fines.amount) AS total_fines FROM fines JOIN users ON fines.user_id = users.id GROUP BY users.id");

// Fetch payouts summary
$payouts = $conn->query("SELECT users.name, payout_date, amount, status FROM payouts JOIN users ON payouts.user_id = users.id ORDER BY payout_date ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reports Summary</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4">📊 System Reports</h2>
  <p>Welcome, <?php echo htmlspecialchars($name); ?> (<?php echo $role; ?>)</p>

  <!-- Contributions Report -->
  <div class="card mb-4">
    <div class="card-header bg-primary text-white">💰 Contributions Summary</div>
    <div class="card-body">
      <table class="table table-bordered table-hover">
        <thead class="table-light">
          <tr><th>Member</th><th>Total Contributions (KES)</th></tr>
        </thead>
        <tbody>
          <?php while ($row = $contribs->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo number_format($row['total_amount']); ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Fines Report -->
  <div class="card mb-4">
    <div class="card-header bg-warning">⚠️ Fines Summary</div>
    <div class="card-body">
      <table class="table table-bordered table-hover">
        <thead class="table-light">
          <tr><th>Member</th><th>Total Fines (KES)</th></tr>
        </thead>
        <tbody>
          <?php while ($row = $fines->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo number_format($row['total_fines']); ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Payout Schedule -->
  <div class="card mb-4">
    <div class="card-header bg-success text-white">📅 Payout Schedule</div>
    <div class="card-body">
      <table class="table table-bordered table-hover">
        <thead class="table-light">
          <tr><th>Member</th><th>Payout Date</th><th>Amount (KES)</th><th>Status</th></tr>
        </thead>
        <tbody>
          <?php while ($row = $payouts->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['payout_date']); ?></td>
            <td><?php echo number_format($row['amount']); ?></td>
            <td><?php echo ucfirst($row['status']); ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="text-center">
    <a href="<?php echo ($role === 'treasurer') ? 'treasurerdashboard.php' : 'memberdashboard.php'; ?>" class="btn btn-secondary">⬅ Back to Dashboard</a>
  </div>
</div>

</body>
</html>
