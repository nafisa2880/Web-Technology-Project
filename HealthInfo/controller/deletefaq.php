<?php
include_once "db.php";
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Access Denied: Only admins can delete FAQs.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $faq_id = intval($_POST['faq_id']);

    if ($faq_id > 0) {
        $sql = "DELETE FROM faq WHERE id = $faq_id";

        if ($conn->query($sql)) {
            echo "FAQ deleted successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Invalid FAQ ID.";
    }
}

$conn->close();
header("Location: faq.php");
exit;
?>
