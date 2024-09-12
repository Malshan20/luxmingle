<?php
session_start();
include 'connection.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_signin.php');
    exit;
}

// Handle product submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    
    // Insert product details into database
    $insert_product_query = "INSERT INTO products (name, description, price, quantity, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insert_product_query);
    $stmt->bind_param('ssdi', $name, $description, $price, $quantity);

    if ($stmt->execute()) {
        $product_id = $stmt->insert_id;
        
        // Handle multiple image uploads
        $image_count = count($_FILES['images']['name']);
        $upload_directory = "uploads/";

        for ($i = 0; $i < $image_count; $i++) {
            $image_name = $_FILES['images']['name'][$i];
            $image_tmp_name = $_FILES['images']['tmp_name'][$i];
            $target_file = $upload_directory . basename($image_name);

            if (move_uploaded_file($image_tmp_name, $target_file)) {
                // Insert image details into the `product_images` table
                $insert_image_query = "INSERT INTO product_images (product_id, image_path) VALUES (?, ?)";
                $image_stmt = $conn->prepare($insert_image_query);
                $image_stmt->bind_param('is', $product_id, $target_file);
                $image_stmt->execute();
            }
        }

        echo "Product added successfully!";
    } else {
        echo "Error adding product: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Multiple Products</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { margin-top: 50px; }
        .form-group img { max-width: 100px; max-height: 100px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Multiple Products</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>

            <div class="form-group">
                <label for="images">Product Images:</label>
                <input type="file" class="form-control-file" id="images" name="images[]" multiple required>
            </div>

            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
