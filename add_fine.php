<?php
session_start();
include 'db.php';

// Ensure only treasurer accesses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'treasurer') {
    header("Location: register_login.php");
    exit();
}

$message = '';

// Handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO fines (user_id, amount, fine_date, reason) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $member_id, $amount, $date, $reason);

    if ($stmt->execute()) {
        $message = "Fine recorded successfully.";
    } else {
        $message = "Failed to record fine.";
    }
}

// Fetch users
$members = $conn->query("SELECT id, name FROM users WHERE role IN ('member', 'treasurer')");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Fine</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  
<div class="container mt-5">
  <h3>Record a Fine</h3>

  <?php if (!empty($message)): ?>
    <div class="alert alert-info"><?php echo $message; ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label for="member_id" class="form-label">Select Member</label>
      <select name="member_id" class="form-control" required>
        <option value="">-- Select --</option>
        <?php while($row = $members->fetch_assoc()): ?>
          <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="amount" class="form-label">Amount (KES)</label>
      <input type="number" name="amount" class="form-control" step="50" min="0" required>
    </div>

    <div class="mb-3">
      <label for="date" class="form-label">Fine Date</label>
      <input type="date" name="date" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="reason" class="form-label">Reason</label>
      <textarea name="reason" class="form-control" rows="2" required></textarea>
    </div>

    <button type="submit" class="btn btn-danger">Save Fine</button>
  </form>
</div>
</body>
</html>
