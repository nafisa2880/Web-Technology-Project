<?php
include_once 'db.php';
session_start();

// Ensure only doctors can access
if ($_SESSION['role'] !== 'doctor') {
    die("Unauthorized access.");
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    $stmt = $conn->prepare("INSERT INTO HealthTips (title, description, category) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $description, $category);

    if ($stmt->execute()) {
        echo "Health tip created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit;
}
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
    <title>Create Health Tip</title>
</head>
<body>
    <h1>Create a New Health Tip</h1>
    <form method="POST" action="">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required><br><br>

        <label for="description">Description:</label><br>
        <textarea name="description" id="description" rows="4" required></textarea><br><br>

        <label for="category">Category:</label>
        <input type="text" name="category" id="category" required><br><br>

        <button type="submit">Create Tip</button>
    </form>
    <!-- Bottom Navigation Bar -->

    <div class="footer">
        <a href="contact.html">Contact Us</a>
        <a href="privacypolicy.html">Privacy Policy</a>
        <a href="service.html">Terms of Service</a>
    </div>
</body>
</html>
