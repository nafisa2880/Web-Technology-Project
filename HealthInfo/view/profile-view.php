<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location:../view/login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "healthinfo");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "SELECT username, role, CONCAT(first_name, ' ', last_name) AS full_name, 
            contact_number, address, specialty, experience_years 
            FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(["error" => "User not found."]);
    }
    $stmt->close();
    $conn->close();
    exit();
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
    <title>User Profile</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
        }
        .header {
            background-color: #518d9c;
            color: white;
            padding: 15px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        .header h2 {
            margin: 0;
        }
        .header a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-size: 1rem;
        }
        .header a:hover {
            text-decoration: underline;
        }
        .container {
            width: 80%;
            max-width: 800px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .container h3 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
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
            background-color: #518d9c;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #406c78;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.querySelector(".container");

            function loadProfile() {
                let xhttp = new XMLHttpRequest();
                xhttp.open("POST", "", true);
                xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhttp.onreadystatechange = function() {
                    if (this.readyState === 4 && this.status === 200) {
                        let response = JSON.parse(this.responseText);
                        if (response.error) {
                            container.innerHTML = `<p style='color:red;'>${response.error}</p>`;
                        } else {
                            populateProfile(response);
                        }
                    }
                };
                xhttp.send();
            }

            function populateProfile(user) {
                let html = `
                    <h3>Profile Details</h3>
                    <table>
                        <tr><th>Username:</th><td>${user.username}</td></tr>
                        <tr><th>Role:</th><td>${user.role}</td></tr>
                        <tr><th>Full Name:</th><td>${user.full_name}</td></tr>
                        <tr><th>Contact Number:</th><td>${user.contact_number}</td></tr>
                        <tr><th>Address:</th><td>${user.address}</td></tr>
                `;

                if (user.role === "Doctor") {
                    html += `
                        <tr><th>Specialty:</th><td>${user.specialty}</td></tr>
                        <tr><th>Years of Experience:</th><td>${user.experience_years}</td></tr>
                    `;
                }

                html += `</table>`;
                html += `<p style="text-align: center;"><button class="button" id="editProfileButton">Edit Profile</button></p>`;

                container.innerHTML = html;

                document.getElementById("editProfileButton").addEventListener("click", function() {
                    location.href = "../controller/editprofile.php";
                });
            }

            loadProfile();
        });
    </script>
</head>
<body>
    <div class="header">
        <h2>User Profile</h2>
        <a href="../view/index.html">Back to Home</a> |
        <a href="/Health%20Info/controller/logout.php">Logout</a>
    </div>

    <div class="container">
        <p>Loading profile...</p>
    </div>
    <!-- Bottom Navigation Bar -->

    <div class="footer">
        <a href="contact.html">Contact Us</a>
        <a href="privacypolicy.html">Privacy Policy</a>
        <a href="service.html">Terms of Service</a>
    </div>
</body>
</html>
