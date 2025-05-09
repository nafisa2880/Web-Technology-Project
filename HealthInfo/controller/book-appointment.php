<?php
session_start();
 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: /view/login.html");
    exit();
}
 
$conn = new mysqli("localhost", "root", "", "Healthinfo");
 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_SESSION['user_id'];
    $doctor_name = isset($_POST['doctor']) ? trim($_POST['doctor']) : '';
    $appointment_date = isset($_POST['appointment_date']) ? trim($_POST['appointment_date']) : '';
    $appointment_time = isset($_POST['appointment_time']) ? trim($_POST['appointment_time']) : '';
 
    $errors = [];
 
    // Validate doctor
    if (empty($doctor_name)) {
        $errors[] = "Doctor is required.";
    }
 
    // Validate appointment date
    if (empty($appointment_date)) {
        $errors[] = "Appointment date is required.";
    } elseif (strtotime($appointment_date) < strtotime(date("Y-m-d"))) {
        $errors[] = "Appointment date cannot be in the past.";
    }
 
    // Validate appointment time
    if (empty($appointment_time)) {
        $errors[] = "Appointment time is required.";
    }
 
    // Handle validation errors
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<script>alert('$error');</script>";
        }
        echo "<script>window.history.back();</script>";
        exit();
    }
 
    // Check patient details
    $patient_query = "SELECT CONCAT(first_name, ' ', last_name) AS patient_name FROM users WHERE user_id = ?";
    $patient_stmt = $conn->prepare($patient_query);
    $patient_stmt->bind_param("i", $patient_id);
    $patient_stmt->execute();
    $patient_result = $patient_stmt->get_result();
 
    if ($patient_result->num_rows === 1) {
        $patient = $patient_result->fetch_assoc();
        $patient_name = $patient['patient_name'];
 
        // Insert appointment into the database
        $insert_query = "INSERT INTO appointments (user_id, patient_name, doctor, appointment_date, appointment_time)
                         VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("issss", $patient_id, $patient_name, $doctor_name, $appointment_date, $appointment_time);
 
        if ($insert_stmt->execute()) {
            echo "<script>alert('Appointment booked successfully!'); window.location.href = '/Health Info/view/my-appointment.php';</script>";
        } else {
            echo "<script>alert('Error booking appointment. Please try again.'); window.history.back();</script>";
        }
        $insert_stmt->close();
    } else {
        echo "<script>alert('Patient details not found.'); window.history.back();</script>";
    }
 
    $patient_stmt->close();
}
 
$conn->close();
?>