<?php 
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO support_requests (user_id, subject, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $subject, $message);
    $stmt->execute();
    $stmt->close();

    $_SESSION['flash'] = "Support request submitted successfully.";
    header("Location: support_requests.php");
    exit();
}

// Fetch all support requests
$result = $conn->query("SELECT * FROM support_requests ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Support Requests - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding-top: 80px;
    }

    .navbar {
      width: 100%;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgba(0, 0, 0, 0.6);
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1000;
    }

    .logo {
      font-size: 1.5em;
      font-weight: bold;
      color: white;
    }

    .nav-links {
      display: flex;
      gap: 25px;
    }

    .nav-links a {
      color: white;
      text-decoration: none;
      font-weight: 700;
      transition: color 0.3s ease;
    }

    .nav-links a:hover {
      color: #f9c63f;
    }

    .container {
      max-width: 1000px;
      margin: auto;
    }

    h2 {
      margin-bottom: 20px;
    }

    .alert {
      margin-top: 15px;
    }

    table th, table td {
      vertical-align: middle;
    }
  </style>
</head>
<body>

<!-- Custom Navbar -->
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

<!-- Main Content -->
<div class="container mt-5">
  <h2>Support Requests</h2>

  <?php if (isset($_SESSION['flash'])): ?>
    <div class="alert alert-success">
      <?= htmlspecialchars($_SESSION['flash']) ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <?php if ($result && $result->num_rows > 0): ?>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Subject</th>
          <th>Message</th>
          <th>Submitted At</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= htmlspecialchars($row['user_id']) ?></td>
          <td><?= htmlspecialchars($row['subject']) ?></td>
          <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
          <td><?= $row['created_at'] ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No support requests found.</p>
  <?php endif; ?>
</div>

</body>
</html>
