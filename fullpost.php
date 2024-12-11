<?php
require('connect.php');
require('header.php');

$category_id = filter_input(INPUT_GET, 'category_id', FILTER_SANITIZE_NUMBER_INT);

if ($category_id) {
    $query = "SELECT * FROM posts WHERE category_id = :category_id ORDER BY date_added DESC";
    $statement = $db->prepare($query);
    $statement->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $statement->execute();
    $posts = $statement->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Invalid category selected.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Posts in Category</title>
</head>
<body>
    <h1>Posts in Category</h1>
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h2><?= htmlspecialchars($post['title']) ?></h2>
                <p><?= htmlspecialchars($post['content']) ?></p>
                <small>Posted on <?= htmlspecialchars($post['date_added']) ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No posts found in this category.</p>
    <?php endif; ?>
</body>
</html>
