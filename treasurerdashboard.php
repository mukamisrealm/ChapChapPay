<?php
session_start();
require_once 'lang.php';

if (!isset($_SESSION['name']) || strtolower($_SESSION['role'] ?? '') !== 'treasurer') {
    header("Location: register_login.php");
    exit();
}

require_once 'db.php';
$name = $_SESSION['name'];

// Fetch live Treasurer KPIs
$total_collected = 0.00;
$total_fines_issued = 0.00;
$pending_requests = 0;

// 1. Total Contributions Collected
$res1 = $conn->query("SELECT SUM(amount) AS total FROM contributions");
if ($res1) {
    $row1 = $res1->fetch_assoc();
    $total_collected = $row1['total'] ?? 0.00;
}

// 2. Total Fines Issued
$res2 = $conn->query("SELECT SUM(amount) AS total FROM fines");
if ($res2) {
    $row2 = $res2->fetch_assoc();
    $total_fines_issued = $row2['total'] ?? 0.00;
}

// 3. Pending Requests
$res3 = $conn->query("SELECT COUNT(*) AS total FROM requests WHERE status = 'pending'");
if ($res3) {
    $row3 = $res3->fetch_assoc();
    $pending_requests = $row3['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="<?= $current_lang ?? 'en' ?>">
<head>
  <meta charset="UTF-8">
  <title>Treasurer Portal - ChapChapPay</title>
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
      --accent-blue: #0d6efd;
      --card-bg: #ffffff;
      --text-dark: #0f172a;
      --text-muted: #64748b;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    }

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

    .dashboard-content {
      padding: 36px 36px;
      position: relative;
      z-index: 10;
    }

    .user-banner {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 28px;
      padding-bottom: 20px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .user-avatar {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, #0d6efd 0%, #8172f8 100%);
      color: #fff;
      font-weight: 800;
      font-size: 1.35rem;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
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
      font-size: 1.4rem;
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
      position: relative;
      overflow: hidden;
      text-decoration: none;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 22px;
      cursor: pointer;
    }

    .action-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 18px 36px -8px rgba(0, 0, 0, 0.45);
    }

    .icon-box {
      width: 46px;
      height: 46px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.35rem;
      margin-bottom: 16px;
    }

    .icon-blue { background: #eff6ff; color: #2563eb; }
    .icon-danger { background: #fef2f2; color: #dc2626; }
    .icon-success { background: #f0fdf4; color: #16a34a; }
    .icon-warning { background: #fffbeb; color: #d97706; }
    .icon-purple { background: #f5f3ff; color: #7c3aed; }

    .card-heading {
      font-weight: 800;
      color: var(--text-dark);
      font-size: 1.1rem;
      margin-bottom: 4px;
    }

    .card-subtext {
      color: var(--text-muted);
      font-size: 0.88rem;
      line-height: 1.45;
      margin-bottom: 20px;
    }

    .action-link {
      color: #2563eb;
      font-weight: 700;
      font-size: 0.9rem;
      display: flex;
      align-items: center;
      gap: 6px;
    }

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
  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-blue">
    <div class="container-fluid p-0">
      <a class="navbar-brand fw-bold fs-4 me-4 d-flex align-items-center gap-2" href="treasurerdashboard.php">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 0a.5.5 0 0 1 .5.5V1h5a.5.5 0 0 1 .5.5v14a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5V2a.5.5 0 0 1 .5-.5h5V.5A.5.5 0 0 1 8 0M3 3v12h10V3z"/>
        </svg>
        ChapChapPay
      </a>
      <div class="d-flex align-items-center gap-3">
        
        <!-- Language Switcher -->
        <div class="lang-switcher me-2">
          <a href="?lang=en" class="lang-btn <?= ($current_lang ?? 'en') === 'en' ? 'active' : '' ?>">EN</a>
          <a href="?lang=sw" class="lang-btn <?= ($current_lang ?? 'en') === 'sw' ? 'active' : '' ?>">SW</a>
        </div>

        <!-- Fail-safe Glassmorphic Badge -->
        <span style="background: rgba(255, 255, 255, 0.15) !important; color: #ffffff !important; border: 1px solid rgba(255, 255, 255, 0.3) !important; border-radius: 50px !important; padding: 6px 16px !important; font-size: 0.85rem !important; font-weight: 600 !important; display: inline-flex !important; align-items: center !important; gap: 6px !important;">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 1a2 2 0 0 1 2 2v2H6V3a2 2 0 0 1 2-2m3 4V3a3 3 3 0 0 0-6 0v2H3a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1z"/>
          </svg>
          Treasurer Portal
        </span>
        
        <a href="logout.php" class="btn btn-outline-light btn-sm px-3 rounded-3 fw-semibold">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Content Body -->
  <div class="dashboard-content">
    
    <!-- Greeting Banner -->
    <div class="user-banner">
      <div class="d-flex align-items-center gap-3">
        <div class="user-avatar">
          <?= strtoupper(substr($name, 0, 1)) ?>
        </div>
        <div>
          <h2 class="text-white fw-bold m-0 fs-4">Welcome, Treasurer <?= htmlspecialchars($name) ?> 👋</h2>
          <span class="text-secondary fs-7">Financial Operations Control Panel</span>
        </div>
      </div>
    </div>

    <!-- Live Compact KPI Summary -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="kpi-card">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="kpi-title">Total Fund Pool</span>
            <i class="bi bi-cash-coin text-primary fs-6"></i>
          </div>
          <div class="kpi-value">KES <?= number_format($total_collected, 2) ?></div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="kpi-card">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="kpi-title">Total Fines Issued</span>
            <i class="bi bi-exclamation-octagon text-danger fs-6"></i>
          </div>
          <div class="kpi-value text-danger">KES <?= number_format($total_fines_issued, 2) ?></div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="kpi-card">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="kpi-title">Pending Member Requests</span>
            <i class="bi bi-inbox text-warning fs-6"></i>
          </div>
          <div class="kpi-value text-warning"><?= $pending_requests ?> Action(s)</div>
        </div>
      </div>
    </div>

    <!-- Core 6 Task Modules -->
    <h6 class="text-white fw-bold mb-3 fs-6">Financial Management Modules</h6>
    <div class="row g-3 mb-4">
      
      <!-- 1. Add Contribution -->
      <div class="col-md-4">
        <a href="add_contrib.php" class="action-card h-100">
          <div>
            <div class="icon-box icon-blue"><i class="bi bi-plus-circle-fill"></i></div>
            <div class="card-heading">Add Contribution</div>
            <div class="card-subtext">Record a new member payment into the Chama pool balance.</div>
          </div>
          <div class="action-link">Open Record Form <i class="bi bi-arrow-right"></i></div>
        </a>
      </div>

      <!-- 2. Manage Fines -->
      <div class="col-md-4">
        <a href="add_fine.php" class="action-card h-100">
          <div>
            <div class="icon-box icon-danger"><i class="bi bi-receipt-cutoff"></i></div>
            <div class="card-heading">Fines Management</div>
            <div class="card-subtext">Issue penalties for late payments or review member fine logs.</div>
          </div>
          <div class="action-link text-danger">Manage Fines <i class="bi bi-arrow-right"></i></div>
        </a>
      </div>

      <!-- 3. Rotational Queue Generator -->
      <div class="col-md-4">
        <div onclick="confirmShuffle(event)" class="action-card h-100">
          <div>
            <div class="icon-box icon-purple"><i class="bi bi-cpu-fill"></i></div>
            <div class="card-heading">Rotational Queue</div>
            <div class="card-subtext">Run ML reliability engine to dynamically schedule payout rotation.</div>
          </div>
          <div class="action-link" style="color:#7c3aed;">Generate ML Queue <i class="bi bi-arrow-right"></i></div>
        </div>
      </div>

      <!-- 4. Payout Schedule -->
      <div class="col-md-4">
        <a href="view_payout.php" class="action-card h-100">
          <div>
            <div class="icon-box icon-warning"><i class="bi bi-calendar-check"></i></div>
            <div class="card-heading">Payout Schedule</div>
            <div class="card-subtext">Monitor active rotational order, dates, and payout statuses.</div>
          </div>
          <div class="action-link text-warning">View Payout Queue <i class="bi bi-arrow-right"></i></div>
        </a>
      </div>

      <!-- 5. Member Requests -->
      <div class="col-md-4">
        <a href="view_requests.php" class="action-card h-100">
          <div>
            <div class="icon-box icon-blue"><i class="bi bi-card-checklist"></i></div>
            <div class="card-heading">Member Requests</div>
            <div class="card-subtext">Review and act on loan applications or payment skip requests.</div>
          </div>
          <div class="action-link">Review Requests <i class="bi bi-arrow-right"></i></div>
        </a>
      </div>

      <!-- 6. Reports -->
      <div class="col-md-4">
        <a href="view_reports.php" class="action-card h-100">
          <div>
            <div class="icon-box icon-success"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="card-heading">Chama Reports</div>
            <div class="card-subtext">Access group financial statements and balance ledgers.</div>
          </div>
          <div class="action-link text-success">View Reports <i class="bi bi-arrow-right"></i></div>
        </a>
      </div>

    </div>

    <!-- Support Request Form -->
    <div class="card p-4 rounded-4" style="background: rgba(255,255,255,0.035); border: 1px solid rgba(255,255,255,0.08);">
      <h5 class="text-white fw-bold mb-3 fs-6"><i class="bi bi-headset me-2"></i>Need Help? Submit Support Request</h5>
      <form action="support_requests.php" method="POST">
        <input type="hidden" name="name" value="<?= htmlspecialchars($name) ?>">
        <div class="mb-3">
          <label class="form-label text-secondary fw-semibold fs-7">Subject</label>
          <input type="text" name="subject" class="form-control rounded-3 py-2" required>
        </div>
        <div class="mb-3">
          <label class="form-label text-secondary fw-semibold fs-7">Message</label>
          <textarea name="message" class="form-control rounded-3 py-2" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary px-4 fw-bold rounded-3 btn-sm">Send Support Message</button>
      </form>
    </div>

  </div>
</div>

<!-- SweetAlert2 Trigger Function -->
<script>
function confirmShuffle(e) {
  e.preventDefault();
  Swal.fire({
    title: 'Run Queue Generator?',
    text: 'Runs the dynamic engine to balance payout order.',
    icon: 'question',
    background: '#0d1b2a',
    color: '#ffffff',
    showCancelButton: true,
    confirmButtonColor: '#8172f8',
    cancelButtonColor: '#334155',
    confirmButtonText: 'Generate queue',
    cancelButtonText: 'Cancel',
    padding: '1.75rem',
    borderRadius: '20px'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'generate_queue.php';
    }
  });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>