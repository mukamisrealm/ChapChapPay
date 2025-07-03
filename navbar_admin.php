<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">ChapChapPay</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navContent">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="admindashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
        <li class="nav-item"><a class="nav-link" href="system_settings.php">Settings</a></li>
      </ul>
      <span class="navbar-text me-3">Hello, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>
