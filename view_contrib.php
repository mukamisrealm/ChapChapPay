<?php
session_start();
if (!isset($_SESSION['name'])) {
    header("Location: register_login.php");
    exit();
}

// Include DB
include 'db.php'; // adjust path if needed

// Fetch contributions
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role === 'member' || $role === 'treasurer') {
    $stmt = $conn->prepare("SELECT amount, contribution_date, description FROM contributions WHERE user_id = ? ORDER BY contribution_date DESC");
    $stmt->bind_param("i", $user_id);
} else {
    // Admin or treasurer viewing all
    $stmt = $conn->prepare("SELECT users.name, amount, contribution_date, description FROM contributions JOIN users ON contributions.user_id = users.id ORDER BY contribution_date DESC");
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Contributions</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4">Contribution History</h2>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered table-hover bg-white">
      <thead class="thead-dark">
        <tr>
          <?php if ($role === 'admin' || $role === 'treasurer') echo "<th>Member Name</th>"; ?>
          <th>Amount (KES)</th>
          <th>Date</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <?php if ($role === 'admin' || $role === 'treasurer') echo "<td>" . htmlspecialchars($row['name']) . "</td>"; ?>
            <td><?php echo htmlspecialchars($row['amount']); ?></td>
            <td><?php echo htmlspecialchars($row['contribution_date']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No contributions found.</div>
  <?php endif; ?>
   <div class="text-center mt-4">
    <a href="<?php echo ($_SESSION['role'] === 'treasurer') ? 'treasurerdashboard.php' : 'memberdashboard.php'; ?>" class="btn btn-secondary">Back to Dashboard</a>
  </div>
</div>

</body>
</html>

