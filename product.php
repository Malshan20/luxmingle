<?php
session_start();
include 'connection.php'; // Include your database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}


// Fetch product ID from the query string
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details from the database
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "Product not found!";
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="resources/favicon.png" type="image/x-icon">
</head>

<body>

    <!-- Header -->


    <!-- Product View -->
    <div class="container product-details">
        <?php include 'header.php'; ?>
        <div class="row">
            <div class="col-md-6">
                <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
            </div>
            <div class="col-md-6">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="<?php echo $product['quantity']; ?>">
                </div>
                <button class="btn btn-primary btn-custom" onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
                <button class="btn btn-success btn-custom" onclick="buyNow(<?php echo $product['id']; ?>)">Buy Now</button>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </div>

    <!-- Footer -->


    <!-- Include Bootstrap and JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>

</body>

</html>