<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Please log in to access this page";
    echo "<br><a href='../view/login.html'>Login Here</a>";
    exit;
}


$conn = new mysqli("localhost", "root", "", "healthinfo");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users
$sql = "SELECT user_id, username, role, CONCAT(first_name, ' ', last_name) AS full_name, 
        contact_number, address, specialty, experience_years FROM users";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
        .navbar {
            display: flex;
            justify-content: flex-end;
            background-color: #518d9c;
            padding: 10px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .container {
            width: 90%;
            max-width: 1200px;
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
        .delete-button {
            background-color: #d9534f;
        }
        .delete-button:hover {
            background-color: #c12e2a;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const deleteUser = (userId) => {
                if (confirm("Are you sure you want to delete this user?")) {
                    fetch('/Health%20Info/controller/delete-user.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ user_id: userId }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) {
                            document.getElementById(`user-row-${userId}`).remove();
                        }
                    })
                    .catch(error => alert('Error deleting user: ' + error));
                }
            };

            document.querySelectorAll(".delete-button").forEach(button => {
                button.addEventListener("click", function () {
                    const userId = this.dataset.userId;
                    deleteUser(userId);
                });
            });
        });
    </script>
</head>
<body>
    <div class="header">
        <h2>Manage Users</h2>
    </div>
    <div class="navbar">
        <a href="/Health%20Info/view/home.php">Home</a>
        <a href="/Health%20Info/controller/logout.php">Logout</a>
    </div>

    <div class="container">
        <h3>All Users</h3>
        <table>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Full Name</th>
                <th>Contact Number</th>
                <th>Address</th>
                <th>Specialty</th>
                <th>Experience (Years)</th>
                <th>Actions</th>
            </tr>
            <?php if (!empty($users)) : ?>
                <?php foreach ($users as $user) : ?>
                    <tr id="user-row-<?php echo $user['user_id']; ?>">
                        <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['contact_number']); ?></td>
                        <td><?php echo htmlspecialchars($user['address']); ?></td>
                        <td><?php echo htmlspecialchars($user['specialty']); ?></td>
                        <td><?php echo htmlspecialchars($user['experience_years']); ?></td>
                        <td>
                            <a href="/Health%20Info/controller/edit-user.php?id=<?php echo $user['user_id']; ?>" class="button">Edit</a>
                            <button class="button delete-button" data-user-id="<?php echo $user['user_id']; ?>">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="9" align="center">No users found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
