<?php
include 'db.php';
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'doctor') {
  
    header("Location: ../view/login.html");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];


    $sql = "INSERT INTO HealthTips (title, description, category) VALUES ('$title', '$description', '$category')";

    if ($conn->query($sql) === TRUE) {
      
        header("Location: ../view/healthtips.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
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
    <title>Create New Health Tip</title>
</head>
<body>
    <h1>Create New Health Tip</h1>
    <form method="POST" action="../view/create.php">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="Hydration">Hydration</option>
            <option value="Fitness">Fitness</option>
            <option value="Nutrition">Nutrition</option>
            <option value="Mental Health">Mental Health</option>
            <option value="Sleep">Sleep</option>
        </select><br><br>

        <input type="submit" value="Create Tip">
    </form>
    <!-- Bottom Navigation Bar -->

    <div class="footer">
        <a href="contact.html">Contact Us</a>
        <a href="privacypolicy.html">Privacy Policy</a>
        <a href="service.html">Terms of Service</a>
    </div>
</body>
</html>
