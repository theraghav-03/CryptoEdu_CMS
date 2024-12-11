<?php
require('connect.php');
require('header.php');

// Get the category ID from the URL
$category_id = filter_input(INPUT_GET, 'category_id', FILTER_SANITIZE_NUMBER_INT);

// Fetch posts for the selected category
$query = "SELECT * FROM posts WHERE category_id = :category_id ORDER BY date_added DESC";
$statement = $db->prepare($query);
$statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
$statement->execute();
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Category Posts</title>
</head>
<body>
    <h1>Posts in This Category</h1>

    <?php if ($posts): ?>
        <ul>
            <?php foreach ($posts as $post): ?>
                <li>
                    <h2> <a href="fullpost.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a> </h2>
                    <p><?= substr($post['content'], 0, 100) ?>...</p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No posts available in this category.</p>
    <?php endif; ?>
</body>
</html>
