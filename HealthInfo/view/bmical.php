<?php
session_start(); // Start session

// Check if user is logged in
if (!isset($_SESSION['role'])) {
    echo "Please log in to access this page.";
    echo "<br><a href='../view/login.html'>Login Here</a>";
    exit;
}

// Redirect doctor role as they have no access
if ($_SESSION['role'] === 'doctor') {
    echo "You do not have access to this page.";
    exit;
}

// Include database connection
include_once 'db.php';

// Initialize variables
$weight = $height = $bmi = $category = $advice = "";
$message = "";

// Handle BMI calculation for user
if ($_SESSION['role'] === 'patient' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calculate_bmi'])) {
    if (!empty($_POST['weight']) && !empty($_POST['height']) && $_POST['weight'] > 0 && $_POST['height'] > 0) {
        $weight = $_POST['weight'];
        $height = $_POST['height'];

        // Convert height to meters and calculate BMI
        $heightInMeters = $height / 100;
        $bmi = $weight / ($heightInMeters * $heightInMeters);

        // Determine BMI category
        if ($bmi < 18.5) {
            $category = "Underweight";
        } elseif ($bmi >= 18.5 && $bmi < 24.9) {
            $category = "Normal weight";
        } elseif ($bmi >= 25 && $bmi < 29.9) {
            $category = "Overweight";
        } else {
            $category = "Obese";
        }

        // Fetch advice for category
        $adviceQuery = "SELECT advice FROM bmiAdvice WHERE category = '$category'";
        $adviceResult = mysqli_query($conn, $adviceQuery);
        if ($adviceResult && mysqli_num_rows($adviceResult) > 0) {
            $adviceRow = mysqli_fetch_assoc($adviceResult);
            $advice = $adviceRow['advice'];
        } else {
            $advice = "No advice available for this BMI category.";
        }

        // Insert BMI record into database
        $insertQuery = "INSERT INTO bmiEntries (weight, height, bmi, category) VALUES ('$weight', '$height', '$bmi', '$category')";
        if (mysqli_query($conn, $insertQuery)) {
            $message = "BMI calculated and saved successfully!";
        } else {
            $message = "Error saving BMI data: " . mysqli_error($conn);
        }
    } else {
        $message = "Please provide valid weight and height values.";
    }
}

// Handle deletion of BMI records for admin
if ($_SESSION['role'] === 'admin' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_bmi'])) {
    $bmiId = $_POST['bmi_id'];
    $deleteQuery = "DELETE FROM bmiEntries WHERE id = $bmiId";
    if (mysqli_query($conn, $deleteQuery)) {
        $message = "BMI record deleted successfully.";
    } else {
        $message = "Error deleting BMI record: " . mysqli_error($conn);
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
    <title>BMI Calculator</title>
    <style>
    body { 
        font-family: Arial, sans-serif; 
        margin: 0; 
        padding: 20px; 
        background-color: #f4f8fc; 
        color: #333; 
    }
    h2 { 
        color: #007BFF; 
        text-align: center; 
        margin-bottom: 20px; 
    }
    form { 
        background: #fff; 
        padding: 20px; 
        border-radius: 8px; 
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
        margin-bottom: 20px; 
        max-width: 500px; 
        margin: auto;
    }
    label { 
        font-weight: bold; 
        margin-bottom: 8px; 
        display: block; 
    }
    input[type="number"], input[type="submit"], button { 
        padding: 10px; 
        margin: 10px 0; 
        width: 100%; 
        font-size: 16px; 
        border: 1px solid #ccc; 
        border-radius: 4px; 
    }
    input[type="submit"], button { 
        background-color: #007BFF; 
        color: #fff; 
        cursor: pointer; 
    }
    input[type="submit"]:hover, button:hover { 
        background-color: #0056b3; 
    }
    .message { 
        color: #0056b3; 
        font-weight: bold; 
        text-align: center; 
        margin-top: 20px; 
    }
    table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 20px; 
        background: #fff; 
        border-radius: 8px; 
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
        overflow: hidden; 
        max-width: 800px; 
        margin: 20px auto; 
    }
    table, th, td { 
        border: 1px solid #ddd; 
    }
    th { 
        background-color: #007BFF; 
        color: #fff; 
        padding: 8px; 
        font-size: 14px; 
    }
    td { 
        padding: 8px; 
        text-align: center; 
        font-size: 14px; 
    }
    tr:nth-child(even) { 
        background-color: #f2f9ff; 
    }
    tr:hover { 
        background-color: #e6f2ff; 
    }
    .delete-button { 
        background-color: #FF4D4D; 
        color: white; 
        border: none; 
        padding: 6px 10px; 
        border-radius: 4px; 
        font-size: 12px; 
        cursor: pointer; 
    }
    .delete-button:hover { 
        background-color: #e60000; 
    }
</style>

</head>
<body>
    <h2>BMI Calculator</h2>
    <?php if ($_SESSION['role'] === 'patient') : ?>
        <form method="POST" action="">
            <label for="weight">Weight (kg):</label>
            <input type="number" name="weight" id="weight" step="0.1" required>
            <label for="height">Height (cm):</label>
            <input type="number" name="height" id="height" step="0.1" required>
            <input type="submit" name="calculate_bmi" value="Calculate BMI">
        </form>
        <?php if ($message) : ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>
        <?php if ($bmi && $category && $advice) : ?>
            <div>
                <h3>Your BMI: <?= number_format($bmi, 2) ?></h3>
                <p>Category: <?= $category ?></p>
                <p><strong>Advice:</strong> <?= $advice ?></p>
            </div>
        <?php endif; ?>
    <?php elseif ($_SESSION['role'] === 'admin') : ?>
        <h3>All BMI Records</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Weight</th>
                    <th>Height</th>
                    <th>BMI</th>
                    <th>Category</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $bmiQuery = "SELECT * FROM bmiEntries";
                $bmiResult = mysqli_query($conn, $bmiQuery);
                if ($bmiResult && mysqli_num_rows($bmiResult) > 0) :
                    while ($row = mysqli_fetch_assoc($bmiResult)) :
                ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['weight'] ?></td>
                            <td><?= $row['height'] ?></td>
                            <td><?= number_format($row['bmi'], 2) ?></td>
                            <td><?= $row['category'] ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="bmi_id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="delete_bmi">Delete</button>
                                </form>
                            </td>
                        </tr>
                <?php
                    endwhile;
                else :
                ?>
                    <tr>
                        <td colspan="7">No records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <!-- Bottom Navigation Bar -->

    <div class="footer">
        <a href="contact.html">Contact Us</a>
        <a href="privacypolicy.html">Privacy Policy</a>
        <a href="service.html">Terms of Service</a>
    </div>
</body>
</html>
