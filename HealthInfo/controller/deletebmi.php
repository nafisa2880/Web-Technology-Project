<?php
include_once "db.php"; // Include the database connection
session_start();

// Check if the user is an admin and has posted the delete request
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && isset($_POST['bmi_id'])) {
    $bmi_id = $_POST['bmi_id'];
    
    // Delete the BMI record
    $stmt = $conn->prepare("DELETE FROM user_bmi WHERE id = ?");
    $stmt->bind_param("i", $bmi_id);
    $stmt->execute();
    $stmt->close();
    
    echo "<p>BMI record deleted successfully.</p>";
    echo "<a href='faq.php'>Back to FAQ</a>";
} else {
    echo "<p>You do not have permission to delete BMI records.</p>";
}

$conn->close();
?>
