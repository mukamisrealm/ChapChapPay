<?php
session_start();
if (!isset($_SESSION['name']) || $_SESSION['role'] !== 'admin') {
    header("Location: register_login.php");
    exit();
}
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Administrator Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap for clean UI -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">ChapChapPay</a>
    <a href="index.php" class="btn btn-outline-light">Home</a>
    <div class="d-flex">
      <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h2>Welcome, Administrator <?php echo htmlspecialchars($name); ?>!</h2>

  <div class="row mt-4">
    <!-- System Health -->
    <div class="col-md-4 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">System Health</h5>
          <p class="card-text">Monitor system operations and settings.</p>
          <a href="system_health.php" class="btn btn-dark">View System Status</a>
        </div>
      </div>
    </div>

    <!-- Data Backup/Restore -->
    <div class="col-md-4 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">Data Backup</h5>
          <p class="card-text">Manage system backups and restore data.</p>
          <a href="backup_restore.php" class="btn btn-dark">Backup / Restore</a>
        </div>
      </div>
    </div>

    <!-- Activity Monitoring -->
    <div class="col-md-4 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">Activity Logs</h5>
          <p class="card-text">View user activity and system logs.</p>
          <a href="activity_logs.php" class="btn btn-dark">View Logs</a>
        </div>
      </div>
    </div>

    <!-- Clean Old Data -->
    <div class="col-md-4 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">Clean Old Data</h5>
          <p class="card-text">Remove outdated records securely.</p>
          <a href="cleanup.php" class="btn btn-dark">Clean Data</a>
        </div>
      </div>
    </div>

    <!-- Assist Users -->
    <div class="col-md-4 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">Support Requests</h5>
          <p class="card-text">Help users facing system issues.</p>
          <a href="support_requests.php" class="btn btn-dark">View Support Cases</a>
        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>
