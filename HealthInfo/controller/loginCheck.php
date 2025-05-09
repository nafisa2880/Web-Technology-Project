<?php
include_once 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

  
  

   
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        
        if (password_verify($password, $user['password'])) {
           
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email']; 

            echo "<script>
                    alert('Welcome {$user['first_name']}. You have registered as a {$user['role']} here.');
                    window.location.href = '../view/index1.html'; // Redirect to homepage or user-specific page
                  </script>";
        } else {
            echo "<script>
                    alert('Invalid password. Please try again.');
                    window.location.href = '../view/login.html'; // Redirect back to login page
                  </script>";
        }
    } else {
        echo "<script>
                alert('You haven\'t registered yet. Please complete your registration.');
                window.location.href = '../view/register.html'; // Redirect to registration page if user does not exist
              </script>";
    }

    $conn->close();
}
?>
