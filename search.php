<?php
require('connect.php');
require('header.php');

$searchQuery = $_GET['query'] ?? '';

if (!empty($searchQuery)) {
    $searchQuery = "%$searchQuery%";

    $query = "SELECT id, title, content 
              FROM posts 
              WHERE title LIKE :searchQuery OR content LIKE :searchQuery 
              ORDER BY date_added DESC";

    $statement = $db->prepare($query);
    $statement->bindParam(':searchQuery', $searchQuery);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
} else {
    $results = [];
}
?>

<h2>Search Results for "<?= htmlspecialchars($_GET['query'] ?? '') ?>"</h2>

<?php if (count($results) > 0): ?>
    <ul>
        <?php foreach ($results as $result): ?>
            <li>
                <a href="fullpost.php?id=<?= htmlspecialchars($result['id']) ?>">
                    <h3><?= htmlspecialchars($result['title']) ?></h3>
                </a>
                <p><?= htmlspecialchars(substr($result['content'], 0, 150)) ?>...</p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No results found.</p>
<?php endif; ?>
