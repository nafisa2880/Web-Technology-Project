<?php
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctorName = $_POST['doctor_name'];
    $patientName = $_POST['patient_name'];
    $medicine = $_POST['medicine'];
    $dosage = $_POST['dosage'];
    $additionalNotes = $_POST['additional_notes'];
    $dateIssued = $_POST['date_issued'];
 
    // Validate inputs
    if (empty($doctorName) || empty($patientName) || empty($medicine) || empty($dosage) || empty($dateIssued)) {
        echo "All fields are required except additional notes.";
        exit;
    }
 
    // Database connection
    $conn = mysqli_connect('localhost', 'root', '', 'healthinfo');
 
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }
 
    // Insert prescription into the prescriptions table
    $prescriptionQuery = "INSERT INTO prescriptions (doctor_name, patient_name, medicine, dosage, additional_notes, date_issued)
                           VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $prescriptionQuery);
    mysqli_stmt_bind_param($stmt, 'ssssss', $doctorName, $patientName, $medicine, $dosage, $additionalNotes, $dateIssued);
    $result = mysqli_stmt_execute($stmt);
 
    if ($result) {
        echo "<h2>Prescription Saved Successfully</h2>";
        echo "<p><strong>Doctor Name:</strong> " . htmlspecialchars($doctorName) . "</p>";
        echo "<p><strong>Patient Name:</strong> " . htmlspecialchars($patientName) . "</p>";
        echo "<p><strong>Medicine:</strong> " . htmlspecialchars($medicine) . "</p>";
        echo "<p><strong>Dosage:</strong> " . htmlspecialchars($dosage) . "</p>";
        echo "<p><strong>Additional Notes:</strong> " . htmlspecialchars($additionalNotes) . "</p>";
        echo "<p><strong>Date Issued:</strong> " . htmlspecialchars($dateIssued) . "</p>";
    } else {
        echo "Failed to save prescription. Please try again.";
    }
 
    // Close the connection
    mysqli_close($conn);
} else {
    header('location: ../views/prescription.html');
    exit;
}
?>