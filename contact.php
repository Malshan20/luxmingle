<?php
// Start session and include database connection
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$name = $email = $subject = $message = "";
$nameErr = $emailErr = $subjectErr = $messageErr = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $is_valid = true;

    // Validate form inputs
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
        $is_valid = false;
    } else {
        $name = htmlspecialchars($_POST["name"]);
    }

    if (empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Valid email is required";
        $is_valid = false;
    } else {
        $email = htmlspecialchars($_POST["email"]);
    }

    if (empty($_POST["subject"])) {
        $subjectErr = "Subject is required";
        $is_valid = false;
    } else {
        $subject = htmlspecialchars($_POST["subject"]);
    }

    if (empty($_POST["message"])) {
        $messageErr = "Message is required";
        $is_valid = false;
    } else {
        $message = htmlspecialchars($_POST["message"]);
    }

    // If form is valid, insert into the database
    if ($is_valid) {
        $sql = "INSERT INTO `contacts` (`name`, `email`, `subject`, `message`) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        if ($stmt->execute()) {
            echo "<script>alert('Your message has been submitted!');</script>";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="shortcut icon" href="resources/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container {
            margin-top: 50px;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        include 'header.php';
        ?>
        <h2>Contact Us</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
                <span class="error"><?php echo $nameErr; ?></span>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                <span class="error"><?php echo $emailErr; ?></span>
            </div>

            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" class="form-control" id="subject" name="subject" value="<?php echo $subject; ?>">
                <span class="error"><?php echo $subjectErr; ?></span>
            </div>

            <div class="form-group">
                <label for="message">Message:</label>
                <textarea class="form-control" id="message" name="message" rows="5"><?php echo $message; ?></textarea>
                <span class="error"><?php echo $messageErr; ?></span>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <br>
        <a href="my_tickets.php" class="btn btn-primary">My Tickets</a>

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
