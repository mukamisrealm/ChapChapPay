<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'treasurer') {
    header("Location: register_login.php");
    exit();
}

// Fetch only members who have logged in
$stmt = $conn->prepare("SELECT name, email, role, last_login FROM users WHERE (role = 'member' OR role = 'treasurer') AND last_login IS NOT NULL ORDER BY last_login DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Logged-in Members</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h3 class="mb-4">Members Who Have Logged In</h3>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered bg-white">
      <thead class="thead-dark">
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
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo ucfirst($row['role']); ?></td>
            <td><?php echo htmlspecialchars($row['last_login']); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No members have logged in yet.</div>
  <?php endif; ?>

  <div class="text-center mt-4">
    <a href="treasurerdashboard.php" class="btn btn-secondary">Back to Dashboard</a>
  </div>
</div>

</body>
</html>
