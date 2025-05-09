<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctorName = $_POST['doctor_name'];
    $day = $_POST['day'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];

    if (empty($doctorName) || empty($day) || empty($startTime) || empty($endTime)) {
        echo "All fields are required.";
        exit;
    }

    $conn = mysqli_connect('localhost', 'root', '', 'healthinfo');

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Check if the `availability` table exists
    $checkTableQuery = "SHOW TABLES LIKE 'availability'";
    $tableExists = mysqli_query($conn, $checkTableQuery);

    if (mysqli_num_rows($tableExists) === 0) {
        die("The 'availability' table does not exist. Please create it in the database.");
    }

    $doctorName = mysqli_real_escape_string($conn, $doctorName);
    $day = mysqli_real_escape_string($conn, $day);
    $startTime = mysqli_real_escape_string($conn, $startTime);
    $endTime = mysqli_real_escape_string($conn, $endTime);

    $doctorQuery = "SELECT id FROM doctors WHERE name = '$doctorName'";
    $result = mysqli_query($conn, $doctorQuery);
    $doctor = mysqli_fetch_assoc($result);

    if (!$doctor) {
        echo "Doctor not found. Please check the name and try again.";
        mysqli_close($conn);
        exit;
    }

    $doctorId = $doctor['id'];

    $availabilityQuery = "INSERT INTO availability (doctor_id, doctor_name, day, start_time, end_time) 
                           VALUES ('$doctorId', '$doctorName', '$day', '$startTime', '$endTime')";
    $result = mysqli_query($conn, $availabilityQuery);

    if ($result) {
        $insertedId = mysqli_insert_id($conn);

        echo "<h2>Doctor Availability Saved Successfully!</h2>";
        echo "<p>Doctor: " . $doctorName . "</p>";
        echo "<p>Day: " . $day . "</p>";
        echo "<p>Start Time: " . $startTime . "</p>";
        echo "<p>End Time: " . $endTime . "</p>";
    } else {
        echo "Failed to save doctor availability. Please try again.";
    }

    mysqli_close($conn);
} else {
    header('location: ../views/docAvailability.html');
    exit;
}
?>

<br><br>
<a href="../views/docAvailability.html">
    <button>Back</button>
</a>
