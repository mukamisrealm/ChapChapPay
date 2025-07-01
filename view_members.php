<?php
session_start();
include 'db.php';

// Only treasurer can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'treasurer') {
    header("Location: register_login.php");
    exit();
}

// Fetch users who have logged in (i.e., last_login is not NULL)
$stmt = $conn->prepare("SELECT * FROM users WHERE role = 'member' ");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Members - ChapChapPay</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #f0f9ff, #cbebff); /* soft blue gradient */
      min-height: 100vh;
    }

    .members-container {
      background: white;
      margin: 40px auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      max-width: 900px;
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #007b5e;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    table thead {
      background-color: #007b5e;
      color: white;
    }

    table th, table td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    .back-btn {
      margin-top: 25px;
      display: block;
      text-align: center;
    }

    .back-btn a {
      background-color: #007b5e;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
    }

    .back-btn a:hover {
      background-color: #005a47;
    }
  </style>
</head>
<body>

<div class="members-container">
  <h2>Active Chama Members</h2>

  <?php if ($result->num_rows > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Last Login</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td><?php echo $row['last_login'] ? $row['last_login'] : 'Not logged in'; ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info text-center">No members have logged in yet.</div>
  <?php endif; ?>

  <div class="back-btn">
    <a href="treasurerdashboard.php">← Back to Dashboard</a>
  </div>
</div>

</body>
</html>

