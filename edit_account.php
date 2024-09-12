<?php
session_start();
include 'connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch current user data
$query = "SELECT * FROM `users` WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Update user information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $home_address = $conn->real_escape_string($_POST['home_address']);

    $update_query = "UPDATE `users` SET `username` = ?, `email` = ?, `phone_number` = ?, `home_address` = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('ssssi', $name, $email, $phone_number, $home_address, $user_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Account information updated successfully.');</script>";
        header('Location: account.php');
    } else {
        echo "<script>alert('Failed to update account information.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">

    <?php
    include 'header.php';
    ?>

        <h2>Edit Account Information</h2>
        <form method="POST" action="edit_account.php">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
            </div>
            <div class="form-group">
                <label for="home_address">Home Address:</label>
                <textarea class="form-control" id="home_address" name="home_address" required><?php echo htmlspecialchars($user['home_address']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Account</button>
        </form>

        <?php
        include 'footer.php';
        ?>

    </div>

    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
