<?php
session_start(); // login check section

if (!isset($_SESSION['user_id'])) {
    // Redirect
    header("Location: PotteryLogin.php");
    exit();
}
?>
<div class="go-back-button w3-container">
    <form method="GET" action="PotteryDashboard.php">
        <button class="w3-button w3-blue w3-left w3-margin" type="submit">Go Back</button>
    </form>
</div>
