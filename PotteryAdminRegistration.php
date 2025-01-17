<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password with bcrypt
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Database connection
    $pdo = new PDO("mysql:host=localhost;dbname=cs234project", "root", "tiger");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if username already exists
    $check_sql = "SELECT COUNT(*) FROM Users WHERE username = ?";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([$username]);
    $user_exists = $check_stmt->fetchColumn() > 0;

    if ($user_exists) {
        $error_message = "Username already exists. Please choose a different username.";
    } else {
        // Insert new admin user into the database
        $sql = "INSERT INTO Users (username, password, role) VALUES (?, ?, 'admin')";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([$username, $hashed_password]);
            $success_message = "Admin registered successfully!";
        } catch (Exception $e) {
            $error_message = "Error registering admin: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
<div class="w3-container w3-light-grey w3-padding">
    <h2>Admin Registration</h2>
    <?php if (!empty($error_message)) : ?>
        <div class="w3-panel w3-red">
            <p><?php echo htmlspecialchars($error_message); ?></p>
        </div>
    <?php elseif (!empty($success_message)) : ?>
        <div class="w3-panel w3-green">
            <p><?php echo htmlspecialchars($success_message); ?></p>
        </div>
    <?php endif; ?>

    <form action="PotteryAdminRegistration.php" method="POST">
        <label for="username">Username:</label>
        <input class="w3-input w3-border" type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input class="w3-input w3-border" type="password" id="password" name="password" required>

        <button class="w3-button w3-green w3-margin-top" type="submit">Register</button>
    </form>
    <p><a href="PotteryAdminLogin.php">Back to Admin Login</a></p>
</div>
</body>
</html>
