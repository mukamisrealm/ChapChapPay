<?php
session_start();
include 'db.php';

// Only Admin/Treasurer can trigger the random shuffle
if (!isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ['treasurer', 'admin'])) {
    header("Location: register_login.php");
    exit();
}

// 1. Ensure payout_queue exists
$create_table_sql = "
CREATE TABLE IF NOT EXISTS payout_queue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    queue_position INT NOT NULL,
    status ENUM('pending', 'paid') DEFAULT 'pending',
    scheduled_date DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$conn->query($create_table_sql);

// 2. Clear existing queue
$conn->query("TRUNCATE TABLE payout_queue");

// 3. Fetch members
$result = $conn->query("SELECT id FROM users WHERE LOWER(role) = 'member'");
$members = [];
while ($row = $result->fetch_assoc()) {
    $members[] = $row['id'];
}

if (!empty($members)) {
    shuffle($members);

    $start_date = new DateTime();
    $position = 1;

    $stmt = $conn->prepare("INSERT INTO payout_queue (user_id, queue_position, status, scheduled_date) VALUES (?, ?, 'pending', ?)");

    foreach ($members as $member_id) {
        $payout_date = (clone $start_date)->modify("+" . ($position - 1) . " month")->format('Y-m-d');
        $stmt->bind_param("iis", $member_id, $position, $payout_date);
        $stmt->execute();
        $position++;
    }

    $_SESSION['flash'] = "Random rotational queue generated successfully!";
} else {
    $_SESSION['flash'] = "No members found to generate a queue.";
}

header("Location: view_payout.php");
exit();
?>