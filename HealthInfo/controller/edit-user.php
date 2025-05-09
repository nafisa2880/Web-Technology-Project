
<?php

session_start();

// Check if admin is logged in

if (!isset($_SESSION['role'])) {
    echo "Please log in to access this page";
    echo "<br><a href='../view/login.html'>Login Here</a>";
    exit;
}


// Database connection
$conn = new mysqli("localhost", "root", "", "Healthinfo");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user ID is provided
if (!isset($_GET['id'])) {
    die("No user ID provided.");
}

$user_id = intval($_GET['id']);

// Fetch user details
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    die("User not found.");
}

// Handle form submission for updating the user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $role = $_POST['role'];
    $contact_number = trim($_POST['contact_number']);
    $address = trim($_POST['address']);
    $specialty = isset($_POST['specialty']) ? trim($_POST['specialty']) : null;
    $experience_years = isset($_POST['experience_years']) ? intval($_POST['experience_years']) : null;

    // Server-side validation
    $errors = [];
    if (empty($first_name)) {
        $errors[] = "First name is required.";
    }
    if (empty($last_name)) {
        $errors[] = "Last name is required.";
    }
    if (!preg_match("/^[0-9]{10,15}$/", $contact_number)) {
        $errors[] = "Contact number must be between 10 to 15 digits.";
    }
    if (empty($address)) {
        $errors[] = "Address is required.";
    }
    if ($role === 'Doctor') {
        if (empty($specialty)) {
            $errors[] = "Specialty is required for doctors.";
        }
        if ($experience_years < 0 || $experience_years > 50) {
            $errors[] = "Years of experience must be between 0 and 50.";
        }
    }

    if (empty($errors)) {
        $sql = "UPDATE users SET first_name = ?, last_name = ?, role = ?, contact_number = ?, address = ?, specialty = ?, experience_years = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssii", $first_name, $last_name, $role, $contact_number, $address, $specialty, $experience_years, $user_id);

        if ($stmt->execute()) {
            echo "<script>alert('User details updated successfully!'); window.location.href = '/Health%20Info/controller/manage-users.php';</script>";
            exit();
        } else {
            echo "<p style='color: red;'>Error updating user: " . $stmt->error . "</p>";
        }
    } else {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        h2 {
            text-align: center;
            color: #518d9c;
        }
        form {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        form input, form select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form button {
            width: 100%;
            padding: 10px;
            background-color: #518d9c;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #336b73;
        }
        .error {
            color: red;
            font-size: 14px;
        }
    </style>
    <script>
        function validateForm() {
            let isValid = true;

            // Validate First Name
            const firstName = document.getElementById('first_name').value.trim();
            if (firstName === "") {
                document.getElementById('firstNameError').textContent = "First name is required.";
                isValid = false;
            } else {
                document.getElementById('firstNameError').textContent = "";
            }

            // Validate Last Name
            const lastName = document.getElementById('last_name').value.trim();
            if (lastName === "") {
                document.getElementById('lastNameError').textContent = "Last name is required.";
                isValid = false;
            } else {
                document.getElementById('lastNameError').textContent = "";
            }

            // Validate Contact Number
            const contactNumber = document.getElementById('contact_number').value.trim();
            const contactRegex = /^[0-9]{10,15}$/;
            if (!contactRegex.test(contactNumber)) {
                document.getElementById('contactNumberError').textContent = "Contact number must be between 10-15 digits.";
                isValid = false;
            } else {
                document.getElementById('contactNumberError').textContent = "";
            }

            // Validate Address
            const address = document.getElementById('address').value.trim();
            if (address === "") {
                document.getElementById('addressError').textContent = "Address is required.";
                isValid = false;
            } else {
                document.getElementById('addressError').textContent = "";
            }

            // Validate Doctor-Specific Fields
            const role = document.getElementById('role').value;
            if (role === "Doctor") {
                const specialty = document.getElementById('specialty').value.trim();
                const experience = document.getElementById('experience_years').value;

                if (specialty === "") {
                    document.getElementById('specialtyError').textContent = "Specialty is required for doctors.";
                    isValid = false;
                } else {
                    document.getElementById('specialtyError').textContent = "";
                }

                if (experience < 0 || experience > 50 || experience === "") {
                    document.getElementById('experienceError').textContent = "Years of experience must be between 0 and 50.";
                    isValid = false;
                } else {
                    document.getElementById('experienceError').textContent = "";
                }
            }

            return isValid;
        }
    </script>
</head>
<body>
    <h2>Edit User</h2>
    <form method="post" action="" onsubmit="return validateForm();">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>">
        <p id="firstNameError" class="error"></p>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>">
        <p id="lastNameError" class="error"></p>

        <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="Patient" <?php echo ($user['role'] === 'Patient') ? 'selected' : ''; ?>>Patient</option>
            <option value="Doctor" <?php echo ($user['role'] === 'Doctor') ? 'selected' : ''; ?>>Doctor</option>
            <option value="Admin" <?php echo ($user['role'] === 'Admin') ? 'selected' : ''; ?>>Admin</option>
        </select>

        <label for="contact_number">Contact Number:</label>
        <input type="text" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($user['contact_number']); ?>">
        <p id="contactNumberError" class="error"></p>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
        <p id="addressError" class="error"></p>

        <?php if ($user['role'] === 'Doctor'): ?>
            <label for="specialty">Specialty:</label>
            <input type="text" id="specialty" name="specialty" value="<?php echo htmlspecialchars($user['specialty']); ?>">
            <p id="specialtyError" class="error"></p>

            <label for="experience_years">Years of Experience:</label>
            <input type="number" id="experience_years" name="experience_years" value="<?php echo htmlspecialchars($user['experience_years']); ?>">
            <p id="experienceError" class="error"></p>
        <?php endif; ?>

        <button type="submit">Update User</button>
    </form>
</body>
</html>

