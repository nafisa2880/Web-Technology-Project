<?php
include_once 'db.php';
session_start();


if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'doctor' && $_SESSION['role'] != 'admin')) {
    echo "Unauthorized access.";
    exit;
}

$role = $_SESSION['role'];

$sql = "SELECT * FROM reminders";
$result = $conn->query($sql);

echo "<h1>Medication Reminders</h1>";

echo "<table border='1'>
        <tr>
            <th>ID</th>
            <th>Patient Name</th>
            <th>Title</th>
            <th>Description</th>
            <th>Date</th>
            <th>Time</th>
            <th>Advice</th>
            <th>Actions</th>
        </tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        
        // Displaying the data from the database
        echo "<td>" . htmlspecialchars($row['id']) . "</td>"; // Reminder ID
        echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>"; // Patient Name
        echo "<td>" . htmlspecialchars($row['title']) . "</td>"; // Medication Title
        echo "<td>" . htmlspecialchars($row['description']) . "</td>"; // Description
        echo "<td>" . htmlspecialchars($row['date']) . "</td>"; // Date
        echo "<td>" . htmlspecialchars($row['time']) . "</td>"; // Time
        echo "<td>" . htmlspecialchars($row['advice'] ?? "None") . "</td>"; // Advice (if available)
        echo "<td class='actions'>";

        // Doctor: Add advice (only if advice is not yet added)
        if ($role == 'doctor' && empty($row['advice'])) {
            echo "<a href='../control/addAdvice.php?id=" . $row['id'] . "'>Add Advice</a>";
        }

        // Admin: Delete reminder
        if ($role == 'admin') {
            echo "<a href='../control/deleteReminder.php?id=" . $row['id'] . "'>Delete</a>";
        }
        
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>No reminders found.</td></tr>"; // Adjusted to match number of columns


}

echo "</table>";

echo "<br>";
echo "<a href='../view/index.html' style='text-decoration: none; font-size: 16px; color: blue;'>Back to Home Page</a>";
$conn->close();
?>
