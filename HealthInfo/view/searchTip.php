<?php
include_once 'db.php';
session_start();

// Check if user is logged in and is a patient
if (!isset($_SESSION['username']) || $_SESSION['userRole'] != 'patient') {
    header("Location: login.html");
    exit;
}


// Search functionality if the form is submitted
$searchQuery = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $searchQuery = $_POST['searchQuery'];
    $stmt = $conn->prepare("SELECT * FROM HealthTips WHERE title LIKE :searchQuery OR description LIKE :searchQuery");
    $stmt->bindValue(':searchQuery', "%" . $searchQuery . "%");
    $stmt->execute();
    $healthTips = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Default query to show all tips
    $stmt = $conn->prepare("SELECT * FROM HealthTips");
    $stmt->execute();
    $healthTips = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Search Health Tips</title>
</head>
<body>

    <h1>Search Health Tips</h1>

    <!-- Search Form -->
    <form method="POST">
        <input type="text" name="searchQuery" placeholder="Search health tips..." value="<?php echo htmlspecialchars($searchQuery); ?>" required>
        <button type="submit">Search</button>
    </form>

    <?php
    // Display health tips based on search results
    if (!empty($healthTips)) {
        foreach ($healthTips as $tip) {
            echo "<div>
                    <h3>" . htmlspecialchars($tip['title']) . "</h3>
                    <p>" . htmlspecialchars($tip['description']) . "</p>
                    <p>Category: " . htmlspecialchars($tip['category']) . "</p>
                  </div><hr>";
        }
    } else {
        echo "<p>No health tips found.</p>";
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
