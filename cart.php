<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for the logged-in user
$query = "SELECT ci.*, p.name, p.price, p.image 
          FROM cart_items ci
          JOIN products p ON ci.product_id = p.id
          WHERE ci.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result();

// Calculate total price of all items in the cart
$total_price = 0;
$cart_products = array();
while ($row = $cart_items->fetch_assoc()) {
    $total_price += $row['total_price'];
    $cart_products[] = $row;
}

// Reset the result pointer
$cart_items->data_seek(0);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="resources/favicon.png" type="image/x-icon">
    <script src="https://www.paypal.com/sdk/js?client-id=AXMh5RGo61jXruCVBBnIqJHSRKZ1OYtPCZm5YOpBDeWngJSo1aj6DsbErckJxQIhIkVG3cToEYBUEiS2&currency=USD"></script>
</head>
<body>

<div class="container mt-5">

<?php
include 'header.php';
?>

    <h2>Your Cart</h2>

    <?php if ($cart_items->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Added At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_products as $row): ?>
                    <tr>
                        <td>
                            <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" width="50">
                            <?php echo $row['name']; ?>
                        </td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>$<?php echo number_format($row['price'], 2); ?></td>
                        <td>$<?php echo number_format($row['total_price'], 2); ?></td>
                        <td><?php echo $row['added_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Total: $<?php echo number_format($total_price, 2); ?></h3>

        <!-- PayPal Button Container -->
        <div id="paypal-button-container"></div>

        <script>
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '<?php echo $total_price; ?>'
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        alert('Transaction completed by ' + details.payer.name.given_name);
                        // Call your server to save the transaction
                        return fetch('/paypal-transaction-complete', {
                            method: 'post',
                            headers: {
                                'content-type': 'application/json'
                            },
                            body: JSON.stringify({
                                orderID: data.orderID
                            })
                        });
                    });
                }
            }).render('#paypal-button-container');
        </script>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <?php
    include 'footer.php';
    ?>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
