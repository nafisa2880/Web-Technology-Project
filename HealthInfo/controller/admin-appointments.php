<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Please log in to access this page";
    echo "<br><a href='../view/login.html'>Login Here</a>";
    exit;
}

$conn = new mysqli("localhost", "root", "", "Healthinfo");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all appointments
$appointments = [];
$sql = "SELECT * FROM appointments";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Appointment Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .header {
            background-color: #518d9c;
            color: white;
            padding: 10px;
            text-align: center;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #518d9c;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .actions button, .actions a {
            background-color: #518d9c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            display: inline-block;
        }
        .actions button:hover, .actions a:hover {
            background-color: #406c78;
        }
        .actions .delete {
            background-color: red;
        }
        .actions .delete:hover {
            background-color: darkred;
        }
        .nav {
            text-align: right;
            margin: 10px;
        }
        .nav a {
            color: #518d9c;
            text-decoration: none;
            margin-left: 10px;
        }
        .nav a:hover {
            text-decoration: underline;
        }
        h3 {
            text-align: center;
            color: #518d9c;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Function to delete an appointment via AJAX
            function deleteAppointment(appointmentId) {
                if (confirm("Are you sure you want to delete this appointment?")) {
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "/Health%20Info/controller/delete.php", true);
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

            // Add event listeners to delete buttons
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
    <div class="header">
        <h2>Admin Appointment Management</h2>
    </div>

    <div class="nav">
        <a href="/Health%20Info/view/home.php">Dashboard</a> |
        <a href="/Health%20Info/controller/logout.php">Logout</a>
    </div>

    <h3>All Appointments</h3>
    <table>
        <tr>
            <th>Appointment ID</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Date</th>
            <th>Time</th>
            <th>Actions</th>
        </tr>
        <?php if (!empty($appointments)) : ?>
            <?php foreach ($appointments as $appointment) : ?>
                <tr id="appointment-row-<?php echo $appointment['appointment_id']; ?>">
                    <td><?php echo htmlspecialchars($appointment['appointment_id']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['doctor']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                    <td class="actions">
                        <a href="/Health%20Info/controller/edit-appointment.php?id=<?php echo $appointment['appointment_id']; ?>">Edit</a>
                        <button class="delete-button delete" data-appointment-id="<?php echo $appointment['appointment_id']; ?>">
                            Delete
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="6" align="center">No appointments found.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
