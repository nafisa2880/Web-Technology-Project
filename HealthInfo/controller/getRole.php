<?php

session_start(); // Start session

if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
    $response = array(
        'username' => $_SESSION['username'],
        'role' => $_SESSION['role']
    );
    echo json_encode($response);
} else {
    echo json_encode(array(
        'username' => 'undefined',
        'role' => 'null'
    ));
}
?>
