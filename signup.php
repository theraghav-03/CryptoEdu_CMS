<?php
require('connect.php');
require('header.php');

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (empty($username) || empty($email) || empty($password)) {
        echo "Please fill in all fields.";
        exit;
    }
     $checkQuery = "SELECT * FROM users WHERE email = :email";
     $checkStatement = $db->prepare($checkQuery);
     $checkStatement->bindParam(':email', $email);
     $checkStatement->execute();
 
     if ($checkStatement->rowCount() > 0) {
         echo "Error: Email already exists, Try login !";
        //  header("Location: login.php");
         exit;
     }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, email, password , role) VALUES (:username, :email, :password , 'user')";
        $statement = $db->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':password', $password);

        if($statement->execute()) {
            echo "Sign up successful!";
            header("Location: login.php");
        } else {
            echo "Error: Unable to sign up."; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
</head>
<body>
    <h1>Sign up</h1>
    <form action="signup.php" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Sign up">
    </form>
    <p>Already have an account? <a href="login.php">Log in</a></p>
</body>
</html>
   