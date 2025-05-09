<?php
session_start();
require 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch medical history for the logged-in user
$query = "SELECT * FROM medical_history WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical History</title>
</head>
<body>
    <h2>Medical History</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Condition</th>
            <th>Diagnosis Date</th>
            <th>Notes</th>
            <th>Added On</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['condition']; ?></td>
                <td><?php echo $row['diagnosis_date']; ?></td>
                <td><?php echo $row['notes']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
