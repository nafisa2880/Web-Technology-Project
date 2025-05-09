<?php
include_once 'db.php';
session_start();


// Check for valid session and admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Unauthorized access.";
    exit;
}

// Check if the ID is provided via GET
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the ID to avoid SQL injection

    // Directly execute the delete query
    $sql = "DELETE FROM reminders WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Reminder deleted successfully.'); window.location.href = '../controller/reminderTable.php';</script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
