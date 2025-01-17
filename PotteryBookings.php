<?php
session_start(); // Login check section

if (!isset($_SESSION['user_id'])) {
    // Redirect if the user is not logged in
    header("Location: PotteryLogin.php");
    exit();
}

// Connect to the database
$pdo = new PDO("mysql:host=localhost;dbname=cs234project", "root", "tiger");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Initialize messages
$success_message = $error_message = "";

// Handle the booking form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_booking'])) {
    $user_id = $_SESSION['user_id']; // Get logged-in user's ID
    $number_of_people = $_POST['number_of_people'];
    $class_type = $_POST['class_type'];
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];

    // Check if the selected time slot is already booked
    $stmt = $pdo->prepare("SELECT is_booked FROM bookings WHERE booking_date = ? AND booking_time = ?");
    $stmt->execute([$booking_date, $booking_time]);
    $is_booked = $stmt->fetchColumn();

    if ($is_booked) {
        $error_message = "This time slot is already booked. Please select a different time.";
    } else {
        // Update the booking information for the selected slot
        $stmt = $pdo->prepare(
            "UPDATE bookings SET user_id = ?, number_of_people = ?, class_type = ?, is_booked = 1 
             WHERE booking_date = ? AND booking_time = ?"
        );
        $stmt->execute([$user_id, $number_of_people, $class_type, $booking_date, $booking_time]);
        $success_message = "Your booking has been successfully made!";
    }
}

// Fetch all time slots
$all_slots = ["13:00:00", "15:00:00", "17:00:00"];

// Fetch booked slots for a specific date
$booked_slots = [];
if (!empty($_POST['booking_date'])) {
    $selected_date = $_POST['booking_date'];

    $stmt = $pdo->prepare("SELECT booking_time FROM bookings WHERE booking_date = ? AND is_booked = 1");
    $stmt->execute([$selected_date]);
    $booked_slots = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Dynamically exclude booked slots
$available_slots = array_diff($all_slots, $booked_slots);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bookings</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
         .header {
            text-align: center;
            padding: 20px;
            background-color: tan;
            color: black;
        }
        .booking-form {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background: #f4f4f4;
            border-radius: 8px;
        }
        .class-section {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .class-item {
            display: flex;
            gap: 20px;
        }
        .class-item img {
            max-width: 150px;
            border-radius: 8px;
        }
        .class-item .description {
            flex-grow: 1;
        }
        body {
            background-image: url(https://clayimports.com/cdn/shop/files/Organic-Smooth-Terracotta_by_Clay-Imports_6.jpg?v=1724268672&width=3840);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    
    </style>
</head>
<body>
    <div class="header">
        <h1>Bookings</h1>
    </div>

    <div class="booking-form">
        <h2>Book Your Class</h2>
        <div class="class-section">
        <div class="class-item">
            <img src="https://www.aloepaintedpots.com/cdn/shop/files/IMG_7281.jpg?v=1693441980&width=600" alt="Class 1">
            <div class="description">
                <h3>Class 1</h3>
                <p>Beginners to have fun with terracota and basic pot making.</p>
            </div>
        </div>

        <div class="class-item">
            <img src="https://deneenpottery.com/wp-content/uploads/2017/11/Throw.jpg" alt="Class 2">
            <div class="description">
                <h3>Class 2</h3>
                <p>Understand making pots on the wheel and advanced design techniques</p>
            </div>
        </div>

        <div class="class-item">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQnLWWw9ii4qZe6fxfj63aILbj44eiW4YT5xw&s" alt="Class 3">
            <div class="description">
                <h3>Glazed Pot Painting</h3>
                <p>Paint premade or your own pots</p>
            </div>
        </div>
        <!-- Display success or error messages -->
        <?php if ($success_message) { echo "<p class='w3-text-green'>$success_message</p>"; } ?>
        <?php if ($error_message) { echo "<p class='w3-text-red'>$error_message</p>"; } ?>

        <form method="POST">
            <input class="w3-input w3-border" type="number" name="number_of_people" placeholder="Number of People" required>
            <input class="w3-input w3-border" type="text" name="class_type" placeholder="Class Type" required>

            <label for="booking_date">Select Date:</label>
            <input class="w3-input w3-border" type="date" name="booking_date" value="<?php echo htmlspecialchars($_POST['booking_date'] ?? ''); ?>" required>

            <label for="booking_time">Select Time Slot:</label>
            <select class="w3-select w3-border" name="booking_time" required>
                <option value="" disabled selected>Select Time Slot</option>
                <?php
                foreach ($available_slots as $time) {
                    echo '<option value="' . htmlspecialchars($time) . '">' . htmlspecialchars($time) . '</option>';
                }
                ?>
            </select>

            <button class="w3-button w3-blue w3-margin-top" type="submit" name="submit_booking">Submit Booking</button>
        </form>
    </div>

    <div class="go-back-button w3-container">
        <form method="GET" action="PotteryDashboard.php">
            <button class="w3-button w3-blue w3-left w3-margin" type="submit">Go Back</button>
        </form>
    </div>
</body>
</html>
