<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}


$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

// Fetch product details from the database
$sql = "SELECT * FROM `products` WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "Product not found!";
    exit;
}

$price = number_format($product['price'], 2); // Format price for PayPal
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.paypal.com/sdk/js?client-id=AXMh5RGo61jXruCVBBnIqJHSRKZ1OYtPCZm5YOpBDeWngJSo1aj6DsbErckJxQIhIkVG3cToEYBUEiS2&currency=USD"></script> 
    <link rel="shortcut icon" href="resources/favicon.png" type="image/x-icon">
    <style>
        .container {
            margin-top: 50px;
        }

        .paypal-button-container {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <?php include 'header.php'; ?>

        <div class="col-md-4">
            <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
        </div>
        <h2>Checkout</h2>
        <p>Product: <?php echo htmlspecialchars($product['name']); ?></p>
        <p>Price: $<?php echo $price; ?></p>

        <!-- PayPal Button Container -->
        <div class="paypal-button-container"></div>


        <!-- Footer -->
        <?php include 'footer.php'; ?>
    </div>
    <script>
        // Render PayPal Button
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo $price; ?>' // Dynamic price of the product
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Transaction completed by ' + details.payer.name.given_name);
                    window.location.href = 'thank_you.php'; // Redirect to thank you page or confirmation
                });
            }
        }).render('.paypal-button-container'); // Display PayPal button in the container
    </script>

    <!-- Include Bootstrap and JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>