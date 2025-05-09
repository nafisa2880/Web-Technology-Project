<?php
include_once 'db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'doctor') {
    die("Unauthorized access.");
}

$successMessage = ""; // Variable to store the success message

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM HealthTips WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $tip = $result->fetch_assoc();
    } else {
        echo "Tip not found.";
        exit;
    }
} else {
    echo "No tip ID provided.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    $sql = "UPDATE HealthTips 
            SET title = '$title', description = '$description', category = '$category'
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        $successMessage = "Health tip updated successfully. <a href='../view/healthtips.php'>Go back to health tips</a>";
    } else {
        $successMessage = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
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
    <title>Edit Health Tip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            width: 400px;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #004080;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }
        input[type="text"],
        textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            resize: vertical;
        }
        textarea {
            height: 80px;
        }
        input[type="submit"] {
            background-color: #004080;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
        }
        input[type="submit"]:hover {
            background-color: #002060;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #004080;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            color: #002060;
        }
        .success-message {
            margin-top: 20px;
            padding: 10px;
            background-color: #e0f7e9;
            border: 1px solid #4caf50;
            color: #2e7d32;
            text-align: center;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Health Tip</h1>
    <form method="POST" action="../view/edittips.php?id=<?php echo $id; ?>">
        <label for="title">Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($tip['title']); ?>" required>

        <label for="description">Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($tip['description']); ?></textarea>

        <label for="category">Category:</label>
        <input type="text" name="category" value="<?php echo htmlspecialchars($tip['category']); ?>" required>

        <input type="submit" value="Update Tip">
    </form>
</div>

<?php if ($successMessage): ?>
    <div class="success-message">
        <?php echo $successMessage; ?>
    </div>
<?php endif; ?>
<!-- Bottom Navigation Bar -->

<div class="footer">
        <a href="contact.html">Contact Us</a>
        <a href="privacypolicy.html">Privacy Policy</a>
        <a href="service.html">Terms of Service</a>
    </div>
</body>
</html>
