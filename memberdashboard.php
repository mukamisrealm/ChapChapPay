<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
//var_dump($_SESSION);
//exit();
if (!isset($_SESSION['name'])) {
    // User is not logged in; redirect to login
    header("Location: login.php");
    exit();
}
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Member Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap for clean UI -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">ChapChapPay</a>
    <div class="d-flex">
<a href="../logout.php" class="btn btn-outline-light">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h2>Welcome, <?php echo htmlspecialchars($name); ?>!</h2>

  <div class="row">
    <div class="col-md-4 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">My Contributions</h5>
          <p class="card-text">View your contribution history.</p>
          <a href="../view_contrib.php" class="btn btn-primary">View Contributions</a>
        </div>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">My Fines</h5>
          <p class="card-text">Check fines issued to you.</p>
          <a href="../view_fine.php" class="btn btn-primary">View Fines</a>
        </div>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title">My Payouts</h5>
          <p class="card-text">View your payouts.</p>
          <a href="../view_payout.php" class="btn btn-primary">View Payouts</a>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
