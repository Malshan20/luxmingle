<?php
session_start();
include 'connection.php'; // Ensure this connects to your database

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php'); // Redirect to login if not logged in
    exit;
}

$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session on login

// Fetch user details from the database
$sql = "SELECT * FROM `users` WHERE `id` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if user exists
if (!$user) {
    echo "User not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="shortcut icon" href="resources/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/account.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .account-container {
            margin: 50px auto;
            max-width: 600px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .account-details {
            padding: 10px 0;
        }

        .account-details label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .account-details p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .btn-edit {
            display: block;
            width: 100%;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            text-align: center;
            font-size: 16px;
            border-radius: 5px;
        }

        .btn-edit:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <div class="container account-container">

        <?php
        include 'header.php';
        ?>

        <h2>Your Account</h2>
        <div class="account-details">
            <label>Name:</label>
            <p><?php echo htmlspecialchars($user['username']); ?></p>
        </div>
        <div class="account-details">
            <label>Email:</label>
            <p><?php echo htmlspecialchars($user['email']); ?></p>
        </div>
        <div class="account-details">
            <label>Phone Number:</label>
            <p><?php echo htmlspecialchars($user['phone_number']); ?></p>
        </div>
        <div class="account-details">
            <label>Home Address:</label>
            <p><?php echo nl2br(htmlspecialchars($user['home_address'])); ?></p>
        </div>
        <a href="edit_account.php" class="btn-edit">Edit Account</a>

        <?php
        include 'footer.php';
        ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>