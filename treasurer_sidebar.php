<!-- treasurer_sidebar.php -->
<style>
  body {
    margin: 0;
    font-family: Arial, sans-serif;
  }

  .sidebar {
    height: 100vh;
    width: 220px;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #198754;
    color: white;
    padding-top: 20px;
    transition: width 0.3s;
  }

  .sidebar a {
    display: block;
    padding: 12px 20px;
    color: white;
    text-decoration: none;
  }

  .sidebar a:hover {
    background-color: #145c3d;
  }

  .sidebar-toggle {
    position: fixed;
    top: 15px;
    left: 230px;
    font-size: 22px;
    cursor: pointer;
    z-index: 999;
    color: #198754;
  }

  @media (max-width: 768px) {
    .sidebar {
      width: 0;
      overflow: hidden;
    }

    .sidebar.active {
      width: 220px;
    }

    .sidebar-toggle {
      left: 10px;
    }

    .main-content {
      margin-left: 0 !important;
    }
  }

  .main-content {
    margin-left: 220px;
    padding: 20px;
    transition: margin-left 0.3s;
  }

  .main-content.full {
    margin-left: 0;
  }
</style>

<!-- Sidebar toggle button -->
<div class="sidebar-toggle" onclick="toggleSidebar()">☰</div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <h4 class="text-center">ChapChapPay</h4>
  <hr style="border-color: rgba(255,255,255,0.3);">

  <a href="treasurerdashboard.php">Dashboard</a>
  <a href="add_contrib.php">Add Contribution</a>
  <a href="view_contrib.php">View Contributions</a>
  <a href="add_fine.php">Add Fine</a>
  <a href="view_fines.php">View Fines</a>
  <a href="schedule_payout.php">Schedule Payout</a>
  <a href="view_payouts.php">View Payouts</a>
  <a href="view_members.php">View Members</a>
  <a href="view_reports.php">Reports</a>
  <a href="view_requests.php">Requests</a>
  <a href="index.php">Home</a>
  <a href="logout.php" class="btn btn-light btn-sm m-3">Logout</a>
</div>

<script>
  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const content = document.querySelector('.main-content');
    sidebar.classList.toggle('active');
    content.classList.toggle('full');
  }
</script>
