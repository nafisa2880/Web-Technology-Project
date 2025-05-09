<?php
session_start(); // Start the session

// Include database connection
include_once 'db.php';

// Check if the user is logged in and is a doctor
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    echo "Access denied. Only doctors can access this page.";
    echo "<br><a href='../view/login.html'>Login Here</a>";
    exit;
}

// Initialize variables
$dietPlan = null;
$message = "";

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure ID is an integer

    // Fetch the current diet plan from the database
    $sql = "SELECT * FROM dietPlans WHERE id = $id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $dietPlan = $result->fetch_assoc();
    } else {
        $message = "No diet plan found for the given ID.";
    }
} else {
    $message = "Invalid diet plan ID.";
}

// Handle form submission for giving advice
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $advice = isset($_POST['advice']) ? $conn->real_escape_string($_POST['advice']) : '';

    if ($dietPlan && !empty($advice)) {
        // Update the diet plan with the doctor's advice
        $updateSql = "UPDATE dietPlans 
                      SET diet_plan = CONCAT(diet_plan, '\n\nDoctor\'s Advice: ', '$advice') 
                      WHERE id = $id";
        
        if ($conn->query($updateSql)) {
            $message = "Advice added successfully!";
            $dietPlan['diet_plan'] .= "\n\nDoctor's Advice: " . $advice; // Update the local copy
        } else {
            $message = "Failed to update the diet plan: " . $conn->error;
        }
    } else {
        $message = "Please provide valid advice.";
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
    <title>Give Advice</title>
</head>
<body>
    <h2>Give Advice on Diet Plan</h2>

    <?php if ($dietPlan): ?>
        <p><strong>Current Diet Plan:</strong></p>
        <pre><?= htmlspecialchars($dietPlan['diet_plan']); ?></pre>

        <form action="advicedplan.php?id=<?= $dietPlan['id'] ?>" method="POST">
            <label for="advice">Your Advice:</label>
            <textarea name="advice" id="advice" rows="5" required></textarea>
            <button type="submit">Submit Advice</button>
        </form>
    <?php endif; ?>

    <?php if ($message): ?>
        <p><?= htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <a href="dietplan.php">Back to Diet Plans</a>
    <!-- Bottom Navigation Bar -->

    <div class="footer">
        <a href="contact.html">Contact Us</a>
        <a href="privacypolicy.html">Privacy Policy</a>
        <a href="service.html">Terms of Service</a>
    </div>
</body>
</html>
