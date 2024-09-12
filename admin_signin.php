<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM `admin` WHERE `username` = ? OR `email` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if ($password === $admin['password']) {
            session_start();
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            header("Location: admin_dashboard.php");
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
    <title>Admin Signin</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background-image: url('resources/a_bg.jpg'); background-size: cover; background-position: center; display: flex; justify-content: flex-end; align-items: center; height: 100vh; margin: 0;">
    <div class="container" style="margin-right: 10%; max-width: 300px; background-color: rgba(255, 255, 255, 0.8); padding: 20px; border-radius: 10px;">
        <h2>Admin Signin</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username or Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Signin</button>
            <p>Sign up to control<a href="admin_signup.php">Sign Up</a></p>
        </form>
    </div>
</body>
</html>
