<?php
require('connect.php');
require('header.php');

$error_msg = '';

$category_query = "SELECT * FROM categories ORDER BY category_name ASC";
$categories = $db->query($category_query)->fetchAll(PDO::FETCH_ASSOC);

if ($_POST && isset($_POST['update'])) {
    if (isset($_POST['title'], $_POST['content'], $_POST['id'], $_POST['category_id'])) {
        // Sanitize inputs
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);

        // Validate inputs
        if (strlen($title) < 1 || strlen($content) < 1) {
            $error_msg = "Title and content must have at least 1 character.";
        } elseif (empty($category_id)) {
            $error_msg = "Please select a valid category.";
        } else {
            $query = "UPDATE posts SET title = :title, content = :content, category_id = :category_id, date_added = :date_added WHERE id = :id";  
            $statement = $db->prepare($query);
            $statement->bindValue(':title', $title);
            $statement->bindValue(':content', $content);
            $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
            $statement->bindValue(':date_added', date('Y-m-d H:i:s'));
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();

            header("Location: index.php?id={$id}");
            exit;
        }
    }
} elseif ($_POST && isset($_POST['delete'])) {
    if (isset($_POST['id'])) {
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $query = "DELETE FROM posts WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        header("Location: index.php");
        exit;
    }
} elseif (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT * FROM posts WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $post = $statement->fetch();
} else {
    $id = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Edit this Post!</title>
</head>
<header>
    <h1><a href="index.php">My Posts - Edit</a></h1>
</header>
<body>
    <div class="edit">
        <?php if (!empty($error_msg)): ?>
            <p><?= ($error_msg) ?></p>
        <?php endif; ?>
        <?php if ($id && $post): ?>
            <form action="edit.php" method="post">
                <input type="hidden" name="id" value="<?= ($post['id']) ?>">

                <label for="title">Title:</label>
                <input type="text" name="title" id="title" value="<?= ($post['title']) ?>"><br>

                <label for="content">Content:</label><br>
                <textarea name="content" id="content" cols="40" rows="10"><?= ($post['content']) ?></textarea><br>

                <label for="category_id">Category:</label>
                <select name="category_id" id="category_id" required>
                    <option value="">Select a Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= ($category['category_id']) ?>" 
                            <?= $post['category_id'] == $category['category_id'] ? 'selected' : '' ?>>
                            <?= ($category['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>
                <input type="submit" name="update" value="Update Post">
            </form>
            <form action="edit.php" method="post">
                <input type="hidden" name="id" value="<?= ($post['id']) ?>">
                <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure?');">
            </form>
        <?php else: ?>
            <p>Post not found!</p>
        <?php endif; ?>
        <button><a href="index.php">Home</a></button>
    </div>
</body>
</html>
