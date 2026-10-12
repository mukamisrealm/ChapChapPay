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
  <title>Administrator Dashboard - ChapChapPay</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    body {
      background: #8172f8;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .app-card {
      width: 100%;
      max-width: 1200px;
      background-color: #061118;
      border-radius: 20px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
      border: 1px solid rgba(255, 255, 255, 0.08);
      overflow: hidden;
      position: relative;
    }

    .app-card::before {
      content: '';
      position: absolute;
      top: -150px;
      right: -100px;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(111, 76, 255, 0.2) 0%, rgba(6, 17, 24, 0) 70%);
      pointer-events: none;
    }

    .navbar-dark-custom {
      background-color: #212529 !important;
      padding: 15px 30px;
    }

    .dashboard-content {
      padding: 30px;
      position: relative;
      z-index: 10;
    }

    .welcome-text {
      color: #ffffff;
      font-weight: 700;
      margin-bottom: 25px;
    }

    .card-widget {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      border: none;
    }

    .card-widget .card-title {
      font-weight: 700;
      color: #1a202c;
    }

    .card-widget .card-text {
      color: #4a5568;
    }
  </style>
</head>
<body>

<div class="app-card">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-dark-custom">
    <div class="container-fluid p-0">
      <a class="navbar-brand fw-bold fs-4" href="#">ChapChapPay</a>
      <div class="d-flex align-items-center gap-2">
        <a href="index.php" class="btn btn-outline-light me-2">Home</a>
        <a href="logout.php" class="btn btn-outline-light">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Dashboard Content -->
  <div class="dashboard-content">
    <h2 class="welcome-text">Welcome, Administrator <?php echo htmlspecialchars($name); ?>!</h2>

    <div class="row">
      <!-- System Health -->
      <div class="col-md-4 mb-4">
        <div class="card text-center card-widget h-100">
          <div class="card-body d-flex flex-column justify-content-between p-4">
            <div>
              <h5 class="card-title fs-5 mb-2">System Health</h5>
              <p class="card-text mb-4">Monitor system operations and server settings.</p>
            </div>
            <div><a href="system_health.php" class="btn btn-dark px-4">View System Status</a></div>
          </div>
        </div>
      </div>

      <!-- Data Backup/Restore -->
      <div class="col-md-4 mb-4">
        <div class="card text-center card-widget h-100">
          <div class="card-body d-flex flex-column justify-content-between p-4">
            <div>
              <h5 class="card-title fs-5 mb-2">Data Backup</h5>
              <p class="card-text mb-4">Manage system backups and restore database data.</p>
            </div>
            <div><a href="backup_restore.php" class="btn btn-dark px-4">Backup / Restore</a></div>
          </div>
        </div>
      </div>

      <!-- Activity Monitoring -->
      <div class="col-md-4 mb-4">
        <div class="card text-center card-widget h-100">
          <div class="card-body d-flex flex-column justify-content-between p-4">
            <div>
              <h5 class="card-title fs-5 mb-2">Activity Logs</h5>
              <p class="card-text mb-4">View user activity and administrative system logs.</p>
            </div>
            <div><a href="activity_logs.php" class="btn btn-dark px-4">View Logs</a></div>
          </div>
        </div>
      </div>

      <!-- Clean Old Data -->
      <div class="col-md-4 mb-4">
        <div class="card text-center card-widget h-100">
          <div class="card-body d-flex flex-column justify-content-between p-4">
            <div>
              <h5 class="card-title fs-5 mb-2">Clean Old Data</h5>
              <p class="card-text mb-4">Remove outdated audit and session records securely.</p>
            </div>
            <div><a href="cleanup.php" class="btn btn-dark px-4">Clean Data</a></div>
          </div>
        </div>
      </div>

      <!-- Support Requests -->
      <div class="col-md-4 mb-4">
        <div class="card text-center card-widget h-100">
          <div class="card-body d-flex flex-column justify-content-between p-4">
            <div>
              <h5 class="card-title fs-5 mb-2">Support Requests</h5>
              <p class="card-text mb-4">Help users facing login or system submission issues.</p>
            </div>
            <div><a href="support_requests.php" class="btn btn-dark px-4">View Support Cases</a></div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>