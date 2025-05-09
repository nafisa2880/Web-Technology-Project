<?php
include_once "db.php";
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'patient') {
    echo "Access Denied: Only patients can add questions.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $question = mysqli_real_escape_string($conn, trim($_POST['question']));

    if (!empty($question)) {
        $sql = "INSERT INTO faq (question) VALUES ('$question')";

        if ($conn->query($sql)) {
            echo "Question added successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Please enter a valid question.";
    }
}

$conn->close();
header("Location: faq.php");
exit;
?>
