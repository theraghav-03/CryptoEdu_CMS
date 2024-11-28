<?php
require('connect.php');
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

require('header.php');

$sort_column = 'date_added';
$sort_order = 'ASC';

if (isset($_GET['sort'])) {
    $sort_column = $_GET['sort'];
    $valid_columns = ['title', 'date_added'];
    if (!in_array($sort_column, $valid_columns)) {
        $sort_column = 'date_added';
    }
}

if (isset($_GET['order']) && $_GET['order'] === 'DESC') {
    $sort_order = 'DESC';
}

$query = "SELECT * FROM posts ORDER BY $sort_column $sort_order";
$statement = $db->prepare($query);
$statement->execute();
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Lessons</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th a {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>
    <h1>All Lessons</h1>
    <table>
        <thead>
            <tr>
                <th><a href="?sort=title&order=<?= $sort_order === 'ASC' ? 'DESC' : 'ASC' ?>">Title <?= $sort_column === 'title' ? ($sort_order === 'ASC' ? '▲' : '▼') : '' ?></a></th>
                <th><a href="?sort=date_added&order=<?= $sort_order === 'ASC' ? 'DESC' : 'ASC' ?>">Created At <?= $sort_column === 'date_added' ? ($sort_order === 'ASC' ? '▲' : '▼') : '' ?></a></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= htmlspecialchars($post['title']) ?></td>
                    <td><?= htmlspecialchars($post['date_added']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>