<?php
session_start();
require_once 'lang.php';

if (!isset($_SESSION['name']) || strtolower($_SESSION['role'] ?? '') !== 'member') {
    header("Location: register_login.php");
    exit();
}

require_once 'db.php';
$name = $_SESSION['name'];
$user_id = $_SESSION['user_id'] ?? 0;

// Dynamic KPI Metrics
$total_contrib = 0.00;
$total_fines = 0.00;
$queue_pos = "N/A";

if ($user_id > 0) {
    // 1. Total Contributions
    $stmt1 = $conn->prepare("SELECT SUM(amount) AS total FROM contributions WHERE user_id = ?");
    if ($stmt1) {
        $stmt1->bind_param("i", $user_id);
        $stmt1->execute();
        $res1 = $stmt1->get_result()->fetch_assoc();
        $total_contrib = $res1['total'] ?? 0.00;
        $stmt1->close();
    }

    // 2. Total Fines
    $stmt2 = $conn->prepare("SELECT SUM(amount) AS total FROM fines WHERE user_id = ?");
    if ($stmt2) {
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        $res2 = $stmt2->get_result()->fetch_assoc();
        $total_fines = $res2['total'] ?? 0.00;
        $stmt2->close();
    }

    // 3. Queue Position
    $stmt3 = $conn->prepare("SELECT queue_position FROM payout_queue WHERE user_id = ? AND status = 'pending' LIMIT 1");
    if ($stmt3) {
        $stmt3->bind_param("i", $user_id);
        $stmt3->execute();
        $res3 = $stmt3->get_result()->fetch_assoc();
        if ($res3) {
            $queue_pos = "#" . $res3['queue_position'];
        }
        $stmt3->close();
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $current_lang ?? 'en' ?>">
<head>
  <meta charset="UTF-8">
  <title>Member Portal - ChapChapPay</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    :root {
      --bg-outer: #8172f8;
      --bg-inner: #061118;
      --card-bg: #ffffff;
      --text-dark: #0f172a;
      --text-muted: #64748b;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

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

    .navbar-blue {
      background: linear-gradient(90deg, #0d6efd 0%, #0b5ed7 100%) !important;
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
    }

    .kpi-card {
      background: rgba(255, 255, 255, 0.035);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 16px; padding: 16px 20px; color: #fff;
    }

    .kpi-title { font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; }
    .kpi-value { font-size: 1.4rem; font-weight: 800; color: #ffffff; margin-top: 4px; }

    .action-card {
      background: var(--card-bg);
      border-radius: 18px;
      border: 1px solid rgba(255, 255, 255, 0.9);
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
      transition: all 0.28s ease;
      text-decoration: none; display: flex; flex-direction: column; justify-content: space-between;
      padding: 22px; cursor: pointer;
    }

    .action-card:hover { transform: translateY(-5px); }

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

    .lang-switcher {
      display: flex; background: rgba(0, 0, 0, 0.25);
      border-radius: 30px; padding: 3px; border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .lang-btn { color: #ffffff; text-decoration: none; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; }
    .lang-btn.active { background: #ffffff; color: #0d6efd; }
  </style>
</head>
<body>

<div class="app-card">
  <!-- Shared Navbar (Renders Single Header Row) -->
  <?php include 'navbar.php'; ?>

  <div class="dashboard-content">
    <div class="user-banner">
      <div class="d-flex align-items-center gap-3">
        <div class="user-avatar"><?= strtoupper(substr($name, 0, 1)) ?></div>
        <div>
          <h2 class="text-white fw-bold m-0 fs-4">Welcome back, <?= htmlspecialchars($name) ?>! 👋</h2>
          <span class="text-secondary fs-7">Here is an overview of your financial activity</span>
        </div>
      </div>
      <div>
        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fs-8">
          ● Account Active
        </span>
      </div>
    </div>

    <!-- KPI Metrics -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="kpi-card">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="kpi-title">Total Contributed</span>
            <i class="bi bi-cash-stack text-primary fs-6"></i>
          </div>
          <div class="kpi-value">KES <?= number_format($total_contrib, 2) ?></div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="kpi-card">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="kpi-title">Total Fines Issued</span>
            <i class="bi bi-exclamation-triangle text-danger fs-6"></i>
          </div>
          <div class="kpi-value text-danger">KES <?= number_format($total_fines, 2) ?></div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="kpi-card">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="kpi-title">Payout Queue Status</span>
            <i class="bi bi-arrow-repeat text-warning fs-6"></i>
          </div>
          <div class="kpi-value text-warning"><?= $queue_pos ?></div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <h6 class="text-white fw-bold mb-3 fs-6">Quick Actions & Services</h6>
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <a href="view_contrib.php" class="action-card h-100">
          <div>
            <div class="icon-box icon-blue"><i class="bi bi-wallet2"></i></div>
            <div class="card-heading">My Contributions</div>
            <div class="card-subtext">Track your payment history and verified transaction receipts.</div>
          </div>
          <div class="action-link">View History <i class="bi bi-arrow-right"></i></div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="view_fine.php" class="action-card h-100">
          <div>
            <div class="icon-box icon-danger"><i class="bi bi-receipt-cutoff"></i></div>
            <div class="card-heading">My Fines</div>
            <div class="card-subtext">Check penalties issued to your account and payment statuses.</div>
          </div>
          <div class="action-link text-danger">Check Fines <i class="bi bi-arrow-right"></i></div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="make_request.php" class="action-card h-100">
          <div>
            <div class="icon-box icon-purple"><i class="bi bi-send-plus"></i></div>
            <div class="card-heading">Make a Request</div>
            <div class="card-subtext">Apply for a loan, payment skip, or partial payout arrangement.</div>
          </div>
          <div class="action-link" style="color:#7c3aed;">Submit Request <i class="bi bi-arrow-right"></i></div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="view_payout.php" class="action-card h-100">
          <div>
            <div class="icon-box icon-warning"><i class="bi bi-calendar-event"></i></div>
            <div class="card-heading">Payout Schedule</div>
            <div class="card-subtext">View rotational queue order and projected distribution dates.</div>
          </div>
          <div class="action-link text-warning">View Rotation <i class="bi bi-arrow-right"></i></div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="view_reports.php" class="action-card h-100">
          <div>
            <div class="icon-box icon-success"><i class="bi bi-bar-chart-line"></i></div>
            <div class="card-heading">Chama Reports</div>
            <div class="card-subtext">Access transparent overall group financial statements.</div>
          </div>
          <div class="action-link text-success">View Reports <i class="bi bi-arrow-right"></i></div>
        </a>
      </div>
    </div>

    <!-- Member Support Request Form -->
    <div class="card p-4 rounded-4" style="background: rgba(255,255,255,0.035); border: 1px solid rgba(255,255,255,0.08);">
      <h5 class="text-white fw-bold mb-3 fs-6"><i class="bi bi-headset me-2"></i>Need Help? Submit Support Request</h5>
      <form action="support_requests.php" method="POST">
        <input type="hidden" name="name" value="<?= htmlspecialchars($name) ?>">
        <div class="mb-3">
          <label class="form-label text-secondary fw-semibold fs-7">Subject</label>
          <input type="text" name="subject" class="form-control rounded-3 py-2" required placeholder="e.g. Contribution Verification Issue">
        </div>
        <div class="mb-3">
          <label class="form-label text-secondary fw-semibold fs-7">Message</label>
          <textarea name="message" class="form-control rounded-3 py-2" rows="3" required placeholder="Describe your query..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary px-4 fw-bold rounded-3 btn-sm">Send Support Message</button>
      </form>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>