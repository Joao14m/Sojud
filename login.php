<?php 
require('config.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
            echo 'All fields are required.';
    } else {
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $records = $conn->query($query);

        if ($records && $records->num_rows === 1) {
            $user = $records->fetch_assoc();
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['id'] = $user['id'];
            $_SESSION['user_id'] = $user['id'];

            if ($user['role'] === 'admin') {
                header("Location: " . "dashboardAdmin.php");
            } else
                header("Location: " . "dashboardFan.php");
            exit;
        } else {
            echo "Invalid username or password.";
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
        <link rel="stylesheet" href="./style/login.css">
        <title>Login</title>
    </head>
    <body>
        <div class="loginContainer">
            <h1 class="loginTitle">Login</h1>
            <form action="login.php"  method="POST">
                <input class="loginInput" type="text" required name="username" placeholder="Username"/><br>
                <input class="loginInput" type="password" required name="password" placeholder="Password"/><br>
                <button class="loginButton" type="submit">Login</button>
            </form>
            <a class="registerButton" href="signup.php">Sign Up</a>
        </div>
    </body>
</html>