<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // Redirect to the dashboard
    header("Location: PotteryDashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection
    $pdo = new PDO("mysql:host=localhost;dbname=Cs234Project", "root", "tiger");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the user exists
    $sql = "SELECT * FROM Users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // If login is successful
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Useful for role-based access control

        // Redirect to dashboard
        header("Location: PotteryDashboard.php");
        exit();
    } else {
        echo "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
         .admin-container {
            position: relative;
            display: inline-block; /* Keep the hover area contained */
        }

        .reveal-admin {
            width: 50px;
            height: 20px;
            background-color: transparent; /* Invisible area */
            cursor: pointer;
        }

        .admin-button {
            display: none;
            position: absolute;
            top: 25px; /* Adjust as needed */
            left: 0; /* Adjust as needed */
        }

        .reveal-admin:hover + .admin-button {
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="w3-container w3-light-grey w3-padding">
    <h2>Login</h2>

    <form action="PotteryLogin.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>
    <p>Don't have an account? <a href="PotteryRegistration.php">Create one!</a></p>
    
    <div class="admin-container">&nbsp;
        <div class =reveal-admin></div>
    
       <a href="PotteryAdminRegistration.php" class="w3-button w3-blue admin-button">Admin Login</a>
    </div>
</div>
</body>
</html>
