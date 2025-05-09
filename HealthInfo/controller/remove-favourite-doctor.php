<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "healthinfo");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$patient_id = $_SESSION['user_id'];

// Check if favourite_id is  in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $favourite_id = intval($_GET['id']);

    // check    if the favourite belongs to the logged-in patient
    $sql = "SELECT favourite_id FROM favourite_doctors WHERE favourite_id = ? AND patient_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error: Statement preparation failed. " . $conn->error);
    }

    $stmt->bind_param("ii", $favourite_id, $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Delete 
        $delete_sql = "DELETE FROM favourite_doctors WHERE favourite_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);

        if (!$delete_stmt) {
            die("Error: Delete statement preparation failed. " . $conn->error);
        }

        $delete_stmt->bind_param("i", $favourite_id);

        if ($delete_stmt->execute()) {
            echo "<script>
                    alert('Doctor removed from favourites successfully!');
                    window.location.href = 'favourite-doctors.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error: Could not remove the doctor. Please try again later.');
                    window.location.href = 'favourite-doctors.php';
                  </script>";
        }

        $delete_stmt->close();
    } else {
        echo "<script>
                alert('Invalid request or doctor not found in your favourites.');
                window.location.href = 'favourite-doctors.php';
              </script>";
    }

    $stmt->close();
} else {
    echo "<script>
            alert('Invalid request. No favourite doctor ID provided.');
            window.location.href = 'favourite-doctors.php';
          </script>";
}

$conn->close();
?>
