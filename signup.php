<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Check if user exists
    $checkUser = $conn->query("SELECT * FROM `users` WHERE `email` = '$email' OR `username` = '$username'");
    if ($checkUser->num_rows > 0) {
        echo "User already exists!";
    } else {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO `users` (`username`, `email`,`password`, `password_hash`, `created_at`) VALUES ('$username', '$email','$password', '$passwordHash', NOW())";
        if ($conn->query($query) === TRUE) {
            echo "User registered successfully!";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="resources/favicon.ico" type="image/x-icon">
    <style>
        body {
    background-image: url('resources/background.jpg');
    background-size: cover;
    background-position: center;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    height: 100vh;
    margin: 0;
}
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
            <p>Already registered? <a href="signin.php">Login</a></p>
        </form>
    </div>
</body>
</html>
