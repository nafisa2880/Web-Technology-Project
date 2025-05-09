<?php
include_once 'db.php';
session_start();



if (!isset($_SESSION['role']) || $_SESSION['role'] != 'doctor') {
    echo "Unauthorized access.";
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $sql = "SELECT * FROM reminders WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $advice = $row['doctor_advice']; // existing advice
    } else {
        echo "Reminder not found.";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $advice = $_POST['advice'];

    $sql = "UPDATE reminders SET doctor_advice = '$advice' WHERE id = $id";

    if ($conn->query($sql)) {
        echo "<script>alert('Advice added successfully!'); window.location.href = '../controller/reminderTable.php';</script>";
        exit;
    } else {
        echo "Error adding advice.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Advice</title>
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
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Add Advice for Reminder</h2>
        <form method="POST" action="">
            <label for="advice">Advice:</label>
            <textarea name="advice" id="advice" rows="4" required><?php echo htmlspecialchars($advice); ?></textarea><br><br>

            <button type="submit">Save Advice</button>
        </form>
    </div>

    <br>
    <a href="../controller/reminderTable.php">Back to reminder table</a>

</body>
</html>
