<?php 
require('config.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    if ($username === '' || $password === '' || $role === '') {
            echo 'All fields are required.';
    } else {
        $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";

        $records = $conn->query($query);
        
        if ($records){
            header("Location: " . "login.php");
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./style/signup.css">
        <title>Sign Up</title>
    </head>
    <body>
        <div class="signupContainer">
            <h1 class="signupTitle">Sign Up</h1>
            <form action="signup.php"  method="POST">
                <input class="signupInput" type="text" required name="username" placeholder="Username">
                <input class="signupInput" type="password" required name="password" placeholder="Password">
                <select class="signupSelect" name="role">
                    <option value="fan">Fan</option>
                    <option value="admin">Admin</option>
                </select>
                <button class="signupButton" type="submit">Signup</button>
            </form>
            <a class="backButton" href="login.php">Back to Login</a>
        </div>
    </body>
</html>