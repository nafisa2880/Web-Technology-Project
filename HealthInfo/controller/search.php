<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search_type = $_POST['search_type'];
    $query = trim($_POST['query']);

    $host = 'localhost';
    $user = 'root'; 
    $password = ''; 
    $dbname = 'healthinfo';

    $conn = mysqli_connect($host, $user, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($search_type && $query) {
        $sql = '';
        if ($search_type === 'name') {
            $sql = "SELECT * FROM doctors WHERE name LIKE '%$query%'";
        } elseif ($search_type === 'specialty') {
            $sql = "SELECT * FROM doctors WHERE specialty LIKE '%$query%'";
        }

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<h2>Search Results:</h2>";
            echo "<ul>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li>" . $row['name'] . " - " . $row['specialty'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "No results found.";
        }
    } else {
        echo "Please select a search type and enter a query.";
    }

    mysqli_close($conn);
} else {
    echo "Invalid request.";
}
?>
