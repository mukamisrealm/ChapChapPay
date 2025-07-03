<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: register_login.php");
    exit();
}
$role = $_SESSION['role'];
if ($role === 'treasurer') {
    include 'navbar_treasurer.php';
} elseif ($role === 'member') {
    include 'navbar_member.php';
} elseif ($role === 'admin') {
    include 'navbar_admin.php';
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

// Fetch fines based on role
if ($role === 'treasurer') {
    // Treasurer sees all fines
    $stmt = $conn->prepare("SELECT fines.amount, fines.fine_date, fines.reason, users.name 
                            FROM fines 
                            JOIN users ON fines.user_id = users.id 
                            ORDER BY fines.fine_date DESC");
} else {
    // Member sees only their fines
    $stmt = $conn->prepare("SELECT amount, fine_date, reason 
                            FROM fines 
                            WHERE user_id = ? 
                            ORDER BY fine_date DESC");
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Fines</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h3 class="mb-4">Fine Records</h3>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered table-striped">
      <thead class="thead-dark">
        <tr>
          <?php if ($role === 'treasurer') echo "<th>Member Name</th>"; ?>
          <th>Amount (KES)</th>
          <th>Date</th>
          <th>Reason</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <?php if ($role === 'treasurer') echo "<td>" . htmlspecialchars($row['name']) . "</td>"; ?>
            <td><?php echo htmlspecialchars($row['amount']); ?></td>
            <td><?php echo htmlspecialchars($row['fine_date']); ?></td>
            <td><?php echo htmlspecialchars($row['reason']); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No fines found.</div>
  <?php endif; ?>

  <div class="mt-4 text-center">
    <a href="<?php echo ($role === 'treasurer') ? 'treasurerdashboard.php' : 'memberdashboard.php'; ?>" class="btn btn-secondary">Back to Dashboard</a>
  </div>
</div>
</body>
</html>
