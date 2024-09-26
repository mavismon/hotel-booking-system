<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get room ID from URL
$rid = isset($_GET['rid']) ? $_GET['rid'] : '';

if (empty($rid)) {
    echo '<script>alert("Room ID is missing. Please try again."); window.history.back();</script>';
    exit();
}

$roomType = isset($_GET['roomtype']) ? $_GET['roomtype'] : '';
$price = isset($_GET['price']) ? $_GET['price'] : '';

// Check available rooms
$sqlCheck = "SELECT numOfRooms FROM room WHERE rid = ?";
$stmtcheck = $conn->prepare($sqlCheck);
$stmtcheck->bind_param("s", $rid);
$stmtcheck->execute();
$stmtcheck->bind_result($availableRooms);
$stmtcheck->fetch();
$stmtcheck->close();

if ($availableRooms <= 0) {
    echo '<script>alert("Sorry, no rooms are available for the selected room type. Please choose a different room or date."); window.history.back();</script>';
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $birthday = $_POST['birthday'];
    $extrabed = $_POST['extrabed'] === "Yes" ? 1 : 0; // Convert Yes/No to 1/0
    $checkInDate = $_POST['checkInDate'];
    $checkOutDate = $_POST['checkOutDate'];

    // Validate dates
    $today = date("Y-m-d");
    if (strtotime($checkInDate) < strtotime($today)) {
        echo '<script>alert("Check-in date cannot be in the past. Please choose a valid date."); window.history.back();</script>';
        exit();
    }

    if (strtotime($checkOutDate) < strtotime($checkInDate)) {
        echo '<script>alert("Check-out date cannot be earlier than check-in date. Please choose a valid date."); window.history.back();</script>';
        exit();
    }

    // Validate phone number
    if (!is_numeric($phone)) {
        echo '<script>alert("Please enter a valid phone number."); window.history.back();</script>';
        exit();
    }

    // SQL query to insert booking details
    $sql = "INSERT INTO booking (rid, name, phone, extrabed, checkInDate, checkOutDate, bdate) 
            VALUES (?, ?, ?, ?, ?, ?, CURDATE())"; // bdate is set to the current date

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind parameters
    $stmt->bind_param("ssisss", $rid, $name, $phone, $extrabed, $checkInDate, $checkOutDate);

    // Execute the statement
    if ($stmt->execute()) {
        $bookingId = $conn->insert_id;

        // Update number of rooms
        $sqlUpdateRooms = "UPDATE room SET numOfRooms = numOfRooms - 1 WHERE rid= ?";
        $stmtUpdateRooms = $conn->prepare($sqlUpdateRooms);
        $stmtUpdateRooms->bind_param("s", $rid);
        $stmtUpdateRooms->execute();
        $stmtUpdateRooms->close();

        echo '<script>alert("Your booking has been successfully added. Booking ID: ' . $bookingId . ', Room ID: ' . $rid . '"); window.location.href="room.html";</script>';
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
</head>
<style>
    body {
        background-color: rgb(251, 253, 243);
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background-size: cover;
    }
    .btn-book {
        transition: background-color 0.3s ease, color 0.3s ease;
        background-color: rgb(207, 245, 205);
        font-size: large;
        font-weight: 500;
        display: block;
        margin: 0 auto;
    }
    .btn-book:active {
        background-color: rgb(0, 0, 0);
        color: rgb(255, 255, 255) !important; 
    }
    .form-container-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        width: 500%;
    }
    .form-container {
        width: 1000px; 
        max-width: 100%; 
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        background-color: rgba(251, 253, 243, 0.9);
        margin: 0 auto; 
    }
</style>
<body>
<div class="container mt-5">
    <h2 class="text-center">Booking Confirmation </h2>
    <div class="form-container" style="width: 600px !important;">
        <form method="post" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="birthday">Birthday:</label>
                <input type="date" class="form-control" id="birthday" name="birthday" required>
            </div>
            <div class="form-group">
                <label for="extrabed">Extra Bed:</label>
                <select class="form-control" id="extrabed" name="extrabed" required>
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                </select>
            </div>
            <div class="form-group">
                <label for="checkInDate">Check-In Date:</label>
                <input type="date" class="form-control" id="checkInDate" name="checkInDate" required>
            </div>
            <div class="form-group">
                <label for="checkOutDate">Check-Out Date:</label>
                <input type="date" class="form-control" id="checkOutDate" name="checkOutDate" required>
            </div>
            <button type="submit" class="btn btn-primary">Confirm Booking</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
?>
