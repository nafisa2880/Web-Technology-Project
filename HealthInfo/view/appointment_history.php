<?php
session_start();
require 'db.php'; // Ensure this connects to your database

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch appointments for the logged-in user
$query = "SELECT doctor_name, appointment_date, status FROM appointments WHERE user_id = ? ORDER BY appointment_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment History</title>
</head>
<body>
    <h2>Appointment History</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Doctor Name</th>
                <th>Appointment Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($appointments)): ?>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                        <td><?php echo ucfirst($appointment['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No appointments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
