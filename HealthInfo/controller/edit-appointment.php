<?php
session_start();

$conn = new mysqli("localhost", "root", "", "Healthinfo");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: /Health%20Info/view/login.html");
    exit();
}

// Get user role
$role = $_SESSION['role'];

if (isset($_GET['id'])) {
    $appointment_id = intval($_GET['id']);

    $sql = "SELECT * FROM appointments WHERE appointment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $appointment = $result->fetch_assoc();
    } else {
        die("Appointment not found.");
    }
} else {
    die("No appointment ID provided.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_name = trim($_POST['patient']);
    $doctor = trim($_POST['doctor']);
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    // PHP Server-Side Validation
    $errors = [];
    if (empty($patient_name)) {
        $errors[] = "Patient name is required.";
    }
    if (empty($doctor)) {
        $errors[] = "Doctor selection is required.";
    }
    if (empty($appointment_date) || strtotime($appointment_date) < strtotime(date("Y-m-d"))) {
        $errors[] = "Appointment date is required and cannot be in the past.";
    }
    if (empty($appointment_time)) {
        $errors[] = "Appointment time is required.";
    }

    if (empty($errors)) {
        $sql = "UPDATE appointments SET 
                patient_name = ?, 
                doctor = ?, 
                appointment_date = ?, 
                appointment_time = ?
                WHERE appointment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $patient_name, $doctor, $appointment_date, $appointment_time, $appointment_id);

        if ($stmt->execute()) {
            if ($role === 'Admin') {
                echo "<script>alert('Appointment updated successfully!'); window.location.href = '/Health%20Info/controller/admin-appointments.php';</script>";
            } else {
                echo "<script>alert('Appointment updated successfully!'); window.location.href = '/Health%20Info/view/my-appointment.php';</script>";
            }
        } else {
            echo "Error updating record: " . $stmt->error;
        }

        $stmt->close();
    } else {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
    <script>
        function validateForm() {
            let isValid = true;

            // Validate Patient Name
            const patientName = document.querySelector('[name="patient"]').value.trim();
            const patientNameError = document.getElementById('patientNameError');
            if (patientName === "") {
                patientNameError.textContent = "Patient name is required.";
                isValid = false;
            } else {
                patientNameError.textContent = "";
            }

            // Validate Doctor
            const doctor = document.querySelector('[name="doctor"]').value;
            const doctorError = document.getElementById('doctorError');
            if (doctor === "") {
                doctorError.textContent = "Doctor selection is required.";
                isValid = false;
            } else {
                doctorError.textContent = "";
            }

            // Validate Appointment Date
            const appointmentDate = document.querySelector('[name="appointment_date"]').value;
            const appointmentDateError = document.getElementById('appointmentDateError');
            const today = new Date().toISOString().split('T')[0];
            if (appointmentDate === "") {
                appointmentDateError.textContent = "Appointment date is required.";
                isValid = false;
            } else if (appointmentDate < today) {
                appointmentDateError.textContent = "Appointment date cannot be in the past.";
                isValid = false;
            } else {
                appointmentDateError.textContent = "";
            }

            // Validate Appointment Time
            const appointmentTime = document.querySelector('[name="appointment_time"]').value;
            const appointmentTimeError = document.getElementById('appointmentTimeError');
            if (appointmentTime === "") {
                appointmentTimeError.textContent = "Appointment time is required.";
                isValid = false;
            } else {
                appointmentTimeError.textContent = "";
            }

            return isValid;
        }
    </script>
</head>
<body>
    <table width="100%" bgcolor="#518d9c">
        <tr>
            <td>
                <h2>Edit Appointment</h2>
            </td>
            <td align="right">
                <a href="<?php echo ($role === 'Admin') ? '/Health%20Info/controller/admin-appointments.php' : '/Health%20Info/view/my-appointment.php'; ?>" style="color: white; text-decoration: none;">Back to Appointments</a>
            </td>
        </tr>
    </table>

    <h3 align="center">Edit Appointment</h3>
    <form method="post" action="" onsubmit="return validateForm();">
        <table border="1" cellpadding="10" width="50%" align="center">
            <tr>
                <td><b>Patient Name:</b></td>
                <td>
                    <input type="text" name="patient" value="<?php echo htmlspecialchars($appointment['patient_name']); ?>">
                    <p id="patientNameError" style="color: red;"></p>
                </td>
            </tr>
            <tr>
                <td><b>Doctor:</b></td>
                <td>
                    <select name="doctor">
                        <option value="" disabled>Select Doctor</option>
                        <option value="Dr. Ahsan Habib" <?php echo ($appointment['doctor'] == 'Dr. Ahsan Habib') ? 'selected' : ''; ?>>Dr. Ahsan Habib (Cardiologist)</option>
                        <option value="Dr. Nusrat Jahan" <?php echo ($appointment['doctor'] == 'Dr. Nusrat Jahan') ? 'selected' : ''; ?>>Dr. Nusrat Jahan (Dermatologist)</option>
                        <option value="Dr. Farhan Rahman" <?php echo ($appointment['doctor'] == 'Dr. Farhan Rahman') ? 'selected' : ''; ?>>Dr. Farhan Rahman (Orthopedic)</option>
                    </select>
                    <p id="doctorError" style="color: red;"></p>
                </td>
            </tr>
            <tr>
                <td><b>Date:</b></td>
                <td>
                    <input type="date" name="appointment_date" value="<?php echo htmlspecialchars($appointment['appointment_date']); ?>">
                    <p id="appointmentDateError" style="color: red;"></p>
                </td>
            </tr>
            <tr>
                <td><b>Time:</b></td>
                <td>
                    <input type="time" name="appointment_time" value="<?php echo htmlspecialchars($appointment['appointment_time']); ?>">
                    <p id="appointmentTimeError" style="color: red;"></p>
                </td>
            </tr>
        </table>
        <p align="center">
            <button type="submit" style="padding: 10px 20px; background-color: #518d9c; color: white; border: none;">Update Appointment</button>
        </p>
    </form>
</body>
</html>
