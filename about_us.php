<?php
session_start();
// Include database connection if needed to fetch dynamic data
include 'connection.php';

// Example query to fetch about us content from database (optional)
$query = "SELECT * FROM `about_info` WHERE id = 1";
$result = $conn->query($query);
$about = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="resources/favicon.png" type="image/x-icon">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        
    </style>
</head>

<body>

    <div class="container">

    <?php
    include 'header.php';
    ?>


        <!-- About Us Section -->
        <div class="about-section">
            <h1>About Us</h1>
        </div>

        <!-- Content Section -->
        <div class="content">
            <h2>Who We Are</h2>
            <p>
                We are a team of passionate individuals committed to delivering high-quality products and services. Our mission is to innovate and inspire, creating memorable experiences for our customers. With years of experience in the industry, we believe in the power of creativity, teamwork, and integrity.
            </p>
        </div>

        <!-- Team Section -->
        <div class="content">
            <h2>Meet Our Team</h2>
            <div class="team-section">
                <!-- Team Member 1 -->
                <div class="team-member">
                    <img src="images/team1.jpg" alt="Team Member 1">
                    <h3>John Doe</h3>
                    <p>Founder & CEO</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="team-member">
                    <img src="images/team2.jpg" alt="Team Member 2">
                    <h3>Jane Smith</h3>
                    <p>Creative Director</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>

                <!-- Team Member 3 -->
                <div class="team-member">
                    <img src="images/team3.jpg" alt="Team Member 3">
                    <h3>Mary Johnson</h3>
                    <p>Marketing Manager</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <?php
        include 'footer.php';
        ?>

    </div>

    <!-- JavaScript to Add Interactivity if needed -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Add interactivity if needed
        });
    </script>

</body>

</html>