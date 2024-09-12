<?php
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_signin.php');
    exit;
}

include 'connection.php'; // Database connection file

// Fetch products from the database
$products_query = "SELECT * FROM products";
$products_result = $conn->query($products_query);

// Add a new product
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    
    // Image upload handling
    $image = $_FILES['image']['name'];
    $target_dir = "images/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    
    // Insert into database
    $insert_query = "INSERT INTO products (name, description, price, quantity, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssdis", $name, $description, $price, $quantity, $target_file);
    $stmt->execute();
}

// Delete a product
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];

    // Delete from the database
    $delete_query = "DELETE FROM `products` WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
}

// Update product quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Update quantity
    $update_query = "UPDATE products SET quantity = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $quantity, $product_id);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin Dashboard</title>
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
        .product-image {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<?php include 'admin_sidebar.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <h2>Manage Products</h2>

    <!-- Add New Product Form -->
    <form method="POST" action="manage_products.php" enctype="multipart/form-data">
        <h4>Add New Product</h4>
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" name="description" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price ($)</label>
            <input type="number" step="0.01" class="form-control" name="price" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" class="form-control" name="quantity" required>
        </div>
        <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" class="form-control-file" name="image" required multiple>
        </div>
        <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
    </form>

    <hr>

    <!-- Product Table -->
    <h4>Product List</h4>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price ($)</th>
                <th>Quantity</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $products_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><img src="images/<?php echo $row['image']; ?>" alt="Product Image" class="product-image"></td>
                    <td>
                        <!-- Delete Product -->
                        <form method="POST" action="manage_products.php" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <button class="btn btn-danger btn-custom" type="submit" name="delete_product">Delete</button>
                        </form>

                        <!-- Update Quantity -->
                        <button class="btn btn-info btn-custom" data-toggle="modal" data-target="#updateQuantityModal<?php echo $row['id']; ?>">Update Quantity</button>
                    </td>
                </tr>

                <!-- Update Quantity Modal -->
                <div class="modal fade" id="updateQuantityModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="updateQuantityModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateQuantityModalLabel">Update Quantity for <?php echo $row['name']; ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="manage_products.php">
                                <div class="modal-body">
                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                    <div class="form-group">
                                        <label for="quantity">New Quantity</label>
                                        <input type="number" class="form-control" name="quantity" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-info" name="update_quantity">Update Quantity</button>
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
