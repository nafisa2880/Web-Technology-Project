<?php
include_once "db.php";
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    echo "Access Denied: Only doctors can answer questions.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $faq_id = intval($_POST['faq_id']);
    $answer = mysqli_real_escape_string($conn, trim($_POST['answer']));

    if ($faq_id > 0 && !empty($answer)) {
        $sql = "UPDATE faq SET answer = '$answer' WHERE id = $faq_id";

        if ($conn->query($sql)) {
            echo "Answer submitted successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Invalid FAQ ID or empty answer.";
    }
}

$conn->close();
header("Location: faq.php");
exit;
?>
