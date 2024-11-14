<?php
require('connect.php');
require('header.php');
// require('config.php');

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    echo"please login as admin to add post";
    header("Location: login.php");
    exit;
}

$error_msg = '';

if ($_POST  && !empty($_POST['title']) && !empty($_POST['content'])) {
    //sanitize user input

    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    if(strlen($title)<1 || strlen($content)<1){

        $error_msg = "Please fill in all fields";
    } else {
        $date_added = date('Y-m-d H:i:s');
        $query = "INSERT INTO posts (title, content, date_added, user_id) VALUES (:title, :content, :date_added,'1')";

        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':date_added', $date_added);

        if($statement -> execute()){
            echo "Post added successfully!";
            header("Location: index.php?id={$id}");
            exit;
        } else {
            $error_msg = "Please fill in all fields";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Add new Blog Post!</title>
</head>
<header>
<h1><a href="index.php">Crypto-Edu</a></h1>
</header>
<body>
     <div class="addpost">
     <h1> Add new lesson to CMS</h1>
     <?php if (!empty($error_msg)): ?>
            <p<?= $error_msg ?> </p>
        <?php endif; ?>  
        <form action="addpost.php" method="post">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title"><BR>
            <label for="content">Content:</label><br>
            <textarea name="content" id="content" cols="60" rows="20"></textarea><br>
            <input type="submit" value="Add Lesson"><br><br>
        </form>
     </div>
</body>
</html>