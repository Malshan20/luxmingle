<?php
session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: signin.php');
    exit();
}

$username = $_SESSION['username'];

// Fetch trending products with pagination
$limit = 4; // Products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$totalQuery = "SELECT COUNT(*) as total FROM `products`";
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalProducts = $totalRow['total'];
$totalPages = ceil($totalProducts / $limit);

$query = "SELECT * FROM `products` ORDER BY popularity DESC LIMIT $start, $limit";
$products = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="shortcut icon" href="resources/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="resources/favicon.png" type="image/x-icon">
    
</head>

<body>
    <div class="containers">
        
<?php
include 'header.php';
?>
        

        <!-- Slider Section -->
        <div class="slider">
            <img src="images/slider1.jpg" alt="Slide 1">
            <img src="images/slider2.jpg" alt="Slide 2">
            <img src="images/slider3.jpg" alt="Slide 3">
        </div>

        <!-- Partners Section -->
        <div class="partners">
            <h2>Our Partners</h2>
            <img src="images/zara.png" alt="Zara">
            <img src="images/adidas.png" alt="Adidas">
            <img src="images/gucci.png" alt="Gucci">
            <img src="images/prada.png" alt="Prada">
        </div>

        <!-- Trending Products Section -->
        <div class="products">
            <h2>Trending Products</h2>
            <div class="product-list">
                <?php if ($products->num_rows > 0): ?>
                    <?php while ($product = $products->fetch_assoc()): ?>
                        <div class="product">
                            <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                            <p><strong>$<?php echo htmlspecialchars($product['price']); ?></strong></p>
                            <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No products found.</p>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="home.php?page=1">1</a>
                    <a href="home.php?page=<?php echo $page - 1; ?>">Previous</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="home.php?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <a href="home.php?page=<?php echo $page + 1; ?>">Next</a>
                    <a href="home.php?page=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Banner Section -->
        <div class="banner">
            <h2>Limited Time Offer!</h2>
            <p>Get 50% off on every spend over $50!</p>
        </div>
    

   
    
</body>

<?php

include 'footer.php';

?>

</div>

<script src="js/script.js"></script>
</html>
