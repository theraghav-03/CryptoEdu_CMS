<?php

require('connect.php'); 
require('header.php');

    function formatDate($date){
        return date('F d, Y, h:i', strtotime($date));
    }

    $query = "SELECT * FROM posts where id = :id limit 1";
    $statement = $db->prepare($query); 
    $statement->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $statement->execute(); 
    $post= $statement->fetch();
?>

<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    
    <title>CRYPTO_EDU><?=($post['title']) ?></title>
</head>
<header>
</header>
<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
     <div class="post">
        <h1><?= $post['title']?></h1>
        <h3><?= $post['content']?></h3>
        <h4> Last updated: <?=(formatDate($post['date_added']))?> </h4>
        <!-- <button><a href="edit.php?id=<?= ($post['id']) ?>">Edit</a></button> -->
        <button><a href="index.php">Done</a></button>
     </div>
     <?php include('comment.php'); ?>
</body>
</html>
<?php include('footer.php'); ?>