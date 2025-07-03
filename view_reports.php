<?php
session_start();
include 'db.php';

// Check if user is logged in
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

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Reports</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4">📊 Chama Report</h2>

  <!-- Contributions Summary -->
  <div class="card mb-4">
    <div class="card-header bg-success text-white">💰 Contributions Summary</div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead><tr><th>Name</th><th>Amount</th><th>Date</th><th>Description</th></tr></thead>
        <tbody>
        <?php
          $sql = "SELECT users.name, amount, contribution_date, description FROM contributions JOIN users ON contributions.user_id = users.id ORDER BY contribution_date DESC";
          $res = $conn->query($sql);
          while($row = $res->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['amount']) ?></td>
              <td><?= htmlspecialchars($row['contribution_date']) ?></td>
              <td><?= htmlspecialchars($row['description']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Fines Summary -->
  <div class="card mb-4">
    <div class="card-header bg-danger text-white">⚠️ Fines Summary</div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead><tr><th>Name</th><th>Amount</th><th>Date</th><th>Reason</th></tr></thead>
        <tbody>
        <?php
          $sql = "SELECT users.name, amount, fine_date, reason FROM fines JOIN users ON fines.user_id = users.id ORDER BY fine_date DESC";
          $res = $conn->query($sql);
          while($row = $res->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['amount']) ?></td>
              <td><?= htmlspecialchars($row['fine_date']) ?></td>
              <td><?= htmlspecialchars($row['reason']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Payout Schedule -->
  <div class="card mb-4">
    <div class="card-header bg-primary text-white">📤 Payout Schedule</div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead><tr><th>Name</th><th>Payout Date</th><th>Amount</th><th>Status</th></tr></thead>
        <tbody>
        <?php
          $sql = "SELECT users.name, payout_date, amount, status FROM payouts JOIN users ON payouts.user_id = users.id ORDER BY payout_date ASC";
          $res = $conn->query($sql);
          while($row = $res->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['payout_date']) ?></td>
              <td><?= htmlspecialchars($row['amount']) ?></td>
              <td><?= htmlspecialchars($row['status']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Member Requests -->
  <div class="card mb-4">
    <div class="card-header bg-warning text-dark">📌 Member Requests</div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead><tr><th>Name</th><th>Request Type</th><th>Date</th><th>Status</th></tr></thead>
        <tbody>
        <?php
          $sql = "SELECT users.name, request_type, request_date, status FROM requests JOIN users ON requests.user_id = users.id ORDER BY request_date DESC";
          $res = $conn->query($sql);
          while($row = $res->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['request_type']) ?></td>
              <td><?= htmlspecialchars($row['request_date']) ?></td>
              <td><?= htmlspecialchars($row['status']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="text-center">
    <a href="<?php echo ($role === 'treasurer') ? 'treasurerdashboard.php' : 'memberdashboard.php'; ?>" class="btn btn-secondary">Back to Dashboard</a>
  </div>
</div>

</body>
</html>

