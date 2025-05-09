<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    // Redirect to login page if not logged in
    header("Location: Health%20Info/view/login.html");
    exit();
}

// Get user's role
$role = $_SESSION['role'];
$username = $_SESSION['username'];
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
    <title>Home Page</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
            display: flex;
        }
        .header {
            background-color: #518d9c;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            width: 100%;
        }
        .header h1 {
            margin: 0;
            font-size: 2rem;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 1.2rem;
        }
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            height: 100vh;
            position: fixed;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        }
        .sidebar .button {
            display: block;
            padding: 15px 20px;
            margin: 10px;
            color: white;
            background-color: #34495e;
            border: none;
            border-radius: 5px;
            text-align: left;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.2s;
        }
        .sidebar .button:hover {
            background-color: #1abc9c;
        }
        .sidebar .button.logout {
            background-color: #d9534f;
        }
        .sidebar .button.logout:hover {
            background-color: #c12e2a;
        }
        .content {
            margin-left: 270px;
            padding: 20px;
            flex: 1;
        }
        .content h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #444;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p>Your Role: <?php echo htmlspecialchars($role); ?></p>
    </div>

    <div class="sidebar">
        <h3 style="text-align: center;">Options</h3>
        <a href="/Health%20Info/view/profile-view.php" class="button">My Profile</a>

        <?php if ($role === 'Admin'): ?>
            <a href="/Health%20Info/controller/admin-appointments.php" class="button">Manage Appointments</a>
            <a href="/Health%20Info/controller/manage-users.php" class="button">Manage Users</a>
            <a href="/Health%20Info/view/admin-userprofile.php" class="button"> View All Users</a>
            <a href="/Health%20Info/view/view-medicalfiles.php" class="button">VIew Medical Files</a>
            <a href="/Health%20Info/view/symptom-checker.html" class="button">Symptom Checker</a>
            
            

        <?php elseif ($role === 'Doctor'): ?>
            <a href="/Health%20Info/view/view-medicalfiles.php" class="button">See All Medical Files</a>
            <a href="/Health%20Info/view/doctor-appointment.php"class="button">View My Appoitnments</a>
            <a href="/Health%20Info/view/symptom-checker.html" class="button">Symptom Checker</a>

        <?php else: ?>
            <a href="/Health%20Info/controller/favourite-doctors.php" class="button">Favourite Doctors</a>
            <a href="/Health%20Info/view/medical-files.php" class="button">My Medical Files</a>
            <a href="/Health%20Info/view/book-appointment.html" class="button">Book My Appointment</a>
            <a href="/Health%20Info/view/symptom-checker.html" class="button">Symptom Checker</a>
            <a href="/Health%20Info/view/my-appointment.php" class="button">View My Appointments</a>
        <?php endif; ?>

        <a href="/Health%20Info/controller/logout.php" class="button logout">Logout</a>
    </div>
<!-- Bottom Navigation Bar -->

<div class="footer">
        <a href="contact.html">Contact Us</a>
        <a href="privacypolicy.html">Privacy Policy</a>
        <a href="service.html">Terms of Service</a>
    </div>
    
</body>
</html>
