<?php
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_signin.php');
    exit;
}

include 'connection.php'; // Include your database connection

// Fetch all users from the database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Block user
if (isset($_POST['block_user'])) {
    $user_id = $_POST['user_id'];
    $reason = $_POST['block_reason'];

    // Insert into blocked_users table
    $block_query = "INSERT INTO blocked_users (user_id, reason, blocked_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($block_query);
    $stmt->bind_param("is", $user_id, $reason);
    if ($stmt->execute()) {
        // Update user status
        $update_status = "UPDATE users SET status = 'blocked' WHERE id = ?";
        $stmt_update = $conn->prepare($update_status);
        $stmt_update->bind_param("i", $user_id);
        $stmt_update->execute();
    }
}

// Delete user
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    // Delete from users table
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

// Send email to user (This is a simplified mail function. Configure mail properly on your server)
if (isset($_POST['send_email'])) {
    $user_email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    // Use mail function to send email
    mail($user_email, $subject, $message);
}

// Fetch orders of each user
function fetchOrders($conn, $user_id) {
    $order_query = "SELECT * FROM orders WHERE user_id = ?";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 15px;
            display: block;
            color: white;
            text-decoration: none;
            font-size: 18px;
        }
        .sidebar a:hover {
            background-color: #575d63;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .btn-custom {
            margin-right: 5px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<?php include 'admin_sidebar.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <h2>Manage Users</h2>

    <!-- User Table -->
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone_number']; ?></td>
                    <td><?php echo $row['home_address']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <!-- Block User -->
                        <button class="btn btn-warning btn-custom" data-toggle="modal" data-target="#blockUserModal<?php echo $row['id']; ?>">Block</button>

                        <!-- Delete User -->
                        <form method="POST" action="manage_users.php" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                            <button class="btn btn-danger btn-custom" type="submit" name="delete_user">Delete</button>
                        </form>

                        <!-- View Orders -->
                        <button class="btn btn-primary btn-custom" data-toggle="modal" data-target="#viewOrdersModal<?php echo $row['id']; ?>">View Orders</button>

                        <!-- Send Email -->
                        <button class="btn btn-info btn-custom" data-toggle="modal" data-target="#sendEmailModal<?php echo $row['id']; ?>">Send Email</button>
                    </td>
                </tr>

                <!-- Block User Modal -->
                <div class="modal fade" id="blockUserModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="blockUserModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="blockUserModalLabel">Block User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="manage_users.php">
                                <div class="modal-body">
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                    <div class="form-group">
                                        <label for="block_reason">Reason for Blocking:</label>
                                        <textarea name="block_reason" class="form-control" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-warning" name="block_user">Block User</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- View Orders Modal -->
                <div class="modal fade" id="viewOrdersModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="viewOrdersModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewOrdersModalLabel">Orders for <?php echo $row['username']; ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <ul class="list-group">
                                    <?php
                                    $orders = fetchOrders($conn, $row['id']);
                                    if ($orders->num_rows > 0) {
                                        while ($order = $orders->fetch_assoc()) {
                                            echo "<li class='list-group-item'>Order ID: " . $order['order_id'] . " | Quantity: " . $order['quantity'] . " | Date: " . $order['created_at'] . "</li>";
                                        }
                                    } else {
                                        echo "<li class='list-group-item'>No orders found</li>";
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Send Email Modal -->
                <div class="modal fade" id="sendEmailModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="sendEmailModalLabel">Send Email to <?php echo $row['email']; ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="manage_users.php">
                                <div class="modal-body">
                                    <input type="hidden" name="email" value="<?php echo $row['email']; ?>">
                                    <div class="form-group">
                                        <label for="subject">Subject:</label>
                                        <input type="text" name="subject" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="message">Message:</label>
                                        <textarea name="message" class="form-control" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-info" name="send_email">Send Email</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
