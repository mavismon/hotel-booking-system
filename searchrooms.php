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

// Get the input from the form
$checkIn = $_GET['checkIn'];
$guests = $_GET['guests'];

// Check if the check-in date is in the future or in the past
$currentDate = date("Y-m-d"); // Get current date in YYYY-MM-DD format
if ($checkIn < $currentDate) {
    echo '<script>alert("Please input a valid check-in date that is today or in the future."); window.location.href="room.html";</script>';    exit; // Stop further execution
}

// SQL query to fetch rooms that are available for the input check-in date and guest count
$sql = "SELECT * FROM room WHERE ava_guest >= ? AND numOfRooms > 0 AND rid NOT IN (
        SELECT rid FROM booking WHERE checkInDate = ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $guests, $checkIn);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Rooms</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
  
    <style>
        body {
            background-color: rgb(251, 253, 243);
        }
        .btn-book {
            transition: background-color 0.3s ease, color 0.3s ease;
            background-color: rgb(207, 245, 205);
            font-size: large;
            font-weight: 500;
            display: block; /* Make it a block element */
            margin: 0 auto; /* Center it */
}

    .btn-book:active {
      background-color: rgb(0, 0, 0);
      color: rgb(
        255,
        255,
        255
      ) !important; 
    }
  

    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Available Rooms</h2>
    <div class="row mt-3">
        <?php
        if ($result->num_rows > 0) {
            // Fetch the rooms into an array
            $rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
            
            foreach ($rooms as $room) {
                // Display the room details
                echo "<div class='col-md-4'>"; // Change the size according to your layout
                echo "<div class='card mb-4'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>{$room['roomtype']}</h5>"; // Assuming room type is stored in the 'roomtype' column
                echo "<p class='card-text'>Max Guests: {$room['ava_guest']}</p>"; // Max guests
                echo "<p class='card-text'>Price: \${$room['price']} / Night</p>"; // Price
                echo "<p class='card-text'>Available Rooms: {$room['numOfRooms']}</p>"; // Available rooms
        
                // Display the image
                if (!empty($room['image'])) {
                    $imageData = base64_encode($room['image']);
                    echo "<img src='data:image/jpeg;base64,{$imageData}' class='card-img-top' alt='Room Image' style='height: 200px; object-fit: cover;'/>";
                } else {
                    echo "<p>No image available</p>";
                }
        
              //  echo "<a href='bookroom.php?rid={$room['rid']}' class='btn btn-book mt-2 '>Book Now</a>"; // Book button
                echo "<a href='bookroom.php?rid={$room['rid']}&roomtype={$room['roomtype']}&price={$room['price']}' class='btn btn-book mt-2'>Book Now</a>"; // Book button

                echo "</div></div></div>";
            }
        } else {
            echo '<script>alert("No rooms left for desired numbers of guests. Please choose another rooms or I am so sorry to announce that your chosen room is fully booked"); window.location.href="room.html";</script>';    exit; 
        }
        ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
