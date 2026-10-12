<?php
session_start();
include 'db.php';

// Only treasurer can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'treasurer') {
    header("Location: register_login.php");
    exit();
}
$role = $_SESSION['role'];

// Fetch users who have logged in (i.e., members)
$stmt = $conn->prepare("SELECT * FROM users WHERE role = 'member'");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Members - ChapChapPay</title>
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

    /* Dark inner container card matching the theme */
    .app-card {
      width: 100%;
      max-width: 1100px;
      min-height: 600px;
      background-color: #061118;
      border-radius: 20px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
      border: 1px solid rgba(255, 255, 255, 0.08);
      overflow: hidden;
      position: relative;
    }

    /* Ambient radial glow effect */
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

    /* Dashboard Content Wrapper */
    .dashboard-content {
      padding: 30px 40px 40px;
      position: relative;
      z-index: 10;
    }

    .page-title {
      color: #ffffff;
      font-weight: 700;
      margin-bottom: 25px;
    }

    /* Clean white table container */
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
  <!-- Top Blue Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-blue">
    <div class="container-fluid p-0">
      <a class="navbar-brand fw-bold fs-4 me-4" href="treasurerdashboard.php">ChapChapPay</a>
      <div class="d-flex align-items-center gap-2 ms-auto">
        <a href="treasurerdashboard.php" class="btn btn-outline-light me-2">Dashboard</a>
        <a href="logout.php" class="btn btn-outline-light">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="dashboard-content">
    <h2 class="page-title">Active Chama Members</h2>

    <?php if ($result->num_rows > 0): ?>
      <div class="table-container mb-4">
        <table class="table custom-table table-hover">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Last Login</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><span class="badge bg-secondary"><?= htmlspecialchars($row['role']) ?></span></td>
                <td><?= $row['last_login'] ? htmlspecialchars($row['last_login']) : 'Not logged in'; ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info rounded-3 text-center mb-4">No members have logged in yet.</div>
    <?php endif; ?>

    <!-- Back to Dashboard Button -->
    <div class="text-center mt-4">
      <a href="treasurerdashboard.php" class="btn-primary-custom">← Back to Dashboard</a>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>