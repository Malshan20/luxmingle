<?php
session_start();
include 'connection.php';

// Pagination setup
$limit = 8; // Number of products per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchCondition = $search ? "WHERE name LIKE '%$search%' OR description LIKE '%$search%'" : '';

// Fetch products from the database
$query = "SELECT * FROM products $searchCondition ORDER BY popularity DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($query);

// Fetch total products for pagination
$totalQuery = "SELECT COUNT(*) AS total FROM products $searchCondition";
$totalResult = $conn->query($totalQuery);
$totalProducts = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalProducts / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="resources/favicon.png" type="image/x-icon">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2.5rem;
            color: #333;
        }

        .search-form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-form input[type="text"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px 0 0 5px;
        }

        .search-form button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }

        .product-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .product-info {
            padding: 15px;
            text-align: center;
        }

        .product-info h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .product-info p {
            color: #666;
            margin-bottom: 10px;
        }

        .product-info .price {
            font-size: 1.4rem;
            color: #007bff;
            font-weight: bold;
        }

        .product-info .quantity {
            font-size: 1rem;
            color: #333;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .pagination a {
            color: #007bff;
            padding: 10px 15px;
            margin: 0 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
        }

        .pagination a.active {
            background-color: #007bff;
            color: #fff;
        }

        .pagination a:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">

    <?php
    include 'header.php';
    ?>
        <h1 style="font-family: 'Helios Extended', sans-serif; font-style: italic">LUX OUTFITS</h1>
        
        <form class="search-form" action="" method="GET">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>

        <div class="products">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                        <div class="product-info">
                            <h3><?php echo $row['name']; ?></h3>
                            <p><?php echo $row['description']; ?></p>
                            <p class="price">$<?php echo $row['price']; ?></p>
                            <p class="quantity">Available: <?php echo $row['quantity']; ?></p>
                            <a href="product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="collections.php?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="collections.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="collections.php?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
            <?php endif; ?>
        </div>

        <?php
        include 'footer.php';
        ?>
    </div>
</body>
</html>
