<?php
session_start();
if (!isset($_SESSION['name']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: register_login.php");
    exit();
}

require_once 'db.php'; // DB connection

// Fetch logs
$sql = "SELECT * FROM activity_logs ORDER BY timestamp DESC";
$result = $conn->query($sql);

$logs_by_date = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $date = date("M d, Y", strtotime($row['timestamp']));
        $logs_by_date[$date][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Activity Logs - ChapChapPay</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    /* Soft purple outer frame background */
    body {
      background: #8172f8;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    /* Dark inner card container wrapper */
    .app-card {
      width: 100%;
      max-width: 1200px;
      min-height: 650px;
      background-color: #061118;
      border-radius: 20px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
      border: 1px solid rgba(255, 255, 255, 0.08);
      overflow: hidden;
      position: relative;
    }

    /* Ambient background radial glow */
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

    /* Top Blue Navigation Bar */
    .navbar-blue {
      background-color: #0d6efd !important;
      padding: 15px 30px;
    }

    .navbar-blue .nav-link {
      color: rgba(255, 255, 255, 0.85);
      font-weight: 500;
      transition: color 0.2s ease;
    }

    .navbar-blue .nav-link:hover,
    .navbar-blue .nav-link.active {
      color: #ffffff;
    }

    /* Content Area */
    .dashboard-content {
      padding: 40px 30px;
      position: relative;
      z-index: 10;
    }

    .page-title {
      color: #ffffff;
      font-weight: 700;
      margin-bottom: 25px;
    }

    .date-header {
      font-weight: 700;
      font-size: 1.1rem;
      color: #8172f8;
      margin-top: 30px;
      margin-bottom: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .date-header:first-of-type {
      margin-top: 0;
    }

    /* Log Entry Card */
    .log-entry {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #ffffff;
      border-radius: 12px;
      padding: 16px 20px;
      margin-bottom: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .log-info {
      flex-grow: 1;
    }

    .username {
      font-weight: 700;
      color: #1a202c;
      font-size: 1rem;
      margin-bottom: 2px;
    }

    .activity-text {
      color: #4a5568;
      font-size: 0.95rem;
    }

    .status-badge {
      display: inline-block;
      font-size: 0.75rem;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 6px;
      margin-top: 6px;
      text-transform: uppercase;
    }

    .DONE { background: #d1e7dd; color: #0f5132; }
    .STUCK { background: #f8d7da; color: #842029; }
    .REVIEW { background: #cff4fc; color: #055160; }

    .timestamp {
      font-size: 0.88rem;
      color: #718096;
      font-weight: 600;
      white-space: nowrap;
      margin-left: 20px;
    }

    /* Blue Action Button */
    .btn-primary-custom {
      background-color: #0d6efd;
      color: #ffffff;
      font-weight: 600;
      padding: 10px 24px;
      border-radius: 8px;
      text-decoration: none;
      display: inline-block;
      transition: background 0.2s ease;
    }

    .btn-primary-custom:hover {
      background-color: #0b5ed7;
      color: #ffffff;
    }
  </style>
</head>
<body>

<div class="app-card">
  <!-- Top Blue Navbar with Admin Links -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-blue">
    <div class="container-fluid p-0">
      <a class="navbar-brand fw-bold fs-4 me-4" href="admindashboard.php">ChapChapPay</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-2">
          <li class="nav-item"><a class="nav-link" href="admindashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="view_members.php">Manage Members</a></li>
          <li class="nav-item"><a class="nav-link active" href="activity_logs.php">Activity Logs</a></li>
          <li class="nav-item"><a class="nav-link" href="view_reports.php">Reports</a></li>
          <li class="nav-item"><a class="nav-link" href="support_requests.php">Support Requests</a></li>
        </ul>
        <a href="logout.php" class="btn btn-outline-light">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Main Content Area -->
  <div class="dashboard-content">
    <h2 class="page-title">Activity Logs</h2>

    <?php if (empty($logs_by_date)): ?>
      <div class="alert alert-info rounded-3 mb-4">No activity logs recorded yet.</div>
    <?php else: ?>
      <?php foreach ($logs_by_date as $date => $logs): ?>
        <div class="date-header"><?= htmlspecialchars($date) ?></div>
        <?php foreach ($logs as $log): ?>
          <div class="log-entry">
            <div class="log-info">
              <div class="username"><?= htmlspecialchars($log['username']) ?></div>
              <div class="activity-text"><?= htmlspecialchars($log['activity']) ?></div>
              <?php if (!empty($log['status'])): ?>
                <span class="status-badge <?= htmlspecialchars(strtoupper($log['status'])) ?>">
                  <?= htmlspecialchars(strtoupper($log['status'])) ?>
                </span>
              <?php endif; ?>
            </div>
            <div class="timestamp"><?= date("g:i a", strtotime($log['timestamp'])) ?></div>
          </div>
        <?php endforeach; ?>
      <?php endforeach; ?>
    <?php endif; ?>

    <!-- Back to Dashboard Button -->
    <div class="text-center mt-4">
      <a href="admindashboard.php" class="btn-primary-custom">Back to Dashboard</a>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>