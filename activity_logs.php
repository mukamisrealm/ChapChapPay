<?php
session_start();
if (!isset($_SESSION['name']) || $_SESSION['role'] !== 'admin') {
    header("Location: register_login.php");
    exit();
}

require_once 'db.php'; // DB connection

// Fetch logs
$sql = "SELECT * FROM activity_logs ORDER BY timestamp DESC";
$result = $conn->query($sql);

$logs_by_date = [];
while ($row = $result->fetch_assoc()) {
    $date = date("M d", strtotime($row['timestamp']));
    $logs_by_date[$date][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Activity Logs</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background:#66bcb1;
      margin: 0;
      padding: 0;
    }

    /* Navigation Bar */
    .navbar {
      background-color:#66bcb1;
      padding: 18px 40px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: white;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
    }

    .navbar h1 {
      font-size: 20px;
      margin: 0;
      color: #333;
    }

    .nav-links {
      display: flex;
      gap: 20px;
    }

    .nav-links a {
      color:#333;
      text-decoration: none;
      font-weight: bold;
      transition: color 0.2s ease;
    }

    .nav-links a:hover {
      color:darkorange;
    }

    .log-container {
      background: #1f1f2e;
      padding: 20px;
      border-radius: 10px;
      max-width: 920px;
      margin: 40px auto;
    }

    h2 {
      color: white;
    }

    .date-header {
      font-weight: bold;
      margin-top: 40px;
      color: white;
    }

    .log-entry {
      display: flex;
      justify-content: space-between;
      margin-top: 15px;
      background: rgb(165, 133, 203);
      border-radius: 10px;
      padding: 10px;
    }

    .log-info { flex-grow: 1; }
    .username { font-weight: bold; }

    .status {
      font-size: 0.85em;
      font-weight: bold;
      padding: 2px 8px;
      border-radius: 5px;
    }

    .DONE { background: #d4f5d4; color: #0a8a0a; }
    .STUCK { background: #ffe2e2; color: #c00; }
    .REVIEW { background: #e5e5ff; color: #5555ff; }

    .timestamp {
      font-size: 0.95em;
      color: #d4f5d4;
      font-weight: bold;
    }
  </style>
</head>
<body>

<!-- ✅ Top Navigation Bar -->
<div class="navbar">
  <h1>ChapChapPay </h1>
  <div class="nav-links">
    <a href="index.php">Home</a>
    <a href="admindashboard.php">Dashboard</a>
    <a href="contact_us.php">Contact</a>
    <a href="help.php">Help</a>
    <a href="logout.php">Logout</a>
  </div>
</div>

<!-- ✅ Logs Section -->
<div class="log-container">
  <h2>Activity Logs</h2>

  <?php if (empty($logs_by_date)): ?>
    <p style="color:white;">No activity logs found.</p>
  <?php else: ?>
    <?php foreach ($logs_by_date as $date => $logs): ?>
      <div class="date-header"><?= $date ?></div>
      <?php foreach ($logs as $log): ?>
        <div class="log-entry">
          <div class="log-info">
            <div class="username"><?= htmlspecialchars($log['username']) ?></div>
            <div><?= htmlspecialchars($log['activity']) ?></div>
            <?php if (!empty($log['status'])): ?>
              <div class="status <?= htmlspecialchars($log['status']) ?>">
                <?= strtoupper($log['status']) ?>
              </div>
            <?php endif; ?>
          </div>
          <div class="timestamp"><?= date("g:i a", strtotime($log['timestamp'])) ?></div>
        </div>
      <?php endforeach; ?>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

</body>
</html>
