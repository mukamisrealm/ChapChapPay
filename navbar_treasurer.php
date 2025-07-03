<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">ChapChapPay</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navContent">
      <ul class="navbar-nav me-auto">
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
      <span class="navbar-text me-3">Hello, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>
