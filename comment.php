<?php
session_start();
require('connect.php');

$post_id = $_GET['id'] ?? 0;

// Handle comment submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'] ?? null;
    $date = date('Y-m-d H:i:s');

    if ($user_id) {
        $insertQuery = "INSERT INTO comments (comment, date, user_id, id) VALUES (:comment, :date, :user_id, :post_id)";
        $insertStmt = $db->prepare($insertQuery);
        $insertStmt->bindParam(':comment', $comment);
        $insertStmt->bindParam(':date', $date);
        $insertStmt->bindParam(':user_id', $user_id);
        $insertStmt->bindParam(':post_id', $post_id);

        if ($insertStmt->execute()) {
            echo "Comment added successfully!";
        } else {
            echo "Error: Unable to add comment.";
        }
    } else {
        echo "Error: Unable to identify user. Please log in again.";
    }
}

// Handle comment deletion (only for admins)
if (isset($_GET['delete_comment']) && $_SESSION['role'] === 'admin') {
    $comment_id = $_GET['delete_comment'];

    $deleteQuery = "DELETE FROM comments WHERE comment_id = :comment_id";
    $deleteStmt = $db->prepare($deleteQuery);
    $deleteStmt->bindParam(':comment_id', $comment_id);

    if ($deleteStmt->execute()) {
        echo "Comment deleted successfully!";
    } else {
        echo "Error deleting comment.";
    }
}

// Fetch and display comments
$query = "SELECT comments.comment_id, comments.comment, comments.date, users.username 
          FROM comments 
          JOIN users ON comments.user_id = users.user_id 
          WHERE comments.id = :post_id 
          ORDER BY comments.date DESC";

$statement = $db->prepare($query);
$statement->bindParam(':post_id', $post_id, PDO::PARAM_INT);
$statement->execute();
$comments = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Display Comments -->
<ul>
    <?php foreach ($comments as $comment): ?>
        <li>
            <strong><?= htmlspecialchars($comment['username']) ?></strong> (<?= htmlspecialchars($comment['date']) ?>):<br>
            <?= htmlspecialchars($comment['comment']) ?>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <!-- Delete Button for Admin -->
                <a href="fullpost.php?id=<?= htmlspecialchars($post_id) ?>&delete_comment=<?= htmlspecialchars($comment['comment_id']) ?>" 
                onclick="return confirm('Are you sure you want to delete this comment?');">Delete</a>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>
        <!-- Comment Form -->
        <form action="fullpost.php?id=<?= htmlspecialchars($post_id) ?>" method="post">
            <textarea name="comment" rows="4" cols="50" required></textarea><br>
            <input type="submit" value="Add Comment">
        </form>
