<?php
include_once 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, title, category, content FROM HealthTips";
$result = $conn->query($sql);

$tips = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tips[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($tips);

$conn->close();
?>
