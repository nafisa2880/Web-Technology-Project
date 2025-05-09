<?php
include_once 'db.php';
session_start();


if (!isset($_SESSION['role'])) {
    echo "Please log in to access this page.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $medication = $_POST['medication'];
    $reminder_time = $_POST['reminder_time'];
    $user_id = $_SESSION['user_id']; // Assume `user_id` is stored in session.

    if (empty($medication) || empty($reminder_time)) {
        echo "All fields are required.";
        exit;
    }
    $formatted_time = date("h:i A", strtotime($row['reminder_time']));
    echo $formatted_time;

    // Insert reminder into the database without using prepared statements
    $sql = "INSERT INTO reminders (user_id, medication, reminder_time) 
            VALUES ('$user_id', '$medication', '$reminder_time')";

    // if (mysqli_query($conn, $sql)) {
    //     echo "<script>
    //             alert('Reminder set successfully!');
    //             setTimeout(() => window.location.href = '../control/patientReminders.php', 2000); 
    //           </script>";
    // } else {
    //     echo "Error setting reminder.";
    // }
}

$conn->close();
?>
