<?php
require('connect.php'); 
require('header.php');

function formatDate($date){
    return date('F d, Y, h:i', strtotime($date));
}

// Fetch post details
$query = "SELECT posts.*, categories.category_name 
          FROM posts 
          LEFT JOIN categories ON posts.category_id = categories.category_id 
          WHERE id = :id LIMIT 1";
$statement = $db->prepare($query); 
$statement->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
$statement->execute(); 
$post = $statement->fetch();

if (!$post) {
    echo "<p>Post not found!</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>CRYPTO_EDU><?= htmlspecialchars($post['title']) ?></title>
</head>
<body>
    <div class="post">
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <h3><?= htmlspecialchars($post['content']) ?></h3>
        <p>Category: <strong><?= htmlspecialchars($post['category_name'] ?? 'Uncategorized') ?></strong></p>
        <h4>Last updated: <?= formatDate($post['date_added']) ?></h4>
        <button><a href="index.php">Done</a></button>
    </div>
    <?php include('comment.php'); ?>
</body>
</html>
<?php include('footer.php'); ?>
