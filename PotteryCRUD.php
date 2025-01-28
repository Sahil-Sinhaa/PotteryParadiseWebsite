<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: PotteryAdminLogin.php");
    exit();
}


// Database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=cs234project", "root", "tiger");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

//  form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add item
    if (isset($_POST['add_item'])) {
        $name = $_POST['item_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock_quantity = $_POST['stock_quantity'];
        $image_path = $_POST['image_url']; // proper file upload later

        $stmt = $pdo->prepare("INSERT INTO potteryitems (item_name, description , price, stock_quantity, image_url) VALUES (?, ?, ?, ?, ?)");

        $stmt->execute([$name, $description , $price, $stock_quantity, $image_path]);
        $success_message = "Item added successfully!";
    }

    // Modify item
    if (isset($_POST['modify_item'])) {
        $id = $_POST['item_id'];
        $name = $_POST['item_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock_quantity = $_POST['stock_quantity'];
        $image_path = $_POST['image_url']; // Can make file uploader ?

        $stmt = $pdo->prepare("UPDATE potteryitems SET item_name = ?, description = ?, price = ?,stock_quantity = ? , image_url = ? WHERE item_id = ?");
        $stmt->execute([$name, $description, $price, $stock_quantity, $image_path, $id]);
        $success_message = "Item updated successfully!";
    }

    // Delete item
    if (isset($_POST['delete_item'])) {
        $id = $_POST['item_id'];

        $stmt = $pdo->prepare("DELETE FROM potteryitems WHERE item_id = ?");
        $stmt->execute([$id]);
        $success_message = "Item deleted successfully!";
    }
}

// Fetch all shop items + bookings
$stmt = $pdo->query("SELECT * FROM potteryitems");
$potteryitems_items = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Control - Shop Management</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        .form-section {
            margin-bottom: 20px;
        }
        .success-message {
            color: green;
        }
    </style>
</head>
<body class="w3-light-grey">
    <div class="w3-container w3-blue">
        <h1>Admin Control - Shop Management</h1>
        <a href="PotteryLogout.php" class="w3-button w3-red w3-right">Logout</a>
    </div>

    <div class="w3-container">
        <?php if (isset($success_message)): ?>
            <p class="success-message"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>
            <!-- ADDING ITEMS -->
        <div class="form-section w3-card w3-white w3-padding">
            <h2>Add Item</h2>
            <form method="POST">
                <label for="item_name">Name:</label>
                <input class="w3-input w3-border" type="text" name="item_name" required>
                
                <label for="description">Description:</label>
                <textarea class="w3-input w3-border" name="description" required></textarea>


                <label for="price">Price:</label>
                <input class="w3-input w3-border" type="number" step="0.01" name="price" required>

                <label for="stock_quantity">Quantity:</label>
                <input class="w3-input w3-border" type="number" name="stock_quantity" required>

                <label for="image_url">Image Path:</label>
                <input class="w3-input w3-border" type="text" name="image_url" required>

                <button class="w3-button w3-green w3-margin-top" type="submit" name="add_item">Add Item</button>
            </form>
        </div>
<!-- MODIFY ITEM -->
        <div class="form-section w3-card w3-white w3-padding">
            <h2>Modify Item</h2>
            <form method="POST">
                <label for="item_id">Item ID:</label>
                <input class="w3-input w3-border" type="number" name="item_id" required>

                <label for="item_name">Name:</label>
                <input class="w3-input w3-border" type="text" name="item_name" required>
                
                <label for="description">Description:</label>
                <textarea class="w3-input w3-border" name="description" required></textarea>

                <label for="price">Price:</label>
                <input class="w3-input w3-border" type="number" step="0.01" name="price" required>

                <label for="stock_quantity">Stock Quantity:</label>
                <input class="w3-input w3-border" type="number" name="stock_quantity" required>

                <label for="image_url">Image Path:</label>
                <input class="w3-input w3-border" type="text" name="image_url" required>

                <button class="w3-button w3-yellow w3-margin-top" type="submit" name="modify_item">Modify Item</button>
            </form>
        </div>
<!-- DELETE ITEM -->
        <div class="form-section w3-card w3-white w3-padding">
            <h2>Delete Item</h2>
            <form method="POST">
                <label for="item_id">Item ID:</label>
                <input class="w3-input w3-border" type="number" name="item_id" required>

                <button class="w3-button w3-red w3-margin-top" type="submit" name="delete_item">Delete Item</button>
            </form>
        </div>

        <div class="form-section w3-card w3-white w3-padding">
            <h2>Shop Items</h2>
            <table class="w3-table w3-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Image Path</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($potteryitems_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['item_id']) ?></td>
                            <td><?= htmlspecialchars($item['item_name']) ?></td>
                            <td><?= htmlspecialchars($item['description'])?></td>
                            <td><?= htmlspecialchars($item['price']) ?></td>
                            <td><?= htmlspecialchars($item['stock_quantity']) ?></td>
                            <td><?= htmlspecialchars($item['image_url']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
