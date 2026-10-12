<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['name']) || strtolower($_SESSION['role']) !== 'treasurer') {
    header("Location: register_login.php");
    exit();
}
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Treasurer Dashboard - ChapChapPay</title>
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

    .navbar-blue {
      background-color: #0d6efd !important;
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
      background: #fffffff7;
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
  <!-- Top Blue Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-blue">
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
    <h2 class="welcome-text">Welcome, Treasurer <?php echo htmlspecialchars($name); ?>!</h2>

    <div class="row">
      <!-- Manage Members -->
      <div class="col-md-4 mb-4">
        <div class="card text-center card-widget h-100">
          <div class="card-body d-flex flex-column justify-content-between p-4">
            <div>
              <h5 class="card-title fs-5 mb-2">Manage Members</h5>
              <p class="card-text mb-4">View and manage member records.</p>
            </div>
            <div><a href="view_members.php" class="btn btn-primary px-4">View Members</a></div>
          </div>
        </div>
      </div>

      <!-- Add Fine -->
      <div class="col-md-4 mb-4">
        <div class="card text-center card-widget h-100">
          <div class="card-body d-flex flex-column justify-content-between p-4">
            <div>
              <h5 class="card-title fs-5 mb-2">Add Fine</h5>
              <p class="card-text mb-4">Record a fine for a member or treasurer.</p>
            </div>
            <div><a href="add_fine.php" class="btn btn-primary px-4">Add Fine</a></div>
          </div>
        </div>
      </div>

      <!-- View Fines -->
      <div class="col-md-4 mb-4">
        <div class="card text-center card-widget h-100">
          <div class="card-body d-flex flex-column justify-content-between p-4">
            <div>
              <h5 class="card-title fs-5 mb-2">View Fines</h5>
              <p class="card-text mb-4">Check all member fines.</p>
            </div>
            <div><a href="view_fine.php" class="btn btn-primary px-4">View Fines</a></div>
          </div>
        </div>
      </div>

      <!-- Reports -->
      <div class="col-md-4 mb-4">
        <div class="card text-center card-widget h-100">
          <div class="card-body d-flex flex-column justify-content-between p-4">
            <div>
              <h5 class="card-title fs-5 mb-2">Reports</h5>
              <p class="card-text mb-4">Access Chama reports.</p>
            </div>
            <div><a href="view_reports.php" class="btn btn-primary px-4">View Reports</a></div>
          </div>
        </div>
      </div>

      <!-- Member Requests -->
      <div class="col-md-4 mb-4">
        <div class="card text-center card-widget h-100">
          <div class="card-body d-flex flex-column justify-content-between p-4">
            <div>
              <h5 class="card-title fs-5 mb-2">Member Requests</h5>
              <p class="card-text mb-4">View and act on loan or skip requests.</p>
            </div>
            <div><a href="view_requests.php" class="btn btn-primary px-4">View Requests</a></div>
          </div>
        </div>
      </div>

      <!-- Contribution Summary -->
      <div class="col-md-4 mb-4">
        <div class="card text-center card-widget h-100">
          <div class="card-body d-flex flex-column justify-content-between p-4">
            <div>
              <h5 class="card-title fs-5 mb-2">Contribution Summary</h5>
              <p class="card-text mb-4">View your contribution records.</p>
            </div>
            <div><a href="view_contrib.php" class="btn btn-primary px-4">View Summary</a></div>
          </div>
        </div>
      </div>

      <!-- Add Contribution -->
      <div class="col-md-4 mb-4">
        <div class="card text-center card-widget h-100">
          <div class="card-body d-flex flex-column justify-content-between p-4">
            <div>
              <h5 class="card-title fs-5 mb-2">Add Contribution</h5>
              <p class="card-text mb-4">Record a new member contribution.</p>
            </div>
            <div><a href="add_contrib.php" class="btn btn-primary px-4">Add Contribution</a></div>
          </div>
        </div>
      </div>

      <!-- Schedule Payouts -->
      <div class="col-md-4 mb-4">
        <div class="card text-center card-widget h-100">
          <div class="card-body d-flex flex-column justify-content-between p-4">
            <div>
              <h5 class="card-title fs-5 mb-2">Payouts</h5>
              <p class="card-text mb-4">Manage and schedule payouts.</p>
            </div>
            <div><a href="schedule_payout.php" class="btn btn-primary px-4">Schedule Payout</a></div>
          </div>
        </div>
      </div>

      <!-- My Payouts -->
      <div class="col-md-4 mb-4">
        <div class="card text-center card-widget h-100">
          <div class="card-body d-flex flex-column justify-content-between p-4">
            <div>
              <h5 class="card-title fs-5 mb-2">My Payouts</h5>
              <p class="card-text mb-4">View payouts.</p>
            </div>
            <div><a href="view_payout.php" class="btn btn-primary px-4">View Payouts</a></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Support Request Form -->
    <div class="card card-widget mt-2">
      <div class="card-body p-4">
        <h4 class="card-title mb-3">Need Help? Submit a Support Request</h4>
        <form action="support_requests.php" method="POST">
          <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
          <div class="mb-3">
            <label for="subject" class="form-label text-dark fw-semibold">Subject</label>
            <input type="text" class="form-control" name="subject" id="subject" required>
          </div>
          <div class="mb-3">
            <label for="message" class="form-label text-dark fw-semibold">Message</label>
            <textarea class="form-control" name="message" id="message" rows="4" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary fw-bold">Send Request</button>
        </form>
      </div>
    </div>

  </div>
</div>

</body>
</html>