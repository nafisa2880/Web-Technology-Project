<?php
session_start();

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
//     header("Location: ../view/login.html");
//     exit();
// }
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    echo "Please log in to access this page";
    echo "<br><a href='../view/login.html'>Login Here</a>";
    exit;
}


$conn = new mysqli("localhost", "root", "", "healthinfo");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Fetch all appointments 
$sql = "SELECT appointment_id, doctor, appointment_date, appointment_time 
        FROM appointments 
        WHERE user_id = ? 
        ORDER BY appointment_date, appointment_time";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const appointmentsTable = document.getElementById("appointments-table");

            // Function to delete an appointment
            function deleteAppointment(appointmentId) {
                if (confirm("Are you sure you want to delete this appointment?")) {
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "../controller/delete.php", true);
                    xhr.setRequestHeader("Content-Type", "application/json");

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            alert(response.message);

                            if (response.success) {
                                document.getElementById(`appointment-row-${appointmentId}`).remove();
                            }
                        }
                    };

                    xhr.send(JSON.stringify({ appointment_id: appointmentId }));
                }
            }

            // Add event listeners for delete buttons
            document.querySelectorAll(".delete-button").forEach(button => {
                button.addEventListener("click", function () {
                    const appointmentId = this.dataset.appointmentId;
                    deleteAppointment(appointmentId);
                });
            });
        });
    </script>
</head>
<body>
    <!-- Navigation Bar -->
    <table width="100%" bgcolor="#518d9c">
        <tr>
            <td>
                <h2>My Appointments</h2>
            </td>
            <td align="right">
                <a href="../view/index.html">Home</a> |
                <a href="/Health%20Info/controller/logout.php">Logout</a>
            </td>
        </tr>
    </table>

    <!-- Appointments List -->
    <h3 align="center">Upcoming Appointments</h3>
    <table id="appointments-table" border="1" cellpadding="10" width="80%" align="center">
        <tr>
            <th>Doctor</th>
            <th>Date</th>
            <th>Time</th>
            <th>Actions</th>
        </tr>
        <?php if (!empty($appointments)) : ?>
            <?php foreach ($appointments as $appointment) : ?>
                <tr id="appointment-row-<?php echo $appointment['appointment_id']; ?>">
                    <td><?php echo htmlspecialchars($appointment['doctor']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                    <td>
                        <a href="../controller/edit-appointment.php?id=<?php echo $appointment['appointment_id']; ?>" 
                           style="padding: 5px 10px; background-color: #518d9c; color: white; text-decoration: none; border-radius: 4px;">Edit</a>
                        <button class="delete-button" data-appointment-id="<?php echo $appointment['appointment_id']; ?>" 
                                style="padding: 5px 10px; background-color: red; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            Delete
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="4" align="center">No appointments found.</td>
            </tr>
        <?php endif; ?>
    </table>
    <!-- Bottom Navigation Bar -->

    <div class="footer">
        <a href="contact.html">Contact Us</a>
        <a href="privacypolicy.html">Privacy Policy</a>
        <a href="service.html">Terms of Service</a>
    </div>
</body>
</html>
