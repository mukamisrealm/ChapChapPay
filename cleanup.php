<?php
// Handle the POST request to clean data
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Include your database connection
  $conn = new mysqli("localhost", "root", "", "chapchap"); // adjust your DB credentials

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Sample table: transactions — change to fit your database table and column
  $sql = "DELETE FROM contributions WHERE contribution_date < NOW() - INTERVAL 1.5 YEAR";
  if ($conn->query($sql) === TRUE) {
    $message = "✅ Old records cleaned successfully.";
  } else {
    $message = "❌ Error: " . $conn->error;
  }

  $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Clean Old Data</title>
  <style>
    body {
      background: #66bcb1;
      font-family: 'Segoe UI', sans-serif;
      padding: 100px;
      color: #333;
    }

    .card {
      max-width: 850px;
      background-color:  #1f1f2e;
      border-radius: 15px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      margin: auto;
      padding: 90px;
    }

    h2 {
      color: white;
      font-size: 2em;
    }

    p {
      font-size: 1.3em;
     color: rgb(142, 106, 186); 
    }

    .info {
      margin-top: 15px;
      color: #e65100;
      font-weight: 500;
    }

    .btn-clean {
      background-color: #7427d1;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 8px;
      font-size: 1em;
      margin-top: 20px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .btn-clean:hover {
      background-color:rgb(142, 106, 186);
    }

    .status-message {
      margin-top: 20px;
      font-weight: bold;
      color: green;
    }
  </style>
</head>
<body>

<div class="card">
  <h2>🧹 Clean Old Data</h2>
  <p>Remove outdated or inactive records to keep your system lean and fast.</p>
  <p class="info">This action will permanently delete records older than 1 year.</p>

  <form method="POST" onsubmit="return confirmCleanup();">
    <button type="submit" class="btn-clean">Clean Now</button>
  </form>

  <?php if ($message): ?>
    <div class="status-message"><?php echo $message; ?></div>
  <?php endif; ?>
</div>

<script>
  function confirmCleanup() {
    return confirm("Are you sure you want to delete old records? This cannot be undone.");
  }
</script>

</body>
</html>
