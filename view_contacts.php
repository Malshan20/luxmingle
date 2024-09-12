<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_signin.php");
    exit;
}

include 'connection.php'; // Database connection file

// Fetch contacts from the database
$contacts_query = "SELECT * FROM contacts";
$contacts_result = $conn->query($contacts_query);

// Fetch chat messages for a specific contact
if (isset($_GET['contact_id'])) {
    $contact_id = intval($_GET['contact_id']);

    // Get the user's contact information
    $contact_query = "SELECT * FROM contacts WHERE id = ?";
    $stmt = $conn->prepare($contact_query);
    $stmt->bind_param("i", $contact_id);
    $stmt->execute();
    $contact = $stmt->get_result()->fetch_assoc();

    // Fetch replies for this contact
    $replies_query = "SELECT * FROM contact_replies WHERE contact_id = ?";
    $stmt = $conn->prepare($replies_query);
    $stmt->bind_param("i", $contact_id);
    $stmt->execute();
    $replies_result = $stmt->get_result();
}

// Reply to a message
if (isset($_POST['reply'])) {
    $admin_reply = $_POST['admin_reply'];
    $contact_id = $_POST['contact_id'];

    // Insert reply into contact_replies table
    $insert_reply_query = "INSERT INTO contact_replies (contact_id, admin_id, reply_message, admin_reply) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_reply_query);
    $stmt->bind_param("iiss", $contact_id, $admin_id, $reply_message, $admin_reply);

    $stmt->execute();

    // Redirect to the same contact page
    header("Location: view_contacts.php?contact_id=" . $contact_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Contacts</title>
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

        .message-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .message-reply {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="view_contacts.php" class="active">View Contacts</a>
        <a href="manage_products.php">Manage Products</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Contact Messages</h2>

        <!-- List of Contacts -->
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $contacts_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['subject']); ?></td>
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <a href="view_contacts.php?contact_id=<?php echo $row['id']; ?>" class="btn btn-info">View & Reply</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- If a contact is selected, show the chat box -->
        <?php if (isset($contact)) { ?>
            <h4>Messages with <?php echo htmlspecialchars($contact['name']); ?></h4>
            <div class="message-box">
                <!-- Show user's original message -->
                <p><strong>User:</strong> <?php echo htmlspecialchars($contact['message']); ?></p>

                <!-- Display all replies -->
                <?php while ($reply = $replies_result->fetch_assoc()) { ?>
                    <div class="message-reply">
                        <p><strong>Admin:</strong> <?php echo htmlspecialchars($reply['admin_reply']); ?></p>
                    </div>
                <?php } ?>

                <!-- Reply form -->
                <form method="POST" action="view_contacts.php">
                    <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                    <div class="form-group">
                        <label for="admin_reply">Your Reply</label>
                        <textarea class="form-control" name="admin_reply" rows="4" required></textarea>
                    </div>
                    <button type="submit" name="reply" class="btn btn-primary">Send Reply</button>
                </form>
            </div>
        <?php } ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>