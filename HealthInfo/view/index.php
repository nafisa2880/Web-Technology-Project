<?php
include_once 'db.php';
session_start(); // Start session to manage login

// Check if the user is logged in, otherwise redirect to login
if (!isset($_SESSION['username'])) {
    header("Location: ../view/login.html");
    exit;
}

// Get the user role from the session
$userRole = $_SESSION['userRole'];
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
</head>
<body>

    <h1>Welcome to the Health Info Platform</h1>
    <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    
    <!-- Health Tips Section -->
    <h3>Health Tips</h3>
    <ul>
        <li><a href="../view/index.html">View Health Tips</a></li>
    </ul>

    <?php
    // Display specific content based on user role
    if ($userRole == 'doctor') {
        echo '<h4>Doctor View:</h4>';
        echo '<ul>
                <li><a href="javascript:void(0);" onclick="window.location.href=\'../view/healthtips.php\'">View and Manage Health Tips</a></li>
                <li><a href="../view/createtip.php">Create New Tip</a></li>
                <li><a href="../view/edittips.php">Edit Health Tips</a></li>
              </ul>';
    } elseif ($userRole == 'admin') {
        echo '<h4>Admin View:</h4>';
        echo '<ul>
                <li><a href="javascript:void(0);" onclick="window.location.href=\'../view/healthtips.php\'">View and Manage Health Tips</a></li>
              </ul>';
    } elseif ($userRole == 'patient') {
        echo '<h4>Patient View:</h4>';
        echo '<ul>
                <li><a href="javascript:void(0);" onclick="window.location.href=\'../view/healthtips.html\'">Search Health Tips</a></li>
              </ul>';
    }
    ?>
<!-- Bottom Navigation Bar -->

<div class="footer">
        <a href="contact.html">Contact Us</a>
        <a href="privacypolicy.html">Privacy Policy</a>
        <a href="service.html">Terms of Service</a>
    </div>
</body>
</html>
