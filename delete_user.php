<?php
session_start();
include 'connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_signin.php');
    exit;
}

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // Delete user query
    $delete_user_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_user_query);
    $stmt->bind_param('i', $user_id);

    if ($stmt->execute()) {
        header('Location: admin_dashboard.php?message=User deleted successfully');
    } else {
        echo "Error deleting user: " . $conn->error;
    }
} else {
    header('Location: admin_dashboard.php?message=Invalid user ID');
}
?>
