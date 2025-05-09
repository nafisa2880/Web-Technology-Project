<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['role'])) {
    echo "Please log in to access this page.";
    echo "<br><a href='../view/login.html'>Login Here</a>";
    exit;
}

$role = $_SESSION['role']; // Get the user's role

// Include database connection
include_once 'db.php';

// Initialize variables
$diet_plan = "";
$message = "";

// Handle Patient Role
if ($role === 'patient') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $age_group = $_POST['age_group'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $activity_level = $_POST['activity_level'] ?? '';
        $goal = $_POST['goal'] ?? '';

        // Query to fetch diet plan
        $sql = "SELECT diet_plan FROM dietPlans WHERE age_group = '$age_group' AND gender = '$gender' AND activity_level = '$activity_level'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $diet_plan = $row['diet_plan'];
        } else {
            $message = "No diet plan found for the given input.";
        }
    }
}

// Handle Doctor and Admin Roles
if ($role === 'doctor' || $role === 'admin') {
    $sql = "SELECT * FROM dietPlans";
    $result = $conn->query($sql);
    if (!$result) {
        $message = "Error fetching diet plans: " . $conn->error;
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
    <title>Diet Plan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #003366;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        table th {
            background-color: #003366;
            color: #fff;
        }
        button, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            background-color: #003366;
            color: #fff;
            cursor: pointer;
        }
        button:hover {
            background-color: #00509e;
        }
        .back-link {
            display: block;
            margin: 20px 0;
            text-align: center;
            color: #003366;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($role === 'patient') : ?>
            <h2>Diet Plan Generator</h2>
            <form action="dietplan.php" method="POST">
                <label>Age Group:</label>
                <select name="age_group" required>
                    <option value="" disabled selected>Select Age Group</option>
                    <option value="18-25">18-25</option>
                    <option value="26-30">26-30</option>
                    <option value="31-40">31-40</option>
                    <option value="41-50">41-50</option>
                    <option value="51-60">51-60</option>
                    <option value="60+">60+</option>
                </select>

                <label>Gender:</label>
                <select name="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>

                <label>Activity Level:</label>
                <select name="activity_level" required>
                    <option value="" disabled selected>Select Activity Level</option>
                    <option value="Low">Low</option>
                    <option value="Moderate">Moderate</option>
                    <option value="High">High</option>
                </select>

                <label>Goal:</label>
                <textarea name="goal" rows="4" required></textarea>
                <button type="submit">Generate Diet Plan</button>
            </form>

            <?php if ($diet_plan) : ?>
                <h3>Your Diet Plan</h3>
                <p><?= htmlspecialchars($diet_plan) ?></p>
            <?php elseif ($message) : ?>
                <p><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($role === 'doctor' || $role === 'admin') : ?>
            <h2>Diet Plans</h2>
            <table>
                <thead>
                    <tr>
                        <th>Age Group</th>
                        <th>Gender</th>
                        <th>Activity Level</th>
                        <th>Goal</th>
                        <th>Diet Plan</th>
                        <?php if ($role === 'doctor') : ?>
                            <th>Advice</th>
                        <?php elseif ($role === 'admin') : ?>
                            <th>Delete</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($result) && $result->num_rows > 0) : ?>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['age_group']) ?></td>
                                <td><?= htmlspecialchars($row['gender']) ?></td>
                                <td><?= htmlspecialchars($row['activity_level']) ?></td>
                                <td><?= htmlspecialchars($row['goal']) ?></td>
                                <td><?= htmlspecialchars($row['diet_plan']) ?></td>
                                <?php if ($role === 'doctor') : ?>
                                    <td><a href="advicedplan.php?id=<?= $row['id'] ?>">Give Advice</a></td>
                                <?php elseif ($role === 'admin') : ?>
                                    <td><a href="deletedplan.php?id=<?= $row['id'] ?>">Delete</a></td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr><td colspan="6">No diet plans available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a class="back-link" href="index1.html">Back to Home</a>
    </div>
    <!-- Bottom Navigation Bar -->

    <div class="footer">
        <a href="contact.html">Contact Us</a>
        <a href="privacypolicy.html">Privacy Policy</a>
        <a href="service.html">Terms of Service</a>
    </div>
</body>
</html>
