<?php
include_once 'db.php';
session_start();

if (!isset($_SESSION['role'])) {
    echo "Please log in to access this page.";
    echo "<br><a href='../view/login.html'>Login Here</a>";
    exit;
}
//for patient
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] == 'patient') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $patient_name = $_SESSION['username'];  


    $sql = "INSERT INTO reminders (patient_name, title, description, date, time) 
            VALUES ('$patient_name', '$title', '$description', '$date', '$time')";


    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Reminder set successfully!'); window.location.href = '../controller/patientReminders.php';</script>";
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// for doc and admin
if ($_SESSION['role'] == 'doctor' || $_SESSION['role'] == 'admin') {
  
    $sql = "SELECT * FROM reminders";
    $result = mysqli_query($conn, $sql);
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
    <title>Set Medication Reminder</title>
    <style>
        .form-container {
            width: 50%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        form label {
            font-weight: bold;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 15px;
            background-color:rgb(125, 170, 232);
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color:rgb(135, 170, 219);
        }

        .alert {
            color: green;
            text-align: center;
        }
        
        .table-container {
            width: 80%;
            margin: auto;
        }

        table, th, td {
            border: 1px solid #ccc;
            border-collapse: collapse;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
        
        .advice {
            display: flex;
            justify-content: space-evenly;
        }
    </style>
</head>
<body>

    <!-- for Patient -->
    <?php if ($_SESSION['role'] == 'patient') { ?>
        <div class="form-container">
            <h2>Set Medication Reminder</h2>
            <form method="POST" action="medication.php">
            
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" required><br><br>

                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea><br><br>

                <label for="date">Date:</label>
                <input type="date" name="date" id="date" required><br><br>

                <label for="time">Time:</label>
                <input type="time" name="time" id="time" required><br><br>

                <button type="submit">Set Reminder</button>
            </form>
        </div>
    <?php } ?>

    <!-- //Doctor/Admin -->
    <?php if ($_SESSION['role'] == 'doctor' || $_SESSION['role'] == 'admin') { ?>
        <div class="table-container">
            <h2>Manage Medication Reminders</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Patient Name</th>
                        <th>Advice</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
                            echo "<td class='advice'>";
                            
                            // Doctor - Add Advice
                            if ($_SESSION['role'] == 'doctor') {
                                echo "<a href='../controller/addAdvice.php?id=" . $row['id'] . "'>Add Advice</a>";
                            }

                            // Admin - Delete Reminder
                            if ($_SESSION['role'] == 'admin') {
                                echo "<a href='../controller/deleteReminder.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this reminder?');\">Delete</a>";
                            }

                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No reminders found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
    <a href="../view/index.html">Back to Home</a><br>
    <a href="../controller/patientReminders.php">See your reminders</a>
    <!-- Bottom Navigation Bar -->

    <div class="footer">
        <a href="contact.html">Contact Us</a>
        <a href="privacypolicy.html">Privacy Policy</a>
        <a href="service.html">Terms of Service</a>
    </div>
</body>

</html>
