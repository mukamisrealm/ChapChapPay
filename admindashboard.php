<?php
session_start();
require_once 'lang.php';

// Case-insensitive session verification
if (!isset($_SESSION['name']) || strtolower($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: register_login.php");
    exit();
}

require_once 'db.php';
$name = $_SESSION['name'];

// Dynamic System Metrics
$total_logs = 0;
$pending_support = 0;

$res1 = $conn->query("SELECT COUNT(*) AS total FROM activity_logs");
if ($res1) { $total_logs = $res1->fetch_assoc()['total'] ?? 0; }

$res2 = $conn->query("SELECT COUNT(*) AS total FROM support_requests WHERE status = 'pending'");
if ($res2) { $pending_support = $res2->fetch_assoc()['total'] ?? 0; }
?>

<!DOCTYPE html>
<html lang="<?= $current_lang ?? 'en' ?>">
<head>
  <meta charset="UTF-8">
  <title><?= __('app_title') ?> - ChapChapPay</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Google Fonts, CSS & SweetAlert2 -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <style>
    :root {
      --bg-outer: #8172f8;
      --bg-inner: #061118;
      --card-bg: #ffffff;
      --text-dark: #0f172a;
      --text-muted: #64748b;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; }

    body {
      background: var(--bg-outer);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 30px 20px;
    }

    .app-card {
      width: 100%;
      max-width: 1240px;
      background-color: var(--bg-inner);
      border-radius: 24px;
      box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.5);
      border: 1px solid rgba(255, 255, 255, 0.08);
      overflow: hidden;
      position: relative;
    }

    .app-card::before {
      content: '';
      position: absolute;
      top: -120px;
      right: -80px;
      width: 550px;
      height: 550px;
      background: radial-gradient(circle, rgba(111, 76, 255, 0.3) 0%, rgba(6, 17, 24, 0) 70%);
      pointer-events: none;
    }

    .navbar-blue {
      background: linear-gradient(90deg, #0d6efd 0%, #0b5ed7 100%) !important;
      padding: 16px 32px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .dashboard-content { padding: 36px 36px; position: relative; z-index: 10; }

    .user-banner {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 28px;
      padding-bottom: 20px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .user-avatar {
      width: 50px; height: 50px;
      background: linear-gradient(135deg, #0d6efd 0%, #8172f8 100%);
      color: #fff; font-weight: 800; font-size: 1.35rem;
      border-radius: 14px; display: flex; align-items: center; justify-content: center;
      box-shadow: 0 8px 20px rgba(13, 110, 253, 0.35);
    }

    /* Moderate & Compact KPI Stats Cards */
    .kpi-card {
      background: rgba(255, 255, 255, 0.035);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 16px; 
      padding: 16px 20px; 
      color: #fff;
      backdrop-filter: blur(12px);
    }

    .kpi-title {
      font-size: 0.75rem;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 0.7px;
      font-weight: 700;
    }

    .kpi-value {
      font-size: 1.5rem;
      font-weight: 800;
      color: #ffffff;
      margin-top: 4px;
    }

    /* Action Modules Cards */
    .action-card {
      background: var(--card-bg);
      border-radius: 18px;
      border: 1px solid rgba(255, 255, 255, 0.9);
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
      transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
      text-decoration: none; display: flex; flex-direction: column; justify-content: space-between;
      padding: 22px; cursor: pointer;
    }

    .action-card:hover { 
      transform: translateY(-5px);
      box-shadow: 0 18px 36px -8px rgba(0, 0, 0, 0.45);
    }

    .icon-box {
      width: 46px; height: 46px; border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.35rem; margin-bottom: 16px;
    }

    .icon-blue { background: #eff6ff; color: #2563eb; }
    .icon-danger { background: #fef2f2; color: #dc2626; }
    .icon-success { background: #f0fdf4; color: #16a34a; }
    .icon-warning { background: #fffbeb; color: #d97706; }
    .icon-purple { background: #f5f3ff; color: #7c3aed; }

    .card-heading { font-weight: 800; color: var(--text-dark); font-size: 1.1rem; margin-bottom: 4px; }
    .card-subtext { color: var(--text-muted); font-size: 0.88rem; line-height: 1.45; margin-bottom: 20px; }
    .action-link { color: #2563eb; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 6px; }

    /* Language Switcher Pill Styles */
    .lang-switcher {
      display: flex;
      background: rgba(0, 0, 0, 0.25);
      border-radius: 30px;
      padding: 3px;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .lang-btn {
      color: #ffffff;
      text-decoration: none;
      font-size: 0.75rem;
      font-weight: 700;
      padding: 4px 10px;
      border-radius: 20px;
      transition: all 0.2s ease;
    }

    .lang-btn.active {
      background: #ffffff;
      color: #0d6efd;
    }
  </style>
</head>
<body>

<div class="app-card">
  <!-- Shared Reusable Navigation Bar Component -->
  <?php include 'navbar.php'; ?>

  <!-- Content Body -->
  <div class="dashboard-content">
    <div class="user-banner">
      <div class="d-flex align-items-center gap-3">
        <div class="user-avatar"><?= strtoupper(substr($name, 0, 1)) ?></div>
        <div>
          <h2 class="text-white fw-bold m-0 fs-4"><?= __('welcome') ?>, <?= htmlspecialchars($name) ?> 👋</h2>
          <span class="text-secondary fs-7"><?= __('subtitle') ?></span>
        </div>
      </div>
    </div>

    <!-- Governance KPI Metrics -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="kpi-card">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="kpi-title"><?= __('server_status') ?></span>
            <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-2 py-1 fs-8"><?= __('online') ?></span>
          </div>
          <div class="kpi-value"><?= __('operational') ?></div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="kpi-card">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="kpi-title"><?= __('audit_logs') ?></span>
            <i class="bi bi-activity text-purple fs-6" style="color: #a78bfa;"></i>
          </div>
          <div class="kpi-value" style="color: #a78bfa;"><?= number_format($total_logs) ?> Logs</div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="kpi-card">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="kpi-title"><?= __('support_tickets') ?></span>
            <i class="bi bi-headset text-danger fs-6"></i>
          </div>
          <div class="kpi-value text-danger"><?= number_format($pending_support) ?> <?= __('pending') ?></div>
        </div>
      </div>
    </div>

    <!-- 5 Core Admin Modules -->
    <h6 class="text-white fw-bold mb-3 fs-6"><?= __('admin_modules') ?></h6>
    <div class="row g-3">
      
      <!-- 1. System Health -->
      <div class="col-md-4">
        <a href="system_health.php" class="action-card h-100">
          <div>
            <div class="icon-box icon-success"><i class="bi bi-heart-pulse-fill"></i></div>
            <div class="card-heading"><?= __('sys_health') ?></div>
            <div class="card-subtext"><?= __('sys_health_desc') ?></div>
          </div>
          <div class="action-link text-success"><?= __('view_status') ?> <i class="bi bi-arrow-right"></i></div>
        </a>
      </div>

      <!-- 2. Data Backup / Restore -->
      <div class="col-md-4">
        <a href="backup_restore.php" class="action-card h-100">
          <div>
            <div class="icon-box icon-blue"><i class="bi bi-database-gear"></i></div>
            <div class="card-heading"><?= __('data_backup') ?></div>
            <div class="card-subtext"><?= __('data_backup_desc') ?></div>
          </div>
          <div class="action-link"><?= __('backup_restore') ?> <i class="bi bi-arrow-right"></i></div>
        </a>
      </div>

      <!-- 3. Activity Logs -->
      <div class="col-md-4">
        <a href="activity_logs.php" class="action-card h-100">
          <div>
            <div class="icon-box icon-purple"><i class="bi bi-clock-history"></i></div>
            <div class="card-heading"><?= __('activity_logs') ?></div>
            <div class="card-subtext"><?= __('activity_logs_desc') ?></div>
          </div>
          <div class="action-link" style="color:#7c3aed;"><?= __('view_logs') ?> <i class="bi bi-arrow-right"></i></div>
        </a>
      </div>

      <!-- 4. Clean Old Data -->
      <div class="col-md-4">
        <a href="cleanup.php" class="action-card h-100">
          <div>
            <div class="icon-box icon-warning"><i class="bi bi-trash3-fill"></i></div>
            <div class="card-heading"><?= __('clean_data') ?></div>
            <div class="card-subtext"><?= __('clean_data_desc') ?></div>
          </div>
          <div class="action-link text-warning"><?= __('clean_data_btn') ?> <i class="bi bi-arrow-right"></i></div>
        </a>
      </div>

      <!-- 5. Support Requests -->
      <div class="col-md-4">
        <a href="support_requests.php" class="action-card h-100">
          <div>
            <div class="icon-box icon-danger"><i class="bi bi-headset"></i></div>
            <div class="card-heading"><?= __('support_req') ?></div>
            <div class="card-subtext"><?= __('support_req_desc') ?></div>
          </div>
          <div class="action-link text-danger"><?= __('view_support') ?> <i class="bi bi-arrow-right"></i></div>
        </a>
      </div>

    </div>
  </div>
</div>

<!-- SweetAlert2 Logout Confirmation Script -->
<script>
function confirmLogout(e) {
  e.preventDefault();
  Swal.fire({
    title: '<?= $current_lang === "sw" ? "Ondoka kwenye Mfumo?" : "End Session?" ?>',
    text: '<?= $current_lang === "sw" ? "Je, una uhakika unataka kuondoka kwenye mfumo?" : "Are you sure you want to log out of the Administrator dashboard?" ?>',
    icon: 'warning',
    background: '#0d1b2a',
    color: '#ffffff',
    showCancelButton: true,
    confirmButtonColor: '#0d6efd',
    cancelButtonColor: '#334155',
    confirmButtonText: '<?= $current_lang === "sw" ? "Ondoka" : "Logout" ?>',
    cancelButtonText: '<?= $current_lang === "sw" ? "Ghairi" : "Cancel" ?>',
    padding: '1.75rem',
    borderRadius: '20px'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'logout.php';
    }
  });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>