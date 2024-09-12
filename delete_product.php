<?php
session_start();
include 'connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_signin.php');
    exit;
}

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Delete product query
    $delete_product_query = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($delete_product_query);
    $stmt->bind_param('i', $product_id);

    if ($stmt->execute()) {
        header('Location: admin_dashboard.php?message=Product deleted successfully');
    } else {
        echo "Error deleting product: " . $conn->error;
    }
} else {
    header('Location: admin_dashboard.php?message=Invalid product ID');
}
?>
