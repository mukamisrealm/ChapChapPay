<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: register_login.php");
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $request_type = $_POST['request_type'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO requests (user_id, request_type, reason) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $request_type, $reason);

    if ($stmt->execute()) {
        $message = "Request submitted successfully!";
    } else {
        $message = "Error submitting request.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Make a Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>Make a Request</h3>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="request_type" class="form-label">Request Type</label>
            <select name="request_type" class="form-control" required>
                <option value="">-- Select Type --</option>
                <option value="loan">Loan</option>
                <option value="skip_payment">Skip Payment</option>
                <option value="half_payment">Half Payment</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="reason" class="form-label">Reason </label>
            <textarea name="reason" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Submit Request</button>
    </form>
</div>
</body>
</html>
