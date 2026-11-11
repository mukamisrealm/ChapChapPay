<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'lang.php';

$role = strtolower($_SESSION['role'] ?? '');
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-dark navbar-blue sticky-top">
  <div class="container-fluid p-0">
    <!-- Dynamic Brand Logo Redirect -->
    <a class="navbar-brand fw-bold fs-4 me-4 d-flex align-items-center gap-2" 
       href="<?= $role === 'admin' ? 'admindashboard.php' : ($role === 'treasurer' ? 'treasurerdashboard.php' : 'memberdashboard.php') ?>">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99 1.094 1.43 2.38 2.3 3.28 2.818a1.58 1.58 0 0 0 1.828 0c.9-.518 2.186-1.388 3.28-2.818 1.678-2.195 3.061-5.513 2.465-9.99a1.54 1.54 0 0 0-1.044-1.263c-.658-.216-1.777-.57-2.887-.87C9.843.266 8.69 0 8 0"/>
      </svg>
      ChapChapPay
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1">
        
        <?php if ($role === 'admin'): ?>
          <!-- ADMIN LINKS -->
          <li class="nav-item"><a class="nav-link <?= $current_page === 'admindashboard.php' ? 'active fw-bold' : '' ?>" href="admindashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link <?= $current_page === 'pay.php' ? 'active fw-bold' : '' ?>" href="pay.php">Make Payment</a></li>
          <li class="nav-item"><a class="nav-link <?= $current_page === 'system_health.php' ? 'active fw-bold' : '' ?>" href="system_health.php">Health</a></li>
          <li class="nav-item"><a class="nav-link <?= $current_page === 'activity_logs.php' ? 'active fw-bold' : '' ?>" href="activity_logs.php">Audit Logs</a></li>
          <li class="nav-item"><a class="nav-link <?= $current_page === 'backup_restore.php' ? 'active fw-bold' : '' ?>" href="backup_restore.php">Backups</a></li>
          <li class="nav-item"><a class="nav-link <?= $current_page === 'support_requests.php' ? 'active fw-bold' : '' ?>" href="support_requests.php">Support</a></li>

        <?php elseif ($role === 'treasurer'): ?>
          <!-- TREASURER LINKS -->
          <li class="nav-item"><a class="nav-link <?= $current_page === 'treasurerdashboard.php' ? 'active fw-bold' : '' ?>" href="treasurerdashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link <?= $current_page === 'pay.php' ? 'active fw-bold' : '' ?>" href="pay.php">Make Payment</a></li>
          <li class="nav-item"><a class="nav-link <?= $current_page === 'add_contrib.php' ? 'active fw-bold' : '' ?>" href="add_contrib.php">Contributions</a></li>
          <li class="nav-item"><a class="nav-link <?= $current_page === 'add_fine.php' ? 'active fw-bold' : '' ?>" href="add_fine.php">Fines</a></li>
          <li class="nav-item"><a class="nav-link <?= $current_page === 'view_payout.php' ? 'active fw-bold' : '' ?>" href="view_payout.php">Payout Queue</a></li>
          <li class="nav-item"><a class="nav-link <?= $current_page === 'view_requests.php' ? 'active fw-bold' : '' ?>" href="view_requests.php">Requests</a></li>

        <?php else: ?>
          <!-- MEMBER LINKS -->
          <li class="nav-item"><a class="nav-link <?= $current_page === 'memberdashboard.php' ? 'active fw-bold' : '' ?>" href="memberdashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link <?= $current_page === 'pay.php' ? 'active fw-bold' : '' ?>" href="pay.php">Make Payment</a></li>
          <li class="nav-item"><a class="nav-link <?= $current_page === 'view_contrib.php' ? 'active fw-bold' : '' ?>" href="view_contrib.php">My Statement</a></li>
          <li class="nav-item"><a class="nav-link <?= $current_page === 'view_payout.php' ? 'active fw-bold' : '' ?>" href="view_payout.php">Payout Queue</a></li>
        <?php endif; ?>

      </ul>

      <!-- Right Side Controls (Language & Logout) -->
      <div class="d-flex align-items-center gap-3">
        <!-- Language Switcher -->
        <div class="lang-switcher">
          <a href="?lang=en" class="lang-btn <?= ($current_lang ?? 'en') === 'en' ? 'active' : '' ?>">EN</a>
          <a href="?lang=sw" class="lang-btn <?= ($current_lang ?? 'en') === 'sw' ? 'active' : '' ?>">SW</a>
        </div>

        <!-- Role Badge -->
        <span style="background: rgba(255, 255, 255, 0.15) !important; color: #ffffff !important; border: 1px solid rgba(255, 255, 255, 0.3) !important; border-radius: 50px !important; padding: 4px 14px !important; font-size: 0.8rem !important; font-weight: 600 !important;">
          <?= ucfirst($role) ?> Portal
        </span>

        <a href="logout.php" class="btn btn-outline-light btn-sm px-3 rounded-3 fw-semibold">Logout</a>
      </div>
    </div>
  </div>
</nav>