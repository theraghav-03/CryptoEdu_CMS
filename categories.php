<?php
require('connect.php');
require('header.php');
session_start();

$isAdmin = isset($_SESSION['loggedin']) && $_SESSION['role'] === 'admin';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAdmin) {
    $category_name = $_POST['category_name'];

    if (!empty($category_name)) {
        if (isset($_POST['category_id']) && !empty($_POST['category_id'])) {
            $query = "UPDATE categories SET category_name = :category_name WHERE category_id = :category_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':category_id', $_POST['category_id'], PDO::PARAM_INT);
        } else {
            $query = "INSERT INTO categories (category_name) VALUES (:category_name)";
            $stmt = $db->prepare($query);
        }
        $stmt->bindParam(':category_name', $category_name);
        $stmt->execute();
        $message = isset($_POST['category_id']) ? "Category updated successfully!" : "Category added successfully!";
    } else {
        $message = "Category name cannot be empty.";
    }
}

$query = "SELECT * FROM categories ORDER BY category_name ASC";
$categories = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Manage Categories</title>
</head>
<body>
    <h1>Manage Categories</h1>

    <?php if (!empty($message)): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if ($isAdmin): ?>
        <form method="POST">
            <input type="hidden" name="category_id" value="<?= $_GET['edit'] ?? '' ?>">
            <label for="category_name">Category Name:</label>
            <input type="text" name="category_name" id="category_name" value="<?= $_GET['name'] ?? '' ?>" required>
            <button type="submit"><?= isset($_GET['edit']) ? 'Update' : 'Add' ?> Category</button>
        </form>
    <?php endif; ?>

    <h2>All Categories</h2>
    <table>
        <thead>
            <tr>
                <th>Category Name</th>
                <?php if ($isAdmin): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td>
                        <a href="categoriesList.php?category_id=<?= $category['category_id'] ?>">
                            <?= htmlspecialchars($category['category_name']) ?>
                        </a>
                    </td>
                    <?php if ($isAdmin): ?>
                        <td>
                            <a href="?edit=<?= $category['category_id'] ?>&name=<?= htmlspecialchars($category['category_name']) ?>">Edit</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
