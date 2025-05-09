<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "Healthinfo");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $first_name = $data['first_name'] ?? null;
    $last_name = $data['last_name'] ?? null;
    $contact_number = $data['contact_number'] ?? null;
    $address = $data['address'] ?? null;
    $specialty = $data['specialty'] ?? null;
    $experience_years = $data['experience_years'] ?? null;

    if ($first_name == null || $last_name == null || $contact_number == null || $address == null) {
        echo json_encode(["error" => "Fields cannot be null."]);
    } elseif (strlen($contact_number) < 10 || strlen($contact_number) > 15) {
        echo json_encode(["error" => "Contact number must be between 10 to 15 characters."]);
    } elseif ($specialty !== null && strlen($specialty) > 50) {
        echo json_encode(["error" => "Specialty must not exceed 50 characters."]);
    } elseif ($experience_years !== null && ($experience_years < 0 || $experience_years > 50)) {
        echo json_encode(["error" => "Experience years must be between 0 and 50."]);
    } else {
        $sql = "UPDATE users SET first_name = ?, last_name = ?, contact_number = ?, 
                address = ?, specialty = ?, experience_years = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssis",
            $first_name,
            $last_name,
            $contact_number,
            $address,
            $specialty,
            $experience_years,
            $username
        );

        if ($stmt->execute()) {
            echo json_encode(["success" => "Profile updated successfully!"]);
        } else {
            echo json_encode(["error" => "Error updating profile."]);
        }

        $stmt->close();
    }
    $conn->close();
    exit();
}

// Fetch user details for the initial load
$sql = "SELECT username, role, first_name, last_name, 
        contact_number, address, specialty, experience_years 
        FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    die("User not found.");
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
</head>
<body>
    <!-- Navigation Bar -->
    <table width="100%" bgcolor="#518d9c">
        <tr>
            <td>
                <h2>Edit Profile</h2>
            </td>
            <td align="right">
                <a href="/Health%20Info/view/profile-view.php">Back to Profile</a> |
                <a href="logout.php">Logout</a>
            </td>
        </tr>
    </table>

    <h3 align="center">Edit Your Profile</h3>
    <form id="editProfileForm" onsubmit="return false;">
        <table border="1" cellpadding="10" width="50%" align="center">
            <tr>
                <td><b>First Name:</b></td>
                <td>
                    <input type="text" id="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>">
                    <p id="firstNameError" style="color: red;"></p>
                </td>
            </tr>
            <tr>
                <td><b>Last Name:</b></td>
                <td>
                    <input type="text" id="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>">
                    <p id="lastNameError" style="color: red;"></p>
                </td>
            </tr>
            <tr>
                <td><b>Contact Number:</b></td>
                <td>
                    <input type="text" id="contact_number" value="<?php echo htmlspecialchars($user['contact_number']); ?>">
                    <p id="contactNumberError" style="color: red;"></p>
                </td>
            </tr>
            <tr>
                <td><b>Address:</b></td>
                <td>
                    <textarea id="address" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                    <p id="addressError" style="color: red;"></p>
                </td>
            </tr>
            <?php if ($user['role'] === 'Doctor') : ?>
            <tr>
                <td><b>Specialty:</b></td>
                <td>
                    <input type="text" id="specialty" value="<?php echo htmlspecialchars($user['specialty']); ?>">
                    <p id="specialtyError" style="color: red;"></p>
                </td>
            </tr>
            <tr>
                <td><b>Years of Experience:</b></td>
                <td>
                    <input type="number" id="experience_years" value="<?php echo htmlspecialchars($user['experience_years']); ?>">
                    <p id="experienceError" style="color: red;"></p>
                </td>
            </tr>
            <?php endif; ?>
        </table>
        <p align="center">
            <button onclick="submitForm()" style="padding: 10px 20px; background-color: #518d9c; color: white; border: none; font-size: 16px;">Save Changes</button>
        </p>
    </form>

    <script>
        function submitForm() {
            const data = {
                first_name: document.getElementById('first_name').value,
                last_name: document.getElementById('last_name').value,
                contact_number: document.getElementById('contact_number').value,
                address: document.getElementById('address').value,
                specialty: document.getElementById('specialty')?.value || null,
                experience_years: document.getElementById('experience_years')?.value || null
            };

            const xhttp = new XMLHttpRequest();
            xhttp.open('POST', '', true);
            xhttp.setRequestHeader('Content-Type', 'application/json');
            xhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    const response = JSON.parse(this.responseText);
                    if (response.error) {
                        alert(response.error);
                    } else {
                        alert(response.success);
                        window.location.href = '/Health%20Info/view/profile-view.php';
                    }
                }
            };
            xhttp.send(JSON.stringify(data));
        }
    </script>
</body>
</html>