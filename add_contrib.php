<?php
session_start();
include 'db.php';

// Only treasurer can access this
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'treasurer') {
    header("Location: register_login.php");
    exit();
}

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO contributions (user_id, amount, date, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $member_id, $amount, $date, $description);

    if ($stmt->execute()) {
        $message = "Contribution recorded successfully.";
    } else {
        $message = "Error saving contribution.";
    }
}

// Fetch members to populate dropdown
$members = $conn->query("SELECT id, name FROM users WHERE role = 'member' OR role = 'treasurer'");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Contribution</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h3>Add a Contribution</h3>

  <?php if (!empty($message)): ?>
    <div class="alert alert-info"><?php echo $message; ?></div>
  <?php endif; ?>

  <form method="POST" action="add_contrib.php">
    <div class="mb-3">
      <label for="member_id" class="form-label">Select Member</label>
      <select name="member_id" class="form-control" required>
        <option value="">-- Select Member --</option>
        <?php while($row = $members->fetch_assoc()): ?>
          <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="amount" class="form-label">Amount (KES)</label>
      <input type="number" class="form-control" id="amount" name="amount" min="0" step="50" required>
    </div>

    <div class="mb-3">
      <label for="date" class="form-label">Date</label>
      <input type="date" name="date" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Purpose/Description</label>
      <textarea name="description" class="form-control" rows="2"></textarea>
    </div>

    <button type="submit" class="btn btn-success">Save Contribution</button>
  </form>
</div>

</body>
</html>
