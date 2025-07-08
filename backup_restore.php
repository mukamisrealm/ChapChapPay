<!-- backup.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ChapChapPay - Admin Backup</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #66bcb1;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .backup-card {
      background: #1f1f2e;
      border-radius: 20px;
      padding: 15px;
      width: 950px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.6);
    }

    .backup-card h2 {
      font-size: 22px;
      margin-bottom: 20px;
      color: #ffffff;
    }

    .donut-chart {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      background: conic-gradient(#00ffc3 0% 75%, #4c4cff 75% 90%, #7427d1 90% 100%);
      margin: auto;
      position: relative;
    }

    .donut-chart::before {
      content: '';
      position: absolute;
      top: 25%;
      left: 25%;
      width: 50%;
      height: 50%;
      background: #1f1f2e;
      border-radius: 50%;
    }

    .report-legend {
      margin: 20px 0;
    }

    .report-legend p {
      margin: 5px 0;
      font-size: 14px;
    }

   .overview-item {
  padding: 12px 20px;
  border-radius: 30px;
  margin-bottom: 15px;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: inset 0 0 5px rgba(255,255,255,0.1), 0 4px 6px rgba(0,0,0,0.4);
  transition: transform 0.2s ease;
  cursor: default;
}

.overview-item:hover {
  transform: scale(1.02);
}


    .tasks { background-color: #082c59; color: #44baff; }
    .attachments { background-color: #3b1c4b; color: #d881f3; }
    .assets { background-color: #3e4444; color: #c2c2c2; }
    .charts { background-color: #004d40; color: #64ffda; }

    .overview-item span {
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="backup-card">
    <h2>🛡️ Backup Reports</h2>

    <div class="donut-chart"></div>

    <div class="report-legend">
      <p><span style="color:#00ffc3">●</span> 5 Successful backups</p>
      <p><span style="color:#4c4cff">●</span> 0 warnings</p>
      <p><span style="color:#7427d1">●</span> 0 sync issues</p>
    </div>

    <h2>Overview</h2>

    <div class="overview">
      <div class="overview-item tasks">
        <span>📝 Group Records</span><span>5873</span>
      </div>
      <div class="overview-item attachments">
        <span>📎 Attachments</span><span>1459</span>
      </div>
      <div class="overview-item assets">
        <span>💼 Shared Assets</span><span>204</span>
      </div>
      <div class="overview-item charts">
        <span>📊 Reports Exported</span><span>12</span>
      </div>
    </div>
  </div>
</body>
</html>
