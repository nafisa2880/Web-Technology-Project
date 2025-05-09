<?php
include_once 'db.php';

session_start();



if (!isset($_SESSION['role'])) {
    echo "Please log in to access this page";
    echo "<br><a href='../view/login.html'>Login Here</a>";
    exit;
}


$sql = "SELECT * FROM HealthTips"; // Fetch all tips
$result = $conn->query($sql);

$role = $_SESSION['role'];
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
    <title>Health Tips</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; /* Light blue */
            margin: 0;
            padding: 0;
            text-align: center;
        }
        h1 {
            color: #004080; /* Dark blue */
        }
      
        form {
            margin-bottom: 20px;
        }
        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 50%; /* Smaller table width */
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        th, td {
            border: 1px solid #004080;
            text-align: left;
            padding: 5px; /* Smaller padding for compact size */
        }
        th {
            background-color: #004080;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #e6f2ff; /* Light blue row */
        }
        tr:hover {
            background-color: #cce0ff; /* Hover effect */
        }
        a {
            color: #004080;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            color: #002060;
        }
        .btn {
            padding: 6px 12px;
            background-color: #004080;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 0.9em;
            border-radius: 4px;
        }
        .btn:hover {
            background-color: #002060;
        }
    </style>
</head>
<body>


<h1>Health Tips</h1>

<?php if ($role == 'patient'): ?>

    <form method="POST" action="../view/healthtips.php">
        <label for="category">Search by Category:</label>
        <input type="text" name="category" required>
        <button type="submit">Search</button>
    </form>
<?php endif; ?>


<table border="1">
    <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Category</th>
        <th>Actions</th>
    </tr>

    <?php
 
    if ($role == 'patient' && isset($_POST['category'])) {
        $category = $_POST['category'];
        $sql .= " WHERE category LIKE '%$category%'";
        $result = $conn->query($sql);
    }

 
    if ($result->num_rows > 0) {
       
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
            echo "<td>";
            
            
           
            if ($role == 'doctor') {
                
                echo "<a href='../view/edittips.php?id=" . $row['id'] . "'>Edit</a> ";
            }
            if ($role == 'admin') {
                echo "<a href='../controller/deletetip.php?id=" . $row['id'] . "'>Delete</a> ";
                
            }
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No tips available.</td></tr>";
    }
    ?>
</table>


<?php if ($role == 'doctor'): ?>
    <br>
    <a href="../view/create.php">Create New Tip</a>
    
<?php endif; ?>
<?php if ($role == 'doctor'): ?>
    <br>
    <a href="../view/index1.html">Back to Home</a>
    
<?php endif; ?>

<?php if ($role == 'admin'): ?>
    <br>
    <a href="../view/index1.html">Back to Home</a>
    
<?php endif; ?>
<a href="index1.html">Back to Home</a>

<!-- Bottom Navigation Bar -->

<div class="footer">
        <a href="contact.html">Contact Us</a>
        <a href="privacypolicy.html">Privacy Policy</a>
        <a href="service.html">Terms of Service</a>
    </div>

</body>
</html>

<?php


$conn->close();
?>
