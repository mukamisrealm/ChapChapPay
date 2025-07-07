<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "chapchap");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed."]));
}

$result = $conn->query("SELECT * FROM activity_logs ORDER BY timestamp DESC");

$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

echo json_encode($logs);
