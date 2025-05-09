<?php
include_once 'db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'patient') {
    echo "Unauthorized access.";
    exit;
}

$patient_name = $_SESSION['username']; 

$sql = "SELECT * FROM reminders WHERE patient_name = '$patient_name'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Medication Reminders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
            color:rgb(88, 164, 208);
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            text-align: center;
            padding: 10px;
        }

        th {
            background-color:rgb(93, 117, 187);
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .nav-links {
            text-align: center;
            margin-top: 20px;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            background-color: #007BFF; /* Blue color */
            padding: 10px 20px;
            border-radius: 5px;
            margin: 10px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .nav-links a:hover {
            background-color: #0056b3; /* Darker blue */
        }
    </style>
</head>
<body>
    <h1>Your Medication Reminders</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Date</th>
            <th>Time</th>
            <th>Advice</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                echo "<td>" . htmlspecialchars($row['doctor_advice'] ?? "No advice yet") . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No reminders found.</td></tr>";
        }
        ?>
    </table>
    <div class="nav-links">
        <a href="../view/medication.php">Back to Set Reminder</a>
        <a href="../view/index1.html">Back to Home</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
