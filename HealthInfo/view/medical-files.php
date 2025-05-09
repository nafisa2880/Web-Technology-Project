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

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['user_id'];

$stmt->close();

$files = [];

if ($role === 'Patient') {
    $sql = "SELECT * FROM medical_files WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
} elseif ($role === 'Doctor') {
    $sql = "SELECT mf.* FROM medical_files mf
            JOIN users u ON mf.user_id = u.user_id
            WHERE u.role = 'Patient'";
    $stmt = $conn->prepare($sql);
} elseif ($role === 'Admin') {
    $sql = "SELECT * FROM medical_files";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $files[] = $row;
}

$stmt->close();
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
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f9; }
        .header { background-color: #518d9c; color: white; padding: 10px; text-align: center; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; background-color: #fff; }
        table th, table td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        table th { background-color: #518d9c; color: white; }
        table tr:nth-child(even) { background-color: #f9f9f9; }
        table tr:hover { background-color: #f1f1f1; }
        form { width: 50%; margin: 20px auto; padding: 20px; background: #fff; border: 1px solid #ccc; border-radius: 8px; }
        form label { font-weight: bold; display: block; margin-bottom: 8px; }
        form input { margin-bottom: 15px; width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        form button { background-color: #518d9c; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        form button:hover { background-color: #336b73; }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const fileInput = document.getElementById("file");
            const uploadForm = document.querySelector("form");
            const fileTable = document.querySelector("table");

            uploadForm.addEventListener("submit", function (event) {
                event.preventDefault();

                const file = fileInput.files[0];

                if (!file) {
                    alert("Please select a file.");
                    return;
                }

                const allowedTypes = ["jpeg", "jpg", "png", "pdf"];
                const fileType = file.name.split('.').pop().toLowerCase();

                if (!allowedTypes.includes(fileType)) {
                    alert("Invalid file type. Only JPEG, PNG, and PDF files are allowed.");
                    return;
                }

                const maxSize = 5 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert("File size exceeds 5MB limit.");
                    return;
                }

                const formData = new FormData();
                formData.append("file", file);

                const xhr = new XMLHttpRequest();
                xhr.open("POST", "../controller/upload-file.php", true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message);

                        if (response.success) {
                            const newFile = response.file;
                            const newRow = `
                                <tr>
                                    <td>${newFile.file_id}</td>
                                    <td>${newFile.file_name}</td>
                                    <td>${newFile.user_id}</td>
                                    <td>${newFile.uploader_role}</td>
                                    <td>${newFile.upload_date}</td>
                                    <td>
                                        <a href="${newFile.file_path}" download>Download</a>
                                        ${newFile.uploader_role === "Admin" ? ` | <a href="delete-file.php?id=${newFile.file_id}" onclick="return confirm('Are you sure you want to delete this file?')">Delete</a>` : ""}
                                    </td>
                                </tr>`;
                            fileTable.querySelector("tbody").insertAdjacentHTML("beforeend", newRow);
                        }
                    }
                };
                xhr.send(formData);
            });
        });
    </script>
</head>
<body>
    <div class="header">
        <h2>Medical Files</h2>
    </div>
    <h3 align="center">Uploaded Medical Files</h3>
    <table>
        <thead>
            <tr>
                <th>File ID</th>
                <th>File Name</th>
                <th>Uploaded By</th>
                <th>Role</th>
                <th>Upload Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($files)) : ?>
                <?php foreach ($files as $file) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($file['file_id']); ?></td>
                        <td><?php echo htmlspecialchars($file['file_name']); ?></td>
                        <td><?php echo htmlspecialchars($file['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($file['uploader_role']); ?></td>
                        <td><?php echo htmlspecialchars($file['upload_date']); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($file['file_path']); ?>" download>Download</a>
                            <?php if ($role === 'Admin') : ?>
                                | <a href="delete-file.php?id=<?php echo $file['file_id']; ?>" onclick="return confirm('Are you sure you want to delete this file?')">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" align="center">No files found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php if ($role === 'Patient') : ?>
        <h3 align="center">Upload New Medical File</h3>
        <form enctype="multipart/form-data">
            <label for="file">Select File:</label>
            <input type="file" id="file" name="file" required>
            <button type="submit">Upload</button>
        </form>
    <?php endif; ?>
    <!-- Bottom Navigation Bar -->

    <div class="footer">
        <a href="contact.html">Contact Us</a>
        <a href="privacypolicy.html">Privacy Policy</a>
        <a href="service.html">Terms of Service</a>
    </div>
</body>
</html>
