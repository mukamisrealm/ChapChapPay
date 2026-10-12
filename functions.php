function logActivity($username, $activity) {
    $conn = new mysqli("localhost", "root", "", "chapchap");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO activity_logs (username, activity) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $activity);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}
