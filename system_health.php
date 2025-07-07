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
   body {
  font-family: 'Segoe UI', sans-serif;
  background: url('photos\Teal.jpeg') no-repeat center center fixed;
  background-size: cover;
  margin: 0;
  padding: 20px;
  color: white;
}

    h1 {
      text-align: center;
      color: #333;
    }
    .dashboard {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      justify-content: center;
      margin-top: 30px;
    }
    .card {
      background: white;
      border-radius: 10px;
      padding: 20px;
      width: 280px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      text-align: center;
    }
    .card h2 {
      margin-bottom: 10px;
      font-size: 20px;
      color: #555;
    }
    .stat {
      font-size: 2em;
      color: #009688;
    }
    canvas {
      max-width: 100%;
    }
  </style>
</head>
<body>
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
