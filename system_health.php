<?php
// Gather system health stats using WMIC (for Windows/XAMPP)

$cpuLoad = trim(shell_exec('wmic cpu get loadpercentage /value'));
$cpuLoad = str_replace(["\r", "\n", "LoadPercentage="], '', $cpuLoad);

$freeSpace = disk_free_space("C:") / 1024 / 1024;
$totalSpace = disk_total_space("C:") / 1024 / 1024;

$totalMemory = trim(shell_exec("wmic computersystem get TotalPhysicalMemory /value"));
$totalMemory = str_replace(["\r", "\n", "TotalPhysicalMemory="], '', $totalMemory);
$totalMemoryMB = round($totalMemory / 1024 / 1024);

$freeMemory = trim(shell_exec("wmic os get FreePhysicalMemory /value"));
$freeMemory = str_replace(["\r", "\n", "FreePhysicalMemory="], '', $freeMemory);
$freeMemoryMB = round($freeMemory / 1024);

$usedDisk = round($totalSpace - $freeSpace);
$usedMemory = $totalMemoryMB - $freeMemoryMB;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>System Health Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #66bcb1;
      margin: 0;
      padding-top: 80px;
      color: white;
    }

    /* Navbar Styles */
    .navbar {
      width: 100%;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgba(255, 255, 255, 0.08);
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1000;
    }

    .logo {
      font-size: 1.5em;
      font-weight: bold;
      color: #333;
    }

    .nav-links {
      display: flex;
      gap: 25px;
    }

    .nav-links a {
      color: #333;
      text-decoration: none;
      font-weight: 700;
      transition: color 0.3s ease;
    }

    .nav-links a:hover {
      color:darkorange;
    }

    /* Page Header */
    h1 {
      text-align: center;
      color: #fff;
      margin-bottom: 20px;
    }

    .dashboard {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      justify-content: center;
      margin-top: 20px;
      padding: 0 20px;
    }

    .card {
      background: #1f1f2e;
      border-radius: 8px;
      padding: 10px;
      width: 280px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      text-align: center;
    }

    .card h2 {
      margin-bottom: 10px;
      font-size: 20px;
      color: white;
    }

    .stat {
      font-size: 2em;
      color: #00e0c6;
    }

    canvas {
      max-width: 100%;
    }
  </style>
</head>
<body>

  <!-- Navigation Bar -->
<div class="navbar">
  <div class="logo">ChapChapPay</div>
  <div class="nav-links">
    <a href="admindashboard.php">Dashboard</a>
    <a href="index.php">Home</a>
    <a href="#about">About</a>
    <a href="contact_us.php">Contact</a>
    <a href="help.php">Help</a>
  </div>
</div>


  <!-- System Health Dashboard -->
  <h1>System Health Dashboard</h1>
  <div class="dashboard">
    <div class="card">
      <h2>CPU Load</h2>
      <div class="stat"><?php echo $cpuLoad; ?>%</div>
    </div>
    <div class="card">
      <h2>Disk Usage</h2>
      <canvas id="diskChart"></canvas>
    </div>
    <div class="card">
      <h2>Memory Usage</h2>
      <canvas id="memoryChart"></canvas>
    </div>
  </div>

  <script>
    const diskChart = document.getElementById('diskChart');
    const memoryChart = document.getElementById('memoryChart');

    const usedDisk = <?= $usedDisk ?>;
    const freeDisk = <?= round($freeSpace) ?>;

    const usedMemory = <?= $usedMemory ?>;
    const freeMemory = <?= $freeMemoryMB ?>;

    new Chart(diskChart, {
      type: 'doughnut',
      data: {
        labels: ['Used', 'Free'],
        datasets: [{
          data: [usedDisk, freeDisk],
          backgroundColor: ['#ff9800', '#4caf50']
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'bottom' },
          title: { display: true, text: 'Disk Space (MB)' }
        }
      }
    });

    new Chart(memoryChart, {
      type: 'doughnut',
      data: {
        labels: ['Used', 'Free'],
        datasets: [{
          data: [usedMemory, freeMemory],
          backgroundColor: ['#e91e63', '#2196f3']
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'bottom' },
          title: { display: true, text: 'Memory (MB)' }
        }
      }
    });
  </script>
</body>
</html>
