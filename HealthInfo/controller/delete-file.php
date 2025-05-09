<?php

session_start();


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}


$conn = new mysqli("localhost", "root", "", "Healthinfo");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_GET['id'])) {
    $file_id = $_GET['id'];

    
    $sql = "SELECT file_path FROM medical_files WHERE file_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $file_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $file = $result->fetch_assoc();
        $file_path = $file['file_path'];

        
        $delete_sql = "DELETE FROM medical_files WHERE file_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $file_id);

        if ($delete_stmt->execute()) {
            // Optionally delete the physical file
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            echo "<script>alert('File deleted successfully.'); window.location.href = 'medical-files.php';</script>";
        } else {
            echo "<script>alert('Error deleting file. Please try again.'); window.history.back();</script>";
        }

        $delete_stmt->close();
    } else {
        echo "<script>alert('File not found.'); window.history.back();</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('No file ID provided.'); window.history.back();</script>";
}

$conn->close();
?>
