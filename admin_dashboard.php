<?php
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_signin.php');
    exit;
}
include 'connection.php'; // Include your database connection file

// Query for summary (e.g., number of users, products)
$user_count_query = "SELECT COUNT(*) AS total_users FROM users";
$product_count_query = "SELECT COUNT(*) AS total_products FROM products";

$user_count_result = $conn->query($user_count_query);
$product_count_result = $conn->query($product_count_query);

$total_users = $user_count_result->fetch_assoc()['total_users'];
$total_products = $product_count_result->fetch_assoc()['total_products'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
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
            text-align: left;
            display: block;
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        .sidebar a:hover {
            background-color: #575d63;
        }

        .sidebar a.active {
            background-color: #007bff;
            color: white;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .navbar-custom {
            background-color: #007bff;
            color: white;
        }

        .dashboard-summary {
            display: flex;
            justify-content: space-between;
        }

        .summary-box {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            width: 45%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .summary-box h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .summary-box p {
            font-size: 18px;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <?php include 'admin_sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container-fluid">
                <span class="navbar-brand">Admin Dashboard</span>
            </div>
        </nav>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <h2>Welcome, Admin</h2>
            <div class="dashboard-summary mt-4">
                <div class="summary-box">
                    <h3>Total Users</h3>
                    <p><?php echo $total_users; ?> Users</p>
                </div>

                <div class="summary-box">
                    <h3>Total Products</h3>
                    <p><?php echo $total_products; ?> Products</p>
                </div>
            </div>

            <!-- Additional Content (Manage Users, Products, etc.) -->
            <div class="mt-5">
                <h4>Manage Your Store</h4>
                <p><a href="manage_users.php">Go to Users Management</a> | <a href="manage_products.php">Go to Products Management</a></p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
