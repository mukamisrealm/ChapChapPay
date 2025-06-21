<?php
session_start();
if (!isset($_SESSION['name']) || $_SESSION['role'] !== 'Treasurer') {
    // Not logged in or not a Treasurer
    header("Location: login.php");
    exit();
}
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Treasurer Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap for clean UI -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">ChapChapPay</a>
    <div class="d-flex">
      <a href="../logout.php" class="btn btn-outline-light">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h2>Welcome, Treasurer <?php echo htmlspecialchars($name); ?>!</h2>

  <div class="row">
    <!-- View Members -->
    <div class="col-md-4 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">Manage Members</h5>
          <p class="card-text">View and manage member records.</p>
          <a href="../view_members.php" class="btn btn-success">View Members</a>
        </div>
      </div>
    </div>

    <!-- View Reports -->
    <div class="col-md-4 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">Reports</h5>
          <p class="card-text">Access Chama reports.</p>
          <a href="../view_reports.php" class="btn btn-success">View Reports</a>
        </div>
      </div>
    </div>

    <!-- Contribution Summary -->
    <div class="col-md-4 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">My Contribution Summary</h5>
          <p class="card-text">View your contribution records.</p>
          <a href="../contrib_summary.php" class="btn btn-success">View Contribution Summary</a>
        </div>
      </div>
    </div>

    <div class="col-md-4 mb-3">
  <div class="card text-center">
    <div class="card-body">
      <h5 class="card-title">Member Contributions</h5>
      <p class="card-text">Check contributions made by all members.</p>
      <a href="../all_contrib.php" class="btn btn-primary">View Member Contributions</a>
    </div>
  </div>
</div>


    <!-- Schedule Payouts -->
    <div class="col-md-4 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">Payouts</h5>
          <p class="card-text">Manage and schedule payouts.</p>
          <a href="../schedule_payout.php" class="btn btn-success">Schedule Payout</a>
        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>
