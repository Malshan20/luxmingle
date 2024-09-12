<?php
include 'connection.php';

// Include PHPMailer classes
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

// Use PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $query = "SELECT * FROM `users` WHERE `email` = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $resetToken = bin2hex(random_bytes(32));
        $updateTokenQuery = "UPDATE `users` SET `reset_token` = '$resetToken' WHERE `email` = '$email'";
        $conn->query($updateTokenQuery);

        // Send reset email
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth   = true;
            $mail->Username   = 'malshandissanayaka246@gmail.com'; // SMTP username
            $mail->Password   = 'tbxjroiiznnzbjua'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('malshandissanayaka246@gmail.com', 'Malshan');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Click this link to reset your password: <a href='http://localhost/viva3/reset_password.php?token=$resetToken'>Reset Password</a>";

            $mail->send();
            echo 'Reset email sent!';
        } catch (Exception $e) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
    } else {
        echo "Email not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="resources/favicon.png" type="image/x-icon">
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Send Reset Link</button>
            <p><a href="signup.php">Register</a> | <a href="signin.php">Login</a></p>
        </form>
    </div>
</body>
</html>
