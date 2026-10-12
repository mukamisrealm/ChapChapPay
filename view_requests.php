<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'treasurer') {
    header("Location: register_login.php");
    exit();
}

$role = $_SESSION['role'];

// Handle approval / rejection
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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Requests - ChapChapPay</title>
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

    /* Soft purple outer frame background matching system theme */
    body {
      background: #8172f8;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    /* Dark inner card container matching theme */
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

    /* Clean white table styling */
    .table-container {
      background: #ffffff;
      border-radius: 12px;
      padding: 0;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .custom-table {
      margin-bottom: 0;
    }

    .custom-table th {
      background-color: #f8f9fa;
      color: #1a202c;
      font-weight: 700;
      border-bottom: 2px solid #dee2e6;
      padding: 15px 20px;
    }

    .custom-table td {
      padding: 15px 20px;
      color: #2d3748;
      vertical-align: middle;
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
  <!-- Top Blue Navbar with Full Activity Links -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-blue">
    <div class="container-fluid p-0">
      <a class="navbar-brand fw-bold fs-4 me-4" href="treasurerdashboard.php">ChapChapPay</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-2">
          <li class="nav-item"><a class="nav-link" href="treasurerdashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="view_members.php">Manage Members</a></li>
          <li class="nav-item"><a class="nav-link" href="add_fine.php">Add Fine</a></li>
          <li class="nav-item"><a class="nav-link" href="view_fine.php">View Fines</a></li>
          <li class="nav-item"><a class="nav-link" href="view_reports.php">Reports</a></li>
          <li class="nav-item"><a class="nav-link active" href="view_requests.php">Requests</a></li>
          <li class="nav-item"><a class="nav-link" href="add_contrib.php">Add Contribution</a></li>
          <li class="nav-item"><a class="nav-link" href="schedule_payout.php">Payouts</a></li>
        </ul>
        <a href="logout.php" class="btn btn-outline-light">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="dashboard-content">
    <h2 class="page-title">Member Requests</h2>

    <?php if ($result && $result->num_rows > 0): ?>
      <div class="table-container mb-4">
        <table class="table custom-table table-hover">
          <thead>
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
                <td><?= ucfirst(str_replace('_', ' ', htmlspecialchars($row['request_type']))) ?></td>
                <td><?= htmlspecialchars($row['reason']) ?></td>
                <td>
                  <?php if ($row['status'] === 'approved'): ?>
                    <span class="badge bg-success">Approved</span>
                  <?php elseif ($row['status'] === 'rejected'): ?>
                    <span class="badge bg-danger">Rejected</span>
                  <?php else: ?>
                    <span class="badge bg-warning text-dark">Pending</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['request_date']) ?></td>
                <td>
                  <?php if ($row['status'] === 'pending'): ?>
                    <a href="?approve=<?= $row['id'] ?>" class="btn btn-success btn-sm me-1">Approve</a>
                    <a href="?reject=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Reject</a>
                  <?php else: ?>
                    <span class="text-muted small">No action</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info rounded-3 mb-4">No member requests submitted yet.</div>
    <?php endif; ?>

    <!-- Back to Dashboard Button -->
    <div class="text-center mt-4">
      <a href="treasurerdashboard.php" class="btn-primary-custom">Back to Dashboard</a>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>