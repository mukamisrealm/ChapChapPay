<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['name']) || strtolower($_SESSION['role']) !== 'treasurer') {
    // Not logged in or not a Treasurer
    header("Location: register_login.php");
    exit();
}
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Treasurer Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- ✅ NAVBAR START -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">ChapChapPay</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="treasurerdashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="add_contrib.php">Add Contribution</a></li>
        <li class="nav-item"><a class="nav-link" href="view_contrib.php">View Contributions</a></li>
        <li class="nav-item"><a class="nav-link" href="add_fine.php">Add Fine</a></li>
        <li class="nav-item"><a class="nav-link" href="view_fine.php">View Fines</a></li>
        <li class="nav-item"><a class="nav-link" href="schedule_payout.php">Schedule Payout</a></li>
        <li class="nav-item"><a class="nav-link" href="view_payout.php">View Payouts</a></li>
        <li class="nav-item"><a class="nav-link" href="view_members.php">View Members</a></li>
        <li class="nav-item"><a class="nav-link" href="view_reports.php">Reports</a></li>
        <li class="nav-item"><a class="nav-link" href="view_requests.php">Requests</a></li>
      </ul>
      <span class="navbar-text me-3">Hello, <?php echo htmlspecialchars($name); ?></span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>
<!-- ✅ NAVBAR END -->

<div class="container mt-4">
  <h2>Welcome, Treasurer <?php echo htmlspecialchars($name); ?>!</h2>


    <!-- View Members -->
    <div class="col-md-4 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">Manage Members</h5>
          <p class="card-text">View and manage member records.</p>
          <a href="view_members.php" class="btn btn-success">View Members</a>
        </div>
      </div>
    </div>

    

    <!-- Add Fine -->
<div class="col-md-4 mb-3">
  <div class="card text-center">
    <div class="card-body">
      <h5 class="card-title">Add Fine</h5>
      <p class="card-text">Record a fine for a member or treasurer.</p>
      <a href="add_fine.php" class="btn btn-danger">Add Fine</a>
    </div>
  </div>
</div>
     
    <!-- View Fines -->
<div class="col-md-4 mb-3">
  <div class="card text-center">
    <div class="card-body">
      <h5 class="card-title">View Fines</h5>
      <p class="card-text">Check all member fines.</p>
      <a href="view_fine.php" class="btn btn-warning">View Fines</a>
    </div>
  </div>
</div>



    <!-- View Reports -->
    <div class="col-md-4 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">Reports</h5>
          <p class="card-text">Access Chama reports.</p>
          <a href="view_reports.php" class="btn btn-success">View Reports</a>
        </div>
      </div>
    </div>

    <!-- View Member Requests -->
<div class="col-md-4 mb-3">
  <div class="card text-center">
    <div class="card-body">
      <h5 class="card-title">Member Requests</h5>
      <p class="card-text">View and act on loan, skip, or half-payment requests.</p>
      <a href="view_requests.php" class="btn btn-warning">View Requests</a>
    </div>
  </div>
</div>


    <!-- Contribution Summary -->
    <div class="col-md-4 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">My Contribution Summary</h5>
          <p class="card-text">View your contribution records.</p>
          <a href="view_contrib.php" class="btn btn-success">View Contribution Summary</a>
        </div>
      </div>
    </div>

   <!-- Add Contribution -->
<div class="col-md-4 mb-3">
  <div class="card text-center">
    <div class="card-body">
      <h5 class="card-title">Add Contribution</h5>
      <p class="card-text">Record a new member contribution.</p>
      <a href="add_contrib.php" class="btn btn-primary">Add Contribution</a>
    </div>
  </div>
</div>



    <!-- Schedule Payouts -->
    <div class="col-md-4 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">Payouts</h5>
          <p class="card-text">Manage and schedule payouts.</p>
          <a href="schedule_payout.php" class="btn btn-success">Schedule Payout</a>
        </div>
      </div>
    </div>

     <div class="col-md-4 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">My Payouts</h5>
          <p class="card-text">View payouts.</p>
          <a href="view_payout.php" class="btn btn-primary">View Payouts</a>
        </div>
      </div>
    </div>

  </div>
   <!--  Support Request Form -->
<div class="card mt-5">
  <div class="card-body">
    <h4 class="card-title">Need Help? Submit a Support Request</h4>

    <form action="../ChapChapPay/support_requests.php" method="POST">
      <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">

      <div class="mb-3">
        <label for="subject" class="form-label">Subject</label>
        <input type="text" class="form-control" name="subject" id="subject" required>
      </div>
      <div class="mb-3">
        <label for="message" class="form-label">Message</label>
        <textarea class="form-control" name="message" id="message" rows="4" required></textarea>
      </div>
      <button type="submit" class="btn btn-info">Send Request</button>
    </form>

  </div>
</div>
<!-- End Support Form -->

</div>

</body>
</html>
</div>

</body>
</html>
