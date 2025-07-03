<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // This means the form was submitted
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Insert the support request into the database
    $stmt = $conn->prepare("INSERT INTO support_requests (user_id, subject, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $subject, $message);
    $stmt->execute();
    $stmt->close();

    // Optional: Flash message or redirect
    $_SESSION['flash'] = "Support request submitted successfully.";
    header("Location: support_requests.php");
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

// Fetch all support requests (GET request)
$result = $conn->query("SELECT * FROM support_requests ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Support Requests - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Support Requests</h2>

    <?php
    if (isset($_SESSION['flash'])) {
        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['flash']) . '</div>';
        unset($_SESSION['flash']);
    }
    ?>

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
