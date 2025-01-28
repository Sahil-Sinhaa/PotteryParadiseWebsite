<?php
session_start(); // login check section

if (!isset($_SESSION['user_id'])) {
    // Redirect
    header("Location: PotteryLogin.php");
    exit();
}
?>


<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pottery Business Dashboard</title>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <style>
        /* Background Image */
        body {
            background-image: url('https://media.istockphoto.com/id/847101680/photo/making-clay-jug.jpg?s=612x612&w=0&k=20&c=fPqJvfhf5zRee5XsQLljqVVOiM41UgGcdIFRGWCO5dM=');
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
        }
        .contact-info {
            display: none;
            margin-top: 10px;
            background-color: white;
            color : black;
            padding: 10px;
            border-radius: 5px;
        }

        /* Center */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            text-align: center;
            color: white;
        }

        /* (2x2 grid) */
        .button-container {
            display: grid;
            grid-template-columns: repeat(2, 300px); 
            grid-gap: 20px; /* Space between buttons */
        }

        .w3-button {
            background-color: #4E3629;
            width: 100%;
            padding: 20px;
            font-size: 18px;
            text-align: center;
        }
        .w3-button:hover{
            background-color: #6A4E3D;
        }

        .contact-button {
            margin-top: 100px;
            margin-bottom: 50px;
        }

    </style>
</head>

    <div class="container">
        <div>
            <h2>Pottery Paradise</h2>
            <div class="button-container">
                <a href="PotteryBookings.php" class="w3-button">Classes</a>
                <a href="PotteryShop.php" class="w3-button">Shop</a>
                <a href="PotteryAbout.php" class="w3-button">About</a>
                <a href="PotteryTestimonials.php" class="w3-button">Testimonials</a>
            </div>
            <button onclick="toggleContactInfo()" class="w3-button w3-large contact-button">Contact</button>

             <!-- Contact Info -->
           <div id="contactInfo" class="contact-info w3-card-4">
               <h4>Contact Information</h4>
                <p>Phone: +1 234 5678</p>
             <p>Available Hours: 9 AM - 5 PM, Mon-Fri</p>
            </div>
        </div>
    </div>
    <div class="logout-button w3-container">
        <form method="POST" action="PotteryLogout.php">
            <button class="w3-button w3-red w3-center" type="submit">Logout</button>
        </form>
    </div>
    <script>
        // Toggle contact
        function toggleContactInfo() {
            var contactInfo = document.getElementById("contactInfo");
            if (contactInfo.style.display === "none" || contactInfo.style.display === "") {
                contactInfo.style.display = "block";
            } else {
                contactInfo.style.display = "none";
            }
        }
    </script>
</body>
</html>
