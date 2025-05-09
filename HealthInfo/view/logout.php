
<?php
include_once 'db.php';
session_start();
 

if (isset($_POST['confirm_logout'])) {
    if ($_POST['confirm_logout'] === 'yes') {
       
        session_unset();
        session_destroy();
     
        header("Location: ../view/index.html");
        exit;
    } else {
        
        header("Location: ../view/index.html");
        exit;
    }
}
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Logout</title>
    <style>
        
    </style>
</head>
<body>
    <h1>Are you sure you want to log out?</h1>
    <form method="post">
        <button type="submit" name="confirm_logout" value="yes" class="button yes">Yes, Logout</button>
        <button type="submit" name="confirm_logout" value="no" class="button no">Cancel</button>
    </form>
</body>
</html>
 