<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: register_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Contributions Query
if ($role === 'member') {
    $contrib_stmt = $conn->prepare("SELECT amount, contribution_date, description FROM contributions WHERE user_id = ? ORDER BY contribution_date DESC");
    $contrib_stmt->bind_param("i", $user_id);
} else {
    $contrib_stmt = $conn->prepare("SELECT users.name, amount, contribution_date, description FROM contributions JOIN users ON contributions.user_id = users.id ORDER BY contribution_date DESC");
}
$contrib_stmt->execute();
$contributions = $contrib_stmt->get_result();

// Fines Query
if ($role === 'member') {
    $fine_stmt = $conn->prepare("SELECT amount, fine_date, reason FROM fines WHERE user_id = ? ORDER BY fine_date DESC");
    $fine_stmt->bind_param("i", $user_id);
} else {
    $fine_stmt = $conn->prepare("SELECT users.name, amount, fine_date, reason FROM fines JOIN users ON fines.user_id = users.id ORDER BY fine_date DESC");
}
$fine_stmt->execute();
$fines = $fine_stmt->get_result();

// Payout Schedule Query
if ($role === 'member') {
    $payout_stmt = $conn->prepare("SELECT amount, payout_date, status FROM payouts WHERE user_id = ? ORDER BY payout_date ASC");
    $payout_stmt->bind_param("i", $user_id);
} else {
    $payout_stmt = $conn->prepare("SELECT users.name, amount, payout_date, status FROM payouts JOIN users ON payouts.user_id = users.id ORDER BY payout_date ASC");
}
$payout_stmt->execute();
$payouts = $payout_stmt->get_result();

// Member Requests Query
if ($role === 'member') {
    $request_stmt = $conn->prepare("SELECT request_type, reason, request_date, status FROM requests WHERE user_id = ? ORDER BY request_date DESC");
    $request_stmt->bind_param("i", $user_id);
} else {
    $request_stmt = $conn->prepare("SELECT users.name, request_type, reason, request_date, status FROM requests JOIN users ON requests.user_id = users.id ORDER BY request_date DESC");
}
$request_stmt->execute();
$requests = $request_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Chama Reports - ChapChapPay</title>
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

    /* Dark inner card container wrapper */
    .app-card {
      width: 100%;
      max-width: 1200px;
      min-height: 700px;
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

    /* Preserved Blue Top Navigation Bar */
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
      padding: 30px 40px 40px;
      position: relative;
      z-index: 10;
    }

    .page-title {
      color: #ffffff;
      font-weight: 700;
      margin-bottom: 30px;
    }

    .section-title {
      color: #ffffff;
      font-weight: 600;
      font-size: 1.25rem;
      margin-top: 30px;
      margin-bottom: 15px;
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
      padding: 12px 18px;
    }

    .custom-table td {
      padding: 12px 18px;
      color: #2d3748;
      vertical-align: middle;
    }

    /* Return button style */
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
  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-blue">
    <div class="container-fluid p-0">
      <a class="navbar-brand fw-bold fs-4 me-4" href="#">ChapChapPay</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-2">
          <?php if ($role === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="admindashboard.php">Dashboard</a></li>
          <?php elseif ($role === 'treasurer'): ?>
            <li class="nav-item"><a class="nav-link" href="treasurerdashboard.php">Dashboard</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="memberdashboard.php">Dashboard</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link active" href="view_reports.php">Reports</a></li>
        </ul>
        <a href="logout.php" class="btn btn-outline-light">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="dashboard-content">
    <h2 class="page-title">Chama Reports</h2>

    <!-- Contributions Section -->
    <h4 class="section-title">Contributions</h4>
    <?php if ($contributions && $contributions->num_rows > 0): ?>
      <div class="table-container mb-4">
        <table class="table custom-table table-hover">
          <thead>
            <tr>
              <?php if ($role !== 'member') echo "<th>Name</th>"; ?>
              <th>Amount (KES)</th>
              <th>Date</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $contributions->fetch_assoc()): ?>
              <tr>
                <?php if ($role !== 'member') echo "<td>" . htmlspecialchars($row['name']) . "</td>"; ?>
                <td><?= number_format($row['amount'], 2) ?></td>
                <td><?= htmlspecialchars($row['contribution_date']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info rounded-3 mb-4">No contributions found.</div>
    <?php endif; ?>

    <!-- Fines Section -->
    <h4 class="section-title">Fines</h4>
    <?php if ($fines && $fines->num_rows > 0): ?>
      <div class="table-container mb-4">
        <table class="table custom-table table-hover">
          <thead>
            <tr>
              <?php if ($role !== 'member') echo "<th>Name</th>"; ?>
              <th>Amount (KES)</th>
              <th>Date</th>
              <th>Reason</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $fines->fetch_assoc()): ?>
              <tr>
                <?php if ($role !== 'member') echo "<td>" . htmlspecialchars($row['name']) . "</td>"; ?>
                <td><?= number_format($row['amount'], 2) ?></td>
                <td><?= htmlspecialchars($row['fine_date']) ?></td>
                <td><?= htmlspecialchars($row['reason']) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info rounded-3 mb-4">No fines found.</div>
    <?php endif; ?>

    <!-- Payout Schedule Section -->
    <h4 class="section-title">Payout Schedule</h4>
    <?php if ($payouts && $payouts->num_rows > 0): ?>
      <div class="table-container mb-4">
        <table class="table custom-table table-hover">
          <thead>
            <tr>
              <?php if ($role !== 'member') echo "<th>Name</th>"; ?>
              <th>Amount (KES)</th>
              <th>Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $payouts->fetch_assoc()): ?>
              <tr>
                <?php if ($role !== 'member') echo "<td>" . htmlspecialchars($row['name']) . "</td>"; ?>
                <td><?= number_format($row['amount'], 2) ?></td>
                <td><?= htmlspecialchars($row['payout_date']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info rounded-3 mb-4">No payouts scheduled.</div>
    <?php endif; ?>

    <!-- Member Requests Section -->
    <h4 class="section-title">Member Requests</h4>
    <?php if ($requests && $requests->num_rows > 0): ?>
      <div class="table-container mb-4">
        <table class="table custom-table table-hover">
          <thead>
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
      </div>
    <?php else: ?>
      <div class="alert alert-info rounded-3 mb-4">No requests submitted.</div>
    <?php endif; ?>

    <!-- Back to Dashboard Button -->
    <div class="text-center mt-5">
      <?php if ($role === 'admin'): ?>
        <a href="admindashboard.php" class="btn-primary-custom">Back to Dashboard</a>
      <?php elseif ($role === 'treasurer'): ?>
        <a href="treasurerdashboard.php" class="btn-primary-custom">Back to Dashboard</a>
      <?php else: ?>
        <a href="memberdashboard.php" class="btn-primary-custom">Back to Dashboard</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>