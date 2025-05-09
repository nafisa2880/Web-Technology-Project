<?php
session_start();
include_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $dietPlanId = $_GET['id'];
    $deleteQuery = "DELETE FROM dietPlans WHERE id = $dietPlanId";
    $conn->query($deleteQuery);

    echo "Diet plan deleted successfully!";
}
?>
