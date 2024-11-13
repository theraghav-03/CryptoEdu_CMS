<?php
require('connect.php');
$login=false;
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (empty($email) || empty($password)) {
        echo "Please fill in all fields.";
        exit;
    }
        $query = "SELECT * FROM users WHERE email = :email AND password = :password";
        $statement = $db->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':password', $password);
        $statement->execute();

        $num= $statement->rowCount();
        if($num == 1) {
            $login = true;
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $email;
            header("location: index.php");
            } else {
            echo "Error: Unable to login.";
            }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>
<body>
    <h1>login</h1>
    <form action="login.php" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="login">
    </form>
    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
</body>
</html>
   