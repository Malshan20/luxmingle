<?php
session_start();
include 'connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please login to add items to cart.";
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

// Fetch product details
$query = "SELECT * FROM `products` WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit;
}

$total_price = $product['price'] * $quantity;

// Insert into cart_items table with user_id
$insert_cart_query = "INSERT INTO `cart_items` (`user_id`, `product_id`, `quantity`, `total_price`, `added_at`) VALUES (?, ?, ?, ?, NOW())";
$cart_stmt = $conn->prepare($insert_cart_query);
$cart_stmt->bind_param("iiid", $user_id, $product_id, $quantity, $total_price);
if ($cart_stmt->execute()) {
    echo "Product added to cart!";
} else {
    echo "Error: " . $conn->error;
}
?>
