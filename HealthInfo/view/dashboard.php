<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
$user = $_SESSION['user'];
$role = strtolower($user['role']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
<meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Dashboard</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .header, .navbar, .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
        .navbar a, .footer a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
        }
        .navbar a:hover, .footer a:hover {
            text-decoration: underline;
        }
        .main-content {
            padding: 20px;
            text-align: center;
        }
        .main-content h3 {
            margin-bottom: 10px;
        }
        .main-content ul {
            list-style-type: none;
            padding: 0;
        }
        .main-content ul li {
            margin: 5px 0;
        }

    </style>
</head>
<body>

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

    <!-- Main Content -->

    <div class="main-content">
        <h3>Welcome, <?php echo $user['username']; ?>!</h3>
        <p>Role: <?php echo ucfirst($role); ?></p>
        <hr>

        <?php if ($role === 'patient'): ?>
            <ul>
                <li><a href="book-appointment.html">Book Appointment</a></li>
                <li><a href="medical-history.php">View Medical History</a></li>
                <li><a href="payment.php">Payment Integration</a></li>
                <li><a href="docAvailability.html">Doctor Available</a></li>
                <li><a href="appointment_history.php">Appointment History</a><li>
                <li><a href="my-appointment.php">My Doctor</a></li>
                <li><a href="prescription.html">My Prescriptions</a></li>
                <li><a href="medical-files.php">Medical Files</a></li>
                <li><a href="medication.html">Medication</a></li>
                <li><a href="bmical.html">BMI Calculator</a></li>
                <li><a href="dietplat.html">Medical Files</a></li>
                <li><a href="healthtips.html">Health Tips</a></li>
                <li><a href="symptom-checker.html">Symptom Checker</a></li>
                <li><a href="emgCont.php">Emergency Contact</a></li>
                <li><a href="medical_history.php">View Medical History</a></li>
                <li><a href="add_medical_history.php">Add Medical History</a></li>
                

            </ul>

        <?php elseif ($role === 'doctor'): ?>

            <ul>
                <li><a href="doctor-appointments.php">View Appointments</a></li>
                <li><a href="patient_prescription.html">Patient Prescriptions</a></li>
                <li><a href="adviceplan.php">Advice</a></li>
                <li><a href="symptom-checker.html">Symptom Checker</a></li>
                <li><a href="view-medicalfiles.php">View Medical Files</a></li>
                <li><a href="bmical.html">BMI Calculator</a></li>
                <li><a href="dietplat.html">Medical Files</a></li>
                <li><a href="healthtips.html">Health Tips</a></li>
                <li><a href="adviceplan.php">Advice</a></li>
                <li><a href="medical_history.php">View Medical History</a></li>
                <li><a href="add_medical_history.php">Add Medical History</a></li>

            </ul>

        <?php elseif ($role === 'admin'): ?>
            
            <ul>
                <li><a href="admin-userprofile.php">Admin Profile</a></li>
                <li><a href="docAvailability.html">Doctor Available</a></li>
                <li><a href="patient_prescription.html">Patient Prescriptions</a></li>
                <li><a href="doctor-appointments.php">View Appointments</a></li>
                <li><a href="view-medicalfiles.php">View Medical Files</a></li>
                <li><a href="edittips.php">Edit Tips</a></li>
                <li><a href="appointment_history.php">Appointment History</a><li>
                <li><a href="medical_history.php">View Medical History</a></li>
                <li><a href="add_medical_history.php">Add Medical History</a></li>

            </ul>
        
            <?php endif; ?>

        <hr>
        <p><a href="logout.php">Logout</a></p>
    </div>

    <!-- Bottom Navigation Bar -->

    <div class="footer">
        <a href="contact.html">Contact Us</a>
        <a href="privacypolicy.html">Privacy Policy</a>
        <a href="service.html">Terms of Service</a>
    </div>

</body>
</html>
