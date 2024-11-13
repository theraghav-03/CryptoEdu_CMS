<?php 
require('connect.php'); 
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
require('header.php');

// echo "Crypto-Edu"; 
?>

<main>
    <h1>Crypto-Edu</h1>
    <p>Welcome to Crypto-Edu where you can learn everything you want to know about cryptocurrency and blockchain industry as a fresher ! </p>
</main>
    
<?php include('footer.php'); ?>


