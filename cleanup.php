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

  // Sample table: contributions — change to fit your actual table
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
  <meta charset="UTF-8">
  <title>Clean Old Data</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: #66bcb1;
      font-family: 'Segoe UI', sans-serif;
      padding-top: 100px;
      color: #333;
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

    .card {
      max-width: 850px;
      background-color: #1f1f2e;
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
      background-color: rgb(142, 106, 186);
    }

    .status-message {
      margin-top: 20px;
      font-weight: bold;
      color: green;
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

  <!-- Clean Data Section -->
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
