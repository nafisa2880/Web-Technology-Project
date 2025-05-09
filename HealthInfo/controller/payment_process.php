<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'];
    $payment_method = $_POST['payment_method'];
    $doctor_name = $_POST['doctor_name'];
    $date = $_POST['date'];
    $amount = $_POST['amount'];

    // Database connection
    $servername = "localhost"; // Update with your database server
    $dbusername = "root";      // Update with your database username
    $dbpassword = "";          // Update with your database password
    $dbname = "healthcare";    // Update with your database name

    // Check if any fields are empty
    if (empty($username) || empty($payment_method) || empty($doctor_name) || empty($date) || empty($amount)) {
        // Redirect to transaction failure page without any specific message
        header("Location: ../views/transaction_result.php?status=failure");
        exit;
    }

    try {
        // Connect to database
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert payment details into the database
        $stmt = $conn->prepare("INSERT INTO payments (username, payment_method, doctor_name, date, amount) 
                                VALUES (:username, :payment_method, :doctor_name, :date, :amount)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->bindParam(':doctor_name', $doctor_name);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':amount', $amount);

        $stmt->execute();

        // Redirect to transaction success page
        header("Location: ../views/transaction_result.php?status=success&username=$username&payment_method=$payment_method&doctor_name=$doctor_name&date=$date&amount=$amount");
        exit;
    } catch (PDOException $e) {
        // If database connection or query fails, log the error and redirect to failure page
        error_log("Database error: " . $e->getMessage());
        header("Location: ../views/transaction_result.php?status=failure");
        exit;
    }
} else {
    // If the request method is not POST, redirect to the payment page
    header("Location: ../views/payment.html");
    exit;
}
?>
