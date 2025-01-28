<?php
session_start(); // login check section

if (!isset($_SESSION['user_id'])) {
    // Redirect
    header("Location: PotteryLogin.php");
    exit();
}
?>

<?php
// Database Connection
$pdo = new PDO(dsn: "mysql:host=localhost;dbname=cs234project", username: "root", password: "tiger");
$pdo->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pottery Shop</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        .product-card {
            display: flex;
            background: #ffffff;
            margin: 20px auto;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 80%;
        }
        .product-card img {
            max-width: 150px;
            height: auto;
            margin-right: 20px;
        }
        .product-info h3 {
            margin: 0 0 10px;
        }
        .header {
            text-align: center;
            padding: 20px;
            background-color: #F5F5DC;
            color: black;
        }
        body {
            background-image: url(https://imgs.search.brave.com/2obSHzDeQ6hIStXwdYhtzh71DToBelkLQ7BZdRZCtBM/rs:fit:500:0:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/cHJlbWl1bS1waG90/by9ncmFkaWVudC1i/YWNrZ3JvdW5kLWJs/dXJyeS1iYWNrZ3Jv/dW5kcGluay1wYXN0/ZWwtZ3JhZGllbnQt/d2FsbHBhcGVyYWJz/dHJhY3QtYmFja2dy/b3VuZC1ibHVyXzYx/MzAwMS02ODczLmpw/Zz9zZW10PWFpc19o/eWJyaWQ);
            background-size: cover;
            background-position: center; 
            background-repeat: no-repeat;
            }
    </style>
</head>
<body>
    <div class="header">
        <h1>Paradise Shop</h1>
        <p>Order via contacting this number: <b>+1 234 567 890</b></p>
    </div>

    <div class="w3-container">
        <?php
        // Read from Table Shop
        $sql = "SELECT item_name, description, price, stock_quantity, image_url FROM potteryitems";
        $stmt = $pdo->query($sql); // PDO query

        if ($stmt->rowCount() > 0) {
            // Get results
            while ($row = $stmt->fetch(mode: PDO::FETCH_ASSOC)) {
                echo '<div class="product-card w3-card">';
                echo '<img src="' . htmlspecialchars(string: $row['image_url']) . '" alt="Product Image">';
                echo '<div class="product-info">';
                echo '<h3>' . htmlspecialchars(string: $row['item_name']) . '</h3>';
                echo '<p><b>Price:</b> $' . htmlspecialchars(string: $row['price']) . '</p>';
                echo '<p><b>Stock Available:</b> ' . htmlspecialchars(string: $row['stock_quantity']) . '</p>';
                echo '<p>' . htmlspecialchars(string: $row['description']) . '</p>';
                echo '</div></div>';
            }
        } else {
            echo '<p>No items available in the shop right now.</p>';
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

<?php $pdo = null; ?> 
