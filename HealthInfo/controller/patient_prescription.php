<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patientName = $_POST['patient_name'];

   
    if (empty($patientName)) {
        echo "Please enter your name.";
        exit;
    }

    
    $conn = mysqli_connect('localhost', 'root', '', 'healthinfo');

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

  
    $patientName = mysqli_real_escape_string($conn, $patientName);

   
    $query = "SELECT * FROM prescriptions WHERE patient_name = '$patientName'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
       
        echo "<h2>Prescription(s) for $patientName</h2>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p><strong>Doctor:</strong> " . htmlspecialchars($row['doctor_name']) . "</p>";
            echo "<p><strong>Medicine:</strong> " . htmlspecialchars($row['medicine']) . "</p>";
            echo "<p><strong>Dosage:</strong> " . htmlspecialchars($row['dosage']) . "</p>";
            echo "<p><strong>Additional Notes:</strong> " . htmlspecialchars($row['additional_notes']) . "</p>";
            echo "<p><strong>Date Issued:</strong> " . htmlspecialchars($row['date_issued']) . "</p>";
            echo "<hr>";
        }
    } else {
        echo "No prescriptions found for $patientName.";
    }

   
    mysqli_close($conn);
} else {
    header('location: ../views/patient_prescription.html');
    exit;
}
?>


<br><br>
<a href="../controller/patient_prescription.html">
    <button>Back to Prescription Search</button>
</a>
