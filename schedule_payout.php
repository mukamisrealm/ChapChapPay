<?php
session_start();
include 'db.php';

// Allow only treasurer
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'treasurer') {
    header("Location: register_login.php");
    exit();
}
$role = $_SESSION['role'];

$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'];
    $amount = $_POST['amount'];
    $payout_date = $_POST['payout_date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO payouts (user_id, amount, payout_date, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $member_id, $amount, $payout_date, $status);

    if ($stmt->execute()) {
        $message = "Payout scheduled successfully.";
    } else {
        $message = "Failed to schedule payout.";
    }
}

// Fetch members
$members = $conn->query("SELECT id, name FROM users WHERE role = 'member' OR role = 'treasurer'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Schedule Payout - ChapChapPay</title>
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

    /* Clean white form card */
    .form-card {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
      border: none;
      padding: 35px;
      max-width: 600px;
      margin: 0 auto;
    }

    .form-card h3 {
      font-weight: 700;
      color: #1a202c;
      margin-bottom: 20px;
    }

    /* Blue Action Button */
    .btn-primary-custom {
      background-color: #0d6efd;
      border-color: #0d6efd;
      color: #ffffff;
      font-weight: 600;
      padding: 10px 28px;
      border-radius: 8px;
      text-decoration: none;
      display: inline-block;
      transition: background 0.2s ease;
    }

    .btn-primary-custom:hover {
      background-color: #0b5ed7;
      border-color: #0a58ca;
      color: #ffffff;
    }
  </style>
</head>
<body>

<div class="app-card">
  <!-- Top Blue Navbar with Full Treasurer Sub-Links -->
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
          <li class="nav-item"><a class="nav-link" href="view_requests.php">Requests</a></li>
          <li class="nav-item"><a class="nav-link" href="add_contrib.php">Add Contribution</a></li>
          <li class="nav-item"><a class="nav-link active" href="schedule_payout.php">Payouts</a></li>
        </ul>
        <a href="logout.php" class="btn btn-outline-light">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Main Content Area -->
  <div class="dashboard-content">
    <div class="form-card">
      <h3>Schedule a Payout</h3>

      <?php if (!empty($message)): ?>
        <div class="alert alert-info py-2 mb-3"><?php echo htmlspecialchars($message); ?></div>
      <?php endif; ?>

      <form method="POST" action="schedule_payout.php">
        <div class="mb-3">
          <label for="member_id" class="form-label text-dark fw-semibold">Member</label>
          <select name="member_id" class="form-select" required>
            <option value="">-- Select Member --</option>
            <?php while ($row = $members->fetch_assoc()): ?>
              <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="amount" class="form-label text-dark fw-semibold">Amount (KES)</label>
          <input type="number" name="amount" class="form-control" required min="0" step="50">
        </div>

        <div class="mb-3">
          <label for="payout_date" class="form-label text-dark fw-semibold">Payout Date</label>
          <input type="date" name="payout_date" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="status" class="form-label text-dark fw-semibold">Status</label>
          <select name="status" class="form-select" required>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
          </select>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
          <a href="treasurerdashboard.php" class="btn btn-outline-secondary">← Back to Dashboard</a>
          <button type="submit" class="btn btn-primary-custom">Schedule Payout</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>