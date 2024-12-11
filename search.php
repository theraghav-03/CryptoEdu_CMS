<?php
require('connect.php');
require('header.php');

$categoryQuery = "SELECT category_id, category_name FROM categories";
$categoryStatement = $db->prepare($categoryQuery);
$categoryStatement->execute();
$categories = $categoryStatement->fetchAll(PDO::FETCH_ASSOC);

$searchQuery = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_STRING);
$category = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT);

if ($searchQuery !== null && $searchQuery !== false) {
    $searchQuery = "%$searchQuery%";

    $query = "SELECT id, title, content 
              FROM posts 
              WHERE (title LIKE :searchQuery OR content LIKE :searchQuery)";

    if ($category !== null && $category !== false) {
        $query .= " AND category_id = :category";
    }

    $query .= " ORDER BY date_added DESC";

    $statement = $db->prepare($query);
    $statement->bindParam(':searchQuery', $searchQuery);

    if ($category !== null && $category !== false) {
        $statement->bindParam(':category', $category);
    }

    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
} else {
    $results = [];
}
?>

<form method="GET" action="">
    <input type="text" name="query" value="<?= ($_GET['query'] ?? '') ?>" placeholder="Search...">
    <select name="category">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= ($cat['category_id']) ?>" <?= $category == $cat['category_id'] ? 'selected' : '' ?>>
                <?= ($cat['category_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Search</button>
</form>

<h2>Search Results for "<?= ($_GET['query'] ?? '') ?>"</h2>

<?php if (count($results) > 0): ?>
    <ul>
        <?php foreach ($results as $result): ?>
            <li>
                <a href="fullpost.php?id=<?= ($result['id']) ?>">
                    <h3><?= ($result['title']) ?></h3>
                </a>
                <p><?= (substr($result['content'], 0, 150)) ?>...</p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No results found.</p>
<?php endif; ?>
