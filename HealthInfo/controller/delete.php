<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

$conn = new mysqli("localhost", "root", "", "Healthinfo");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $appointment_id = $data['appointment_id'] ?? null;

    if ($appointment_id) {
        $check_sql = "SELECT * FROM appointments WHERE appointment_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $appointment_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $sql = "DELETE FROM appointments WHERE appointment_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $appointment_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Appointment deleted successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete appointment.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Appointment not found.']);
        }
        $check_stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid appointment ID.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>
