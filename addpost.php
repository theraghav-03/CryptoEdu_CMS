<?php
require('connect.php');
require('header.php');

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
$error_msg = '';

$category_query = "SELECT * FROM categories ORDER BY category_name ASC";
$categories = $db->query($category_query)->fetchAll(PDO::FETCH_ASSOC);

if ($_POST && !empty($_POST['title']) && !empty($_POST['content']) && !empty($_POST['category_id'])) {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);

    if (strlen($title) < 1 || strlen($content) < 1) {
        $error_msg = "Please fill in all fields";
    } else {
        $date_added = date('Y-m-d H:i:s');
        $user_id = $_SESSION['user_id'];

        $query = "INSERT INTO posts (title, content, category_id, date_added, user_id) 
                  VALUES (:title, :content, :category_id, :date_added, :user_id)";

        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        $statement->bindValue(':date_added', $date_added);
        $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        if ($statement->execute()) {
            echo "Post added successfully!";
            header("Location: index.php");
            exit;
        } else {
            $error_msg = "An error occurred while adding the post.";
        }
    }
} elseif ($_POST) {
    $error_msg = "Please select a category.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Add New Blog Post!</title>
</head>
<header>
<h1><a href="index.php">Crypto-Edu</a></h1>
</header>
<body>
    <div class="addpost">
        <h1>Add a New Lesson to CMS</h1>
        <?php if (!empty($error_msg)): ?>
            <p><?= htmlspecialchars($error_msg) ?></p>
        <?php endif; ?>  
        <form action="addpost.php" method="post">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title"><br>

            <label for="content">Content:</label><br>
            <textarea name="content" id="content" cols="60" rows="20"></textarea><br>

            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id" required>
                <option value="">Select a Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= ($category['category_id']) ?>">
                        <?= ($category['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>
            <input type="submit" value="Add Lesson"><br><br>
        </form>
    </div>
</body>
</html>
