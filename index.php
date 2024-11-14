<?php 
require('connect.php'); 
require('header.php');
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$role = ($_SESSION['role']) ;
$name = ($_SESSION['username']);

function formatDate($date){
    return date('F d, Y, h:i a', strtotime($date));
}

$query = "SELECT * FROM posts order by date_added asc limit 5";
$statement = $db->prepare($query); 
$statement->execute(); 
$posts= $statement->fetchAll();

$sql = "SELECT * FROM posts";
$state = $db->prepare($sql);
$state->execute();
$allposts = $state->fetchAll();

?>

<main>
    <h1>Crypto-Edu</h1>
    <h3>Welcome <?= $role ?> <?= $name ?> to Crypto-Edu where you can learn everything you want to know about cryptocurrency and blockchain industry as a fresher ! </h3>

    <?php if ($role === 'admin'): ?>
        <ul>
        <?php foreach ($allposts as $posts): ?>
                    <li>
                        <a href="fullpost.php?id=<?= ($posts['id']) ?>">
                            <h3>Chapter: <?= ($posts['title']) ?></h3>
                        </a>
                    </li>
                    <?php if (strlen($posts['content']) > 300): ?>
                        <h5><li>
                            <?= (substr($posts['content'], 0, 300)) ?>
                            <a href="fullpost.php?id=<?= ($posts['id']) ?>">...read more</a>
                        </li></h5>
                    <?php else: ?>
                        <li> <?= ($posts['content']) ?></li>
                    <?php endif; ?>
                    <li>Last updated: <?= (formatDate($posts['date_added'])) ?></li>
                        <li>
                            <a href="edit.php?id=<?= $posts['id'] ?>">Edit</a>
                            <a href="delete.php?id=<?= $posts['id'] ?>">Delete</a>
                        </li>
                    <br><br>
                <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="post">
        <ul>
        <?php foreach ($posts as $post): ?>
                    <li>
                        <a href="fullpost.php?id=<?= ($post['id']) ?>">
                            <h3>Chapter: <?= ($post['title']) ?></h3>
                        </a>
                    </li>
                    <?php if (strlen($post['content']) > 250): ?>
                        <h5><li>
                            <?= (substr($post['content'], 0, 250)) ?>
                            <a href="fullpost.php?id=<?= ($post['id']) ?>">...read more</a>
                        </li></h5>
                    <?php else: ?>
                        <li> <?= ($post['content']) ?></li>
                    <?php endif; ?>
                    <li>Last updated: <?= (formatDate($post['date_added'])) ?></li>
                    <br><br>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <?php if($role !== 'user'): ?>
            <h4>For more such content and learnings <a href="signup.php">Signup today!</a></h4>
        <?php endif; ?>
     </div>

      
</main>
    
<?php include('footer.php'); ?>


