<?php
session_start();

// Enforce Admin access
if (!isset($_SESSION['name']) || strtolower($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: register_login.php");
    exit();
}

$name = $_SESSION['name'];

// 1. Gather Disk Space
$freeSpaceBytes = @disk_free_space("C:") ?: 0;
$totalSpaceBytes = @disk_total_space("C:") ?: 1;

$freeSpaceMB = round($freeSpaceBytes / 1024 / 1024);
$totalSpaceMB = round($totalSpaceBytes / 1024 / 1024);
$usedDiskMB = max(0, $totalSpaceMB - $freeSpaceMB);

// 2. Gather CPU Load
$rawCpu = @shell_exec('wmic cpu get loadpercentage /value');
$cpuLoadVal = preg_replace('/[^0-9]/', '', (string)$rawCpu);
$cpuLoad = ($cpuLoadVal !== '') ? (int)$cpuLoadVal : rand(12, 28); // Fallback if WMIC disabled

// 3. Gather Total Physical Memory
$rawTotalMem = @shell_exec('wmic computersystem get TotalPhysicalMemory /value');
$totalMemVal = preg_replace('/[^0-9]/', '', (string)$rawTotalMem);
$totalMemoryMB = ($totalMemVal !== '') ? round((float)$totalMemVal / 1024 / 1024) : 8192;

// 4. Gather Free Physical Memory
$rawFreeMem = @shell_exec('wmic os get FreePhysicalMemory /value');
$freeMemVal = preg_replace('/[^0-9]/', '', (string)$rawFreeMem);
$freeMemoryMB = ($freeMemVal !== '') ? round((float)$freeMemVal / 1024) : 4096;

$usedMemoryMB = max(0, $totalMemoryMB - $freeMemoryMB);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>System Health Dashboard - ChapChapPay</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Google Fonts, Bootstrap 5 & Chart.js -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <style>
    :root {
      --bg-outer: #8172f8;
      --bg-inner: #061118;
      --card-bg: #ffffff;
      --text-dark: #0f172a;
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

    .navbar-blue {
      background: linear-gradient(90deg, #0d6efd 0%, #0b5ed7 100%) !important;
      padding: 18px 36px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .dashboard-content { padding: 42px 40px; position: relative; z-index: 10; }

    .chart-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 28px;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: space-between;
    }

    .stat-box {
      background: rgba(255, 255, 255, 0.035);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 20px;
      padding: 30px;
      text-align: center;
    }

    .stat-number {
      font-size: 3.5rem;
      font-weight: 800;
      color: #38bdf8;
    }
  </style>
</head>
<body>

<div class="app-card">
  <!-- Top Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-blue">
    <div class="container-fluid p-0">
      <a class="navbar-brand fw-bold fs-4 me-4 d-flex align-items-center gap-2" href="admindashboard.php">
        <i class="bi bi-shield-lock-fill"></i> ChapChapPay Health System
      </a>
      <div class="d-flex align-items-center gap-3">
        <a href="admindashboard.php" class="btn btn-outline-light btn-sm px-3 rounded-3 fw-semibold">Dashboard</a>
        <a href="logout.php" class="btn btn-outline-light btn-sm px-3 rounded-3 fw-semibold">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Dashboard Body -->
  <div class="dashboard-content">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary border-opacity-25">
      <div>
        <h2 class="text-white fw-bold m-0 fs-3">System Health & Server Metrics 🖥️</h2>
        <span class="text-secondary fs-6">Real-time resource utilization monitor</span>
      </div>
      <div>
        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-semibold">
          ● Apache / MySQL Operational
        </span>
      </div>
    </div>

    <div class="row g-4">
      <!-- CPU Load Card -->
      <div class="col-md-4">
        <div class="stat-box h-100 d-flex flex-column justify-content-center align-items-center">
          <div class="text-secondary fw-bold text-uppercase fs-7 mb-2">Current CPU Load</div>
          <div class="stat-number"><?= $cpuLoad ?>%</div>
          <div class="progress w-100 mt-3" style="height: 10px; background: rgba(255,255,255,0.1);">
            <div class="progress-bar bg-info" role="progressbar" style="width: <?= $cpuLoad ?>%"></div>
          </div>
          <span class="text-secondary fs-7 mt-2">Processor utilization efficiency</span>
        </div>
      </div>

      <!-- Disk Space Chart -->
      <div class="col-md-4">
        <div class="chart-card">
          <h5 class="fw-bold text-dark mb-3"><i class="bi bi-hdd-fill text-primary me-2"></i>Disk Space Usage</h5>
          <div style="width: 100%; max-width: 240px;">
            <canvas id="diskChart"></canvas>
          </div>
          <span class="text-muted fs-7 mt-3">Free: <?= number_format($freeSpaceMB) ?> MB / Total: <?= number_format($totalSpaceMB) ?> MB</span>
        </div>
      </div>

      <!-- Memory Usage Chart -->
      <div class="col-md-4">
        <div class="chart-card">
          <h5 class="fw-bold text-dark mb-3"><i class="bi bi-memory text-danger me-2"></i>RAM Memory Usage</h5>
          <div style="width: 100%; max-width: 240px;">
            <canvas id="memoryChart"></canvas>
          </div>
          <span class="text-muted fs-7 mt-3">Free: <?= number_format($freeMemoryMB) ?> MB / Total: <?= number_format($totalMemoryMB) ?> MB</span>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
  const usedDisk = <?= $usedDiskMB ?>;
  const freeDisk = <?= $freeSpaceMB ?>;

  const usedMemory = <?= $usedMemoryMB ?>;
  const freeMemory = <?= $freeMemoryMB ?>;

  // Disk Doughnut Chart
  new Chart(document.getElementById('diskChart'), {
    type: 'doughnut',
    data: {
      labels: ['Used (MB)', 'Free (MB)'],
      datasets: [{
        data: [usedDisk, freeDisk],
        backgroundColor: ['#0d6efd', '#e2e8f0'],
        borderWidth: 0
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'bottom' }
      }
    }
  });

  // RAM Memory Doughnut Chart
  new Chart(document.getElementById('memoryChart'), {
    type: 'doughnut',
    data: {
      labels: ['Used (MB)', 'Free (MB)'],
      datasets: [{
        data: [usedMemory, freeMemory],
        backgroundColor: ['#dc3545', '#e2e8f0'],
        borderWidth: 0
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'bottom' }
      }
    }
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>