<?php

session_start();


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "healthinfo");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Fetch all uploaded files
$sql = "SELECT mf.file_id, mf.file_name, mf.file_path, mf.file_type, 
        CONCAT(u.first_name, ' ', u.last_name) AS uploaded_by, u.role AS uploader_role
        FROM medical_files mf
        JOIN users u ON mf.user_id = u.user_id
        ORDER BY mf.file_id DESC";
$result = $conn->query($sql);

$files = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $files[] = $row;
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
    <title>Medical Files</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
        }
        .header {
            background-color: #518d9c;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .header a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }
        .header a:hover {
            text-decoration: underline;
        }
        .container {
            margin: 30px auto;
            width: 90%;
            max-width: 1200px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #518d9c;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .button {
            display: inline-block;
            padding: 8px 16px;
            color: white;
            background-color: #518d9c;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #406c78;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Medical Files</h1>
        <a href="/Health%20Info/view/home.php">Dashboard</a> |
        <a href="/Health%20Info/controller/logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Uploaded Medical Files</h2>
        <table>
            <tr>
                <th>File ID</th>
                <th>File Name</th>
                <th>Uploaded By</th>
                <th>Uploader Role</th>
                <th>File Type</th>
                <th>Actions</th>
            </tr>
            <?php if (!empty($files)) : ?>
                <?php foreach ($files as $file) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($file['file_id']); ?></td>
                        <td><?php echo htmlspecialchars($file['file_name']); ?></td>
                        <td><?php echo htmlspecialchars($file['uploaded_by']); ?></td>
                        <td><?php echo htmlspecialchars($file['uploader_role']); ?></td>
                        <td><?php echo htmlspecialchars($file['file_type']); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($file['file_path']); ?>" class="button" download>Download</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" align="center">No files found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
    <!-- Bottom Navigation Bar -->

    <div class="footer">
        <a href="contact.html">Contact Us</a>
        <a href="privacypolicy.html">Privacy Policy</a>
        <a href="service.html">Terms of Service</a>
    </div>
</body>
</html>
