<?php
session_start();

// Ensure the user is logged in as a patient
if (!isset($_SESSION['role'])) {
    echo "Please log in to access this page";
    echo "<br><a href='../view/login.html'>Login Here</a>";
    exit;
}


// Get the patient ID from the session
// $patient_id = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "healthinfo");

// Check database connection
if ($conn->connect_error) {
    die("<script>
            alert('Database connection failed: " . $conn->connect_error . "');
            window.location.href = '../view/home.php'; // Redirect to home page
         </script>");
}

// Handle adding a favorite doctor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $doctor_id = intval($data['doctor_id'] ?? 0);

    if ($doctor_id > 0) {
        // Check if the doctor is already in the patient's favorites
        $check_sql = "SELECT * FROM favourite_doctors WHERE patient_id = $patient_id AND doctor_id = $doctor_id";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows === 0) {
            // Add the doctor to favorites
            $insert_sql = "INSERT INTO favourite_doctors (patient_id, doctor_id) VALUES ($patient_id, $doctor_id)";
            if ($conn->query($insert_sql) === TRUE) {
                echo json_encode(["success" => true, "message" => "Doctor added to favourites."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to add doctor to favourites."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Doctor is already in your favourites."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Please select a valid doctor."]);
    }
    $conn->close();
    exit();
}

// Fetch the list of favorite doctors
$sql = "SELECT fd.favourite_id, CONCAT(d.first_name, ' ', d.last_name) AS doctor_name, d.specialty 
        FROM favourite_doctors fd
        JOIN users d ON fd.doctor_id = d.user_id
        -- WHERE fd.patient_id = $patient_id";
$result = $conn->query($sql);

$favourite_doctors = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $favourite_doctors[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Favourite Doctors</title>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const doctorSelect = document.getElementById("doctor_id");
            const addDoctorButton = document.getElementById("add-doctor-button");

            addDoctorButton.addEventListener("click", function () {
                const doctorId = doctorSelect.value;

                if (!doctorId) {
                    alert("Please select a doctor before submitting.");
                    return;
                }

                const data = JSON.stringify({ doctor_id: doctorId });

                const xhttp = new XMLHttpRequest();
                xhttp.open("POST", "", true);
                xhttp.setRequestHeader("Content-Type", "application/json");
                xhttp.onreadystatechange = function () {
                    if (this.readyState === 4 && this.status === 200) {
                        const response = JSON.parse(this.responseText);
                        alert(response.message);
                        if (response.success) {
                            window.location.reload(); // Reload to refresh the table
                        }
                    }
                };
                xhttp.send(data);
            });
        });
    </script>
</head>
<body>
    <h2>My Favourite Doctors</h2>
    <table>
        <tr>
            <th>Doctor Name</th>
            <th>Specialty</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($favourite_doctors as $doctor) : ?>
            <tr>
                <td><?php echo htmlspecialchars($doctor['doctor_name']); ?></td>
                <td><?php echo htmlspecialchars($doctor['specialty']); ?></td>
                <td>
                    <a href="../controller/remove-favourite-doctor.php?id=<?php echo $doctor['favourite_id']; ?>" 
                       onclick="return confirm('Are you sure you want to remove this doctor?');">
                       Remove
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Add a Favourite Doctor</h3>
    <select id="doctor_id">
        <option value="">--Select Doctor--</option>
        <?php
        $doctor_sql = "SELECT user_id, CONCAT(first_name, ' ', last_name) AS full_name, specialty FROM users WHERE role = 'Doctor'";
        $doctor_result = $conn->query($doctor_sql);
        if ($doctor_result->num_rows > 0) {
            while ($row = $doctor_result->fetch_assoc()) {
                echo "<option value='{$row['user_id']}'>{$row['full_name']} ({$row['specialty']})</option>";
            }
        }
        ?>
    </select>
    <button id="add-doctor-button">Add to Favourites</button>
</body>
</html>
