<?php
include_once 'db.php';
session_start();
 

 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $email = trim($_POST['email']);
 

    if (empty($email)) {
        die("Error: Email is required.");
    }
 

    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
 
    if ($result->num_rows === 1) {
      
        $_SESSION['reset_email'] = $email;
        header("Location: ../view/resetpass.html");
        exit;
    } else {
        echo "Error: Email not registered.";
    }
 
    $stmt->close();
} else {
    echo "Invalid request.";
}
 
$conn->close();
?>
 