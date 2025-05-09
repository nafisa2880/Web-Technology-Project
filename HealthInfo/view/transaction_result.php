<?php
// Get the status from the URL
$status = $_GET['status'];

echo '<table width="100%" height="100%"><tr><td align="center" valign="middle">';

echo '<table border="1" cellpadding="20"><tr><td>';

if ($status === 'success') {
    // If transaction is successful, show the details
    $username = $_GET['username'];
    $payment_method = $_GET['payment_method'];
    $doctor_name = $_GET['doctor_name'];
    $date = $_GET['date'];
    $amount = $_GET['amount'];

    echo "<h2>Transaction Successful!</h2>";
    echo "<p><b>User Name:</b> $username</p>";
    echo "<p><b>Payment Method:</b> $payment_method</p>";
    echo "<p><b>Doctor's Name:</b> $doctor_name</p>";
    echo "<p><b>Date:</b> $date</p>";
    echo "<p><b>Amount:</b> $amount</p>";
} else {
    // If transaction failed, just show a failure message
    echo "<h2>Transaction Failed</h2>";
}

echo '</td></tr></table>';
echo '</td></tr></table>';
?>
