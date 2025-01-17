<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection
    $pdo = new PDO("mysql:host=localhost;dbname=cs234project", "root", "tiger");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the user exists and is an admin
    $sql = "SELECT * FROM Users WHERE username = ? AND role = 'admin'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // If login is successful
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect to Admin CRUD Page
        header("Location: PotteryCRUD.php");
        exit();
    } else {
        $error_message = "Invalid username or password, or you do not have admin access.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
<div class="w3-container w3-light-grey w3-padding">
    <h2>Admin Login</h2>
    <?php if (!empty($error_message)) : ?>
        <div class="w3-panel w3-red">
            <p><?php echo htmlspecialchars($error_message); ?></p>
        </div>
    <?php endif; ?>
    <form action="PotteryAdminLogin.php" method="POST">
        <label for="username">Username:</label>
        <input class="w3-input w3-border" type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input class="w3-input w3-border" type="password" id="password" name="password" required>

        <button class="w3-button w3-green w3-margin-top" type="submit">Login</button>
    </form>
    <p><a href="PotteryLogin.php">Back to Customer Login</a></p>
</div>
</body>
</html>
