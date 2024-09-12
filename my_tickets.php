<?php
session_start();
include 'connection.php';

// Fetch messages from the contacts table
$sql = "SELECT * FROM contacts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="resources/favicon.png" type="image/x-icon">
    <style>
        .container {
            margin-top: 50px;
        }

        .ticket {
            padding: 20px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .ticket h5 {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">

    <?php
    include 'header.php';
    ?>

        <h2>My Tickets</h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="ticket">
                    <h5><?php echo htmlspecialchars($row['subject']); ?></h5>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                    <p><?php echo htmlspecialchars($row['message']); ?></p>
                    <p><em>Submitted on <?php echo $row['created_at']; ?></em></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No messages yet.</p>
        <?php endif; ?>

        <?php
        include 'footer.php';
        ?>

    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>