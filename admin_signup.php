<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $query = "INSERT INTO `admin` (`username`, `email`, `password`) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo "Admin account created successfully!";
        header("Location: admin_signin.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="resources/favicon.png" type="image/x-icon">
</head>
<body style="background-image: url('resources/a_bg.jpg'); background-size: cover; background-position: center; display: flex; justify-content: flex-end; align-items: center; height: 100vh; margin: 0;">
    <div class="container" style="margin-right: 10%; max-width: 300px; background-color: rgba(255, 255, 255, 0.8); padding: 20px; border-radius: 10px;">
        <h2>Admin Signup</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Signup</button>
            <p>Already registered? <a href="admin_signin.php">Login</a></p>
        </form>
    </div>
</body>
</html>