<?php 
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO support_requests (user_id, subject, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $subject, $message);
    $stmt->execute();
    $stmt->close();

    $_SESSION['flash'] = "Support request submitted successfully.";
    header("Location: support_requests.php");
    exit();
}

// Fetch all support requests
$result = $conn->query("SELECT * FROM support_requests ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Support Requests - ChapChapPay</title>
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
      max-width: 1200px;
      min-height: 650px;
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

    /* Top Navigation Bar */
    .navbar-custom {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 25px 40px;
      position: relative;
      z-index: 10;
    }

    .logo {
      font-size: 1.4rem;
      font-weight: 800;
      color: #ffffff;
    }

    .nav-links {
      display: flex;
      gap: 25px;
    }

    .nav-links a {
      color: #a0aec0;
      text-decoration: none;
      font-weight: 500;
      font-size: 0.95rem;
      transition: color 0.2s ease;
    }

    .nav-links a:hover,
    .nav-links a.active {
      color: #ffffff;
    }

    /* Dashboard Content Wrapper */
    .dashboard-content {
      padding: 20px 40px 40px;
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
  <!-- Navigation Bar -->
  <nav class="navbar-custom">
    <div class="logo">ChapChapPay</div>
    <div class="nav-links">
      <a href="admindashboard.php">Dashboard</a>
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
      <a href="contact_us.php">Contact</a>
      <a href="help.php">Help</a>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="dashboard-content">
    <h2 class="page-title">Support Requests</h2>

    <?php if (isset($_SESSION['flash'])): ?>
      <div class="alert alert-success rounded-3 mb-4">
        <?= htmlspecialchars($_SESSION['flash']) ?>
      </div>
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?php if ($result && $result->num_rows > 0): ?>
      <div class="table-container mb-4">
        <table class="table custom-table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Name / ID</th>
              <th>Subject</th>
              <th>Message</th>
              <th>Submitted At</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['id']) ?></td>
              <td><?= htmlspecialchars($row['user_id']) ?></td>
              <td><?= htmlspecialchars($row['subject']) ?></td>
              <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
              <td><?= htmlspecialchars($row['created_at']) ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="card p-4 rounded-3 text-center bg-white">
        <p class="text-secondary mb-0">No support requests found.</p>
      </div>
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