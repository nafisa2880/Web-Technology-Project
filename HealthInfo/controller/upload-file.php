<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit();
}

$conn = new mysqli("localhost", "root", "", "healthinfo");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

$sql = "SELECT user_id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['user_id'];

$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_size = $_FILES['file']['size'];
    $file_type = pathinfo($file_name, PATHINFO_EXTENSION);

    if ($file_size > 5 * 1024 * 1024) {
        echo json_encode(["success" => false, "message" => "File size exceeds 5MB limit."]);
        exit();
    }

    $upload_dir = "uploads/medical_files/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_path = $upload_dir . uniqid() . "_" . basename($file_name);

    if (move_uploaded_file($file_tmp, $file_path)) {
        $sql = "INSERT INTO medical_files (user_id, uploader_role, file_name, file_path, file_type, upload_date) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $user_id, $role, $file_name, $file_path, $file_type);

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "File uploaded successfully.",
                "file" => [
                    "file_id" => $stmt->insert_id,
                    "file_name" => $file_name,
                    "file_path" => $file_path,
                    "user_id" => $user_id,
                    "uploader_role" => $role,
                    "upload_date" => date("Y-m-d H:i:s")
                ]
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to save file details to the database."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Failed to upload file."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No file uploaded or invalid request."]);
}

$conn->close();
