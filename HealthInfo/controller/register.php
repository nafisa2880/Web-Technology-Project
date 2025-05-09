<?php
include_once 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $role = $_POST['role'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $specialty = isset($_POST['specialty']) ? $_POST['specialty'] : null;
    $experience_years = isset($_POST['experience_years']) ? (int)$_POST['experience_years'] : null;

    $errors = [];

    // Validate required fields
    if (empty($first_name)) {
        $errors[] = "First name is required.";
    }
    if (empty($last_name)) {
        $errors[] = "Last name is required.";
    }
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email address is required.";
    }
    if (empty($_POST['password'])) {
        $errors[] = "Password is required.";
    }
    if (empty($dob)) {
        $errors[] = "Date of birth is required.";
    }
    if (empty($gender)) {
        $errors[] = "Gender is required.";
    }
    if (empty($role)) {
        $errors[] = "Role is required.";
    }
    if (empty($contact_number)) {
        $errors[] = "Contact number is required.";
    }

    // Handle role-specific validations
    if ($role === "Doctor") {
        if (empty($specialty)) {
            $errors[] = "Specialty is required for doctors.";
        }
        if (empty($experience_years)) {
            $errors[] = "Experience years are required for doctors.";
        }
    }

    // Redirect back if there are errors
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST; // Save form data to repopulate fields
        header('Location: ../view/register.html');
        exit;
    }

    // Build the SQL query
    $sql = "INSERT INTO users (first_name, last_name, username, email, password, dob, gender, role, contact_number, address, specialty, experience_years) 
            VALUES (
                '$first_name', 
                '$last_name', 
                '$username', 
                '$email', 
                '$password', 
                '$dob', 
                '$gender', 
                '$role', 
                '$contact_number', 
                '$address', 
                " . ($specialty ? "'$specialty'" : "NULL") . ", 
                " . ($experience_years !== null ? $experience_years : "NULL") . "
            )";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Registration successful. Please log in.');
                window.location.href = '../view/login.html';
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
