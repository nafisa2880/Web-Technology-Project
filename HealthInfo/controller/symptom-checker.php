<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(["error" => "Unauthorized access."]);
    exit();
}

$conn = new mysqli("localhost", "root", "", "Healthinfo");

if ($conn->connect_error) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["error" => "Database connection failed."]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$symptom = trim($data['symptom'] ?? "");

if (empty($symptom)) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(["error" => "Symptom is required."]);
    exit();
}

$sql = "SELECT condition_name, advice FROM symptoms_conditions WHERE symptom_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $symptom);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    echo json_encode([
        "condition_name" => $row['condition_name'],
        "advice" => $row['advice']
    ]);
} else {
    echo json_encode([
        "condition_name" => "No condition found.",
        "advice" => "Please consult a doctor for further assistance."
    ]);
}

$stmt->close();
$conn->close();
?>
