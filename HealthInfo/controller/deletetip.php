<?php
// Include database connection
include_once 'db.php';

// Start the session
session_start();

// Check if the user is logged in and has a role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "You do not have permission to delete tips.";
    exit;
}

// Check if the id is passed
if (isset($_GET['id'])) {
    $tip_id = $_GET['id'];

    // SQL query to delete the tip by its ID
    $sql = "DELETE FROM HealthTips WHERE id = $tip_id";

    if ($conn->query($sql) === TRUE) {
        echo "Tip deleted successfully. <a href='../view/healthtips.php'>Back to Health Tips</a>";
    } else {
        echo "Error deleting tip: " . $conn->error;
    }
} else {
    echo "No tip ID provided.";
}

// Close the database connection
$conn->close();
?>
