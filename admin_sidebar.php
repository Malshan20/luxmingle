<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
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
</style>
<body>

    <div class="sidebar">
        <a href="admin_dashboard.php" class="active">Dashboard</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_products.php">Manage Products</a>
        <a href="view_contacts.php">View Contacts</a>
        <a href="admin_logout.php">Logout</a>
    </div>

</body>

</html>