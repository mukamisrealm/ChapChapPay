<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: register_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Contributions</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">ChapChapPay</a>
    <div class="d-flex">
      <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h3 class="mb-4 text-center">My Contribution History</h3>

  <!-- Placeholder table: later we’ll fetch real data from DB -->
  <table class="table table-bordered table-striped">
    <thead class="thead-dark">
      <tr>
        <th>#</th>
        <th>Date</th>
        <th>Amount (KES)</th>
        <th>Comment</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>2025-06-15</td>
        <td>500</td>
        <td>Monthly contribution</td>
      </tr>
      <tr>
        <td>2</td>
        <td>2025-06-29</td>
        <td>250</td>
        <td>Top up</td>
      </tr>
    </tbody>
  </table>

  <div class="text-center mt-4">
    <a href="<?php echo ($_SESSION['role'] === 'treasurer') ? 'treasurerdashboard.php' : 'memberdashboard.php'; ?>" class="btn btn-secondary">Back to Dashboard</a>
  </div>
</div>

</body>
</html>
