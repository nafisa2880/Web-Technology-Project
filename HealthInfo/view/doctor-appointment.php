<?php
session_start();


if (!isset($_SESSION['role'])) {
    echo "Please log in to access this page";
    echo "<br><a href='../view/login.html'>Login Here</a>";
    exit;
}


$conn = new mysqli("localhost", "root", "", "healthinfo");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$username = $_SESSION['username'];
$sql = "SELECT CONCAT(first_name, ' ', last_name) AS full_name FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $doctor = $result->fetch_assoc();
    $doctor_name = $doctor['full_name'];
} else {
    die("Doctor not found.");
}
$stmt->close();


$sql = "SELECT appointment_id, patient_name, appointment_date, appointment_time 
        FROM appointments 
        WHERE doctor = ? 
        ORDER BY appointment_date, appointment_time";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $doctor_name);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <!-- Top Navigation Bar -->

     <div class="header">
        <h2>Healthcare System Dashboard</h2>
    </div> 
<!-- Navigation Bar -->

    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="profile-view">Profile</a>
        <a href="index.html">Index</a>
        <a href="search.html">Search</a>
        <a href="tip.php">Message</a>

    </div>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
        }
        .header {
            background-color: #518d9c;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .header a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }
        .header a:hover {
            text-decoration: underline;
        }
        .container {
            margin: 30px auto;
            width: 90%;
            max-width: 1200px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #518d9c;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>My Appointments</h1>
        <a href="/Health%20Info/view/home.php">Dashboard</a> |
        <a href="/Health%20Info/controller/logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Appointments with Dr. <?php echo htmlspecialchars($doctor_name); ?></h2>
        <table>
            <tr>
                <th>Appointment ID</th>
                <th>Patient Name</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
            <?php if (!empty($appointments)) : ?>
                <?php foreach ($appointments as $appointment) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['appointment_id']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4" align="center">No appointments found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
    <!-- Bottom Navigation Bar -->

    <div class="footer">
        <a href="contact.html">Contact Us</a>
        <a href="privacypolicy.html">Privacy Policy</a>
        <a href="service.html">Terms of Service</a>
    </div>
</body>
</html>
