<?php
session_start();
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$total_price = isset($_GET['price']) ? number_format($_GET['price'], 2) : 0.00;
$user_id = $_SESSION['user_id'];

// Fetch order details from the database
$sql = "SELECT ci.*, p.name, p.price, p.image, o.total_amount, o.created_at
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        JOIN orders o ON o.user_id = ci.user_id
        WHERE o.order_id = ? AND ci.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch user information
$user_query = "SELECT * FROM `users` WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param('i', $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

if ($result->num_rows === 0) {
    echo "Order not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
        }

        .thank-you-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .invoice-section {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 5px;
        }

        .invoice-header {
            background-color: #343a40;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }

        .invoice-table {
            margin-top: 20px;
        }

        .total-row {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="thank-you-section">
            <h2>Thank You for Your Purchase, <?php echo htmlspecialchars($user['username']); ?>!</h2>
            <p>Your order has been successfully placed. Below is your invoice summary:</p>
        </div>

        <div class="invoice-section">
            <div class="invoice-header">
                <h3>Invoice #<?php echo htmlspecialchars($order_id); ?></h3>
                <p>Order Date: <?php echo date('F d, Y', strtotime($result->fetch_assoc()['ordered_at'])); ?></p>
            </div>

            <table class="table table-striped invoice-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through the cart items and display them
                    $result->data_seek(0); // Reset result set pointer
                    $grand_total = 0;
                    while ($row = $result->fetch_assoc()) {
                        $line_total = $row['quantity'] * $row['price'];
                        $grand_total += $line_total;
                        ?>
                        <tr>
                            <td><img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" style="width: 50px;"> <?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td>$<?php echo number_format($row['price'], 2); ?></td>
                            <td>$<?php echo number_format($line_total, 2); ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr class="total-row">
                        <td colspan="3" class="text-right">Grand Total</td>
                        <td>$<?php echo number_format($grand_total, 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="home.php" class="btn btn-primary">Continue Shopping</a>
        </div>

        <div class="text-center mt-2">
            <p>Need help? Contact our support team at <a href="mailto:support@yourstore.com">support@yourstore.com</a></p>
        </div>
    </div>

    <!-- Include Bootstrap and JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
