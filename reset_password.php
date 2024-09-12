<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['password'];
    $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);

    $query = "SELECT * FROM `users` WHERE `reset_token` = '$token'";
    $result = $conn->query($query);

    if ($result->num_rows === 1) {
        $updateQuery = "UPDATE `users` SET `password_hash` = '$passwordHash', `reset_token` = NULL WHERE `reset_token` = '$token'";
        if ($conn->query($updateQuery) === TRUE) {
            echo "Password reset successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Invalid token!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="resources/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form method="POST" action="">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <input type="password" name="password" placeholder="New Password" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
