<?php
require('connect.php');
require('header.php');
// require('config.php');

// session_start();

// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' || $_SESSION['role'] !== 'user') {
//     echo"please login or signup to browse all leasons";
//     header("Location: login.php");
//     exit;
// }

$query = "SELECT * FROM posts order by date_added asc";
$statement = $db->prepare($query);
$statement->execute();
$posts= $statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Leasons</title>
</head>
<body>
    <h1>All Available Leassons : </h1>
    <h3>Click on the leason to view full details</h3>
    <!-- <input type="search"> -->
    <ul>
        <?php foreach($posts as $post): ?>
            <h3><li>
                <a href="fullpost.php?id=<?= $post['id'] ?>"><?= $post['title'] ?></a>
            </li></h3>
        <?php endforeach; ?>
</body>
</html>