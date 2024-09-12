<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    // Check user credentials
    $query = "SELECT * FROM `users` WHERE `username` = '$username' OR `email` = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            if ($remember) {
                setcookie('username', $username, time() + (86400 * 30), "/"); // 30 days
            }
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['id'];
            header("Location: home.php");
        } else {
            echo "Invalid credentials!";
        }
    } else {
        echo "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="resources/favicon.png" type="image/x-icon">
    <style>
        body {
            background-image: url('resources/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            display: flex;
            justify-content: flex-end;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.7);
            padding: 20px;
            border-radius: 10px;
            margin-left: 10%;
            max-width: 300px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign In</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username or Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <label>
                <input type="checkbox" name="remember"> Remember Me
            </label>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
            <p><a href="forgot_password.php">Forgot Password?</a></p>

        <hr style="margin: 20px 0;">
        <button type="button" onclick="window.location.href='admin_signin.php'" class="admin-button">Admin Sign In</button>
        </form>
    </div>
</body>
</html>
