<?php
session_start();
require 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $condition = $_POST['condition'];
    $diagnosis_date = $_POST['diagnosis_date'];
    $notes = $_POST['notes'];

    $query = "INSERT INTO medical_history (user_id, condition, diagnosis_date, notes) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $user_id, $condition, $diagnosis_date, $notes);

    if ($stmt->execute()) {
        header("Location: medical_history.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medical History</title>
</head>
<body>
    <h2>Add Medical History</h2>
    <form action="add_medical_history.php" method="POST">
        <label for="condition">Condition:</label><br>
        <input type="text" id="condition" name="condition" required><br><br>

        <label for="diagnosis_date">Diagnosis Date:</label><br>
        <input type="date" id="diagnosis_date" name="diagnosis_date" required><br><br>

        <label for="notes">Notes:</label><br>
        <textarea id="notes" name="notes"></textarea><br><br>

        <button type="submit">Add Record</button>
    </form>
</body>
</html>
