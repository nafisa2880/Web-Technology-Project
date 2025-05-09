<?php
include_once "db.php"; 
session_start();


if (!isset($_SESSION['role'])) {
    $logged_in = false;  
} else {
    $logged_in = true;  
    $role = $_SESSION['role'];
}

$sql = "SELECT * FROM faq";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ Page</title>
    <style>
   
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            color: #0056b3;
            text-align: center;
            margin-top: 20px;
            font-size: 28px;
        }

        #faq-container {
            width: 80%;
            max-width: 800px;
            margin-top: 20px;
        }

        .faq {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .question {
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 10px;
        }

        .answer {
            color: #333;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .actions {
            margin-top: 10px;
        }

        .actions button {
            margin-right: 10px;
            padding: 8px 15px;
            background-color: #0056b3;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .actions button:hover {
            background-color: #003d80;
        }

        .add-question-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            width: 80%;
            max-width: 800px;
        }

        .add-question-container h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 22px;
        }

        .add-question-container textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
            box-sizing: border-box;
            resize: vertical;
        }

        .add-question-container button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .add-question-container button:hover {
            background-color: #218838;
        }

        a {
            text-decoration: none;
            color: #007BFF;
            font-size: 14px;
            margin-top: 20px;
            display: block;
            text-align: center;
        }

        a:hover {
            color: #0056b3;
        }

        .faq i {
            color: #888;
        }
    </style>
</head>
<body>
    <h1>Frequently Asked Questions</h1>
    <div id="faq-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="faq">';
                echo '<div class="question">' . htmlspecialchars($row["question"]) . '</div>';
                echo '<div class="answer">' . (!empty($row["answer"]) ? htmlspecialchars($row["answer"]) : '<i>Unanswered</i>') . '</div>';
                
                
                echo '<div class="actions">';
                
               
                if (isset($role) && $role === 'doctor' && empty($row["answer"])) {
                    echo '<form action="anstoques.php" method="post" style="display:inline;">
                            <input type="hidden" name="faq_id" value="' . $row["id"] . '">
                            <textarea name="answer" placeholder="Type your answer here..." required></textarea><br>
                            <button type="submit">Submit Answer</button>
                          </form>';
                }
                
          
                if (isset($role) && $role === 'admin') {
                    echo '<form action="deletefaq.php" method="post" style="display:inline;">
                            <input type="hidden" name="faq_id" value="' . $row["id"] . '">
                            <button type="submit">Delete</button>
                          </form>';
                }

                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>No FAQs found.</p>";
        }
        ?>
    </div>

    <?php if ($logged_in && isset($role) && $role === 'patient') { ?>
    
    <div class="add-question-container">
        <h2>Add a Question</h2>
        <form action="addques.php" method="post">
            <textarea name="question" rows="3" cols="50" placeholder="Type your question here..." required></textarea><br>
            <button type="submit">Submit</button>
        </form>
    </div>
    <?php } else if (!$logged_in) { ?>
        <p>Do you want to add a question to know something? Please login here. <a href="../view/login.html">Login Here</a></p>
    <?php } ?>

    <a href="../view/index1.html">Back to home</a>

    <?php $conn->close(); ?>
</body>
</html>
