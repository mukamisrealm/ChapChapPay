<?php
session_start();
include 'db.php';

// Allow only treasurer
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'treasurer') {
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

$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'];
    $amount = $_POST['amount'];
    $payout_date = $_POST['payout_date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO payouts (user_id, amount, payout_date, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $member_id, $amount, $payout_date, $status);

    if ($stmt->execute()) {
        $message = "Payout scheduled successfully.";
    } else {
        $message = "Failed to schedule payout.";
    }
}

// Fetch members
$members = $conn->query("SELECT id, name FROM users WHERE role = 'member' OR role = 'treasurer'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Schedule Payout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h3>Schedule a Payout</h3>

  <?php if (!empty($message)): ?>
    <div class="alert alert-info"><?php echo $message; ?></div>
  <?php endif; ?>

  <form method="POST" action="schedule_payout.php">
    <div class="mb-3">
      <label for="member_id" class="form-label">Member</label>
      <select name="member_id" class="form-control" required>
        <option value="">-- Select Member --</option>
        <?php while ($row = $members->fetch_assoc()): ?>
          <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="amount" class="form-label">Amount (KES)</label>
      <input type="number" name="amount" class="form-control" required min="0" step="50">
    </div>

    <div class="mb-3">
      <label for="payout_date" class="form-label">Payout Date</label>
      <input type="date" name="payout_date" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="status" class="form-label">Status</label>
      <select name="status" class="form-control" required>
        <option value="pending">Pending</option>
        <option value="completed">Completed</option>
      </select>
    </div>

    <button type="submit" class="btn btn-success">Schedule Payout</button>
  </form>
</div>

</body>
</html>
