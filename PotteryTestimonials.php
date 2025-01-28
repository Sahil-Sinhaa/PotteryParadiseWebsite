<?php
session_start(); // login check section

if (!isset($_SESSION['user_id'])) {
    // Redirect
    header("Location: PotteryLogin.php");
    exit();
}
?>

<?php
session_start();

// Connect to database
$pdo = new PDO("mysql:host=localhost;dbname=cs234project", "root", "tiger");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data 
    $username = !empty($_POST['username']) ? $_POST['username'] : 'Anonymous';
    $rating = !empty($_POST['rating']) ? $_POST['rating'] : null;
    $review = !empty($_POST['review']) ? $_POST['review'] : null;

    try {
        // Insert data into Testimonials
        $stmt = $pdo->prepare("INSERT INTO testimonials (user_id, username, rating, review) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $username, $rating, $review]);

        echo "<p>Thank you for submitting your review!</p>";
    } catch (PDOException $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
}

// Read and display SQL table data
$sql = "SELECT username, rating, review FROM testimonials";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        .header {
            text-align: center;
            padding: 20px;
            background-color: #8e8d8a;
            color: white;
        }
        .testimonial-form {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background: #f4f4f4;
            border-radius: 8px;
        }
        .testimonial {
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: lightgoldenrodyellow;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 80%;
        }
        body {
            background-image: url(https://cooltools.us/cdn/shop/files/TTL-1004a.jpg?v=1721057183&width=990);
            background-size: cover;
            background-position: center; 
            background-repeat: no-repeat;
            }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reviews</h1>
    </div>

    <div class="testimonial-form">
        <h2>Leave a Review</h2>
        <form method="POST">
            <input class="w3-input w3-border" type="text" name="username" placeholder="Your Name">
            <select class="w3-select w3-border" name="rating">
                <option value="" disabled selected>Rating</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <textarea class="w3-input w3-border" name="review" placeholder="Write your review here..."></textarea>
            <button class="w3-button w3-green w3-margin-top" type="submit">Submit</button>
        </form>
    </div>

    <div class="w3-container">
        <?php
        foreach ($pdo->query($sql) as $row) {
            echo '<div class="testimonial w3-card">';
            echo '<h3>' . htmlspecialchars($row['username']) . '</h3>';
            if (!is_null($row['rating'])) {
                echo '<p>Rating: ' . htmlspecialchars($row['rating']) . '</p>';
            }
            if (!is_null($row['review'])) {
                echo '<p>' . htmlspecialchars($row['review']) . '</p>';
            }
            echo '</div>';
        }
        ?>
    </div>
    <div class="go-back-button w3-container">
    <form method="GET" action="PotteryDashboard.php">
        <button class="w3-button w3-blue w3-left w3-margin" type="submit">Go Back</button>
    </form>
</div>

</body>
</html>
