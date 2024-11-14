<?php
require('connect.php');
require('header.php');

$error_msg = '';

if($_POST && isset($_POST['update'])) {
    if ($_POST && isset($_POST['title']) && isset($_POST['content']) && isset($_POST['id'])) {
        //sanitize
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        //validate
        if (strlen($title) < 1 || strlen($content) < 1) {
            $error_msg = "Title and content must have at least 1 character";
        }else{
            //prepare sql statement
            $query = "UPDATE posts SET title = :title, content = :content , date_added = :date_added WHERE id = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':title', $title);
            $statement->bindValue(':content', $content);
            $statement->bindValue(':date_added', date('Y-m-d H:i:s'));          
            $statement->bindValue(':id', $id);
            $statement -> execute();

            header("Location: index.php?id={$id}");
            exit;
        }
    }
}
 else if($_POST && isset($_POST['delete'])) {
    if ($_POST &&  isset($_POST['id'])) {
        //sanitize
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        //prepare sql statement
        $query = "DELETE FROM posts WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id);

        //excetute 
        $statement -> execute();

        //redirect
        header("Location: index.php?id={$id}");
        exit;
    }
}
 else if(isset($_GET['id'])) {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        //prepare sql statement
        $query = "SELECT * FROM posts WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id , PDO::PARAM_INT);
        //execute
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
<h1><a href="index.php">My postss -Edit</a></h1>
</header>
<body>
     <div class="edit">
     <?php if(!empty($error_msg)): ?>
        <p><?= $error_msg ?></p>
        <?php endif; ?>
        <?php if($id): ?>
            <form action="edit.php" method="post">
                <input type="hidden" name = "id" value ="<?= $post['id'] ?> ">

                <label for="title">Title:</label>
                <input type="text" name="title" id="title" value="<?= $post['title'] ?>"><BR>
                <label for="content">Content:</label><br>
                <textarea name="content" id="content" cols="40" rows="30"><?= $post['content'] ?></textarea><br>
                <input type="submit" name = "update">
            </form>

            <form action="edit.php" method ="post">
                <input type="hidden" name = "id" value = "<?= $post['id'] ?> ">
                <input type="submit" name = "delete" value = "Delete" onclick = "return confirm('Are you sure?');">
            </form>
        <?php else: ?>
            <p>Post not found !</p>
        <?php endif; ?>
        <button> <a href="index.php">Home</a></button>

     </div>
</body>
</html>