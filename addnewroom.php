<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

$name = $_SESSION['name'];

// Database connection
$host = "localhost";
$user = "root";
$passwd = "";
$database = "hotel";
$table_name = "room"; 

$connect = mysqli_connect($host, $user, $passwd, $database) or die("Could not connect to database");

// File upload variables
$target_dir = "images/";
$maxsize = 5242880; // 5MB
$extensions_arr = array("png", "jpeg", "jpg", "gif");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $rid = $_POST['rid'];
    $roomType = $_POST['roomtype'];
    $price = $_POST['price'];
    $ava_guest = $_POST['ava_guest'];
    $numOfRooms = $_POST['numOfRooms'];
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Check if file is an actual image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo '<script>alert("File is not an image.");</script>';
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["image"]["size"] > $maxsize || $_FILES["image"]["size"] == 0) {
        echo '<script>alert("File too large. File must be less than 5MB.");</script>';
        $uploadOk = 0;
    }

    // Allow specific file formats
    if (!in_array($imageFileType, $extensions_arr)) {
        echo '<script>alert("Invalid file extension. Only JPG, JPEG, PNG & GIF files are allowed.");</script>';
        $uploadOk = 0;
    }

 
if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Read the image file as binary data
        $imageData = file_get_contents($target_file);
        $imageData = mysqli_real_escape_string($connect, $imageData); // Escape binary data for database

        // Insert room into the database
        $sql = "INSERT INTO room (rid, roomtype, price, ava_guest, numOfRooms, image) VALUES ('$rid', '$roomType', '$price', '$ava_guest', '$numOfRooms', '$imageData')";
        
        if (!mysqli_query($connect, $sql)) {
            echo '<script>alert("Error adding new room: ' . mysqli_error($connect) . '");</script>';
        } else {
            echo '<script>alert("New room added successfully."); window.location.href="adminroom.php";</script>';
        }
    } else {
        echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
    }
}}


mysqli_close($connect);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Room</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: rgb(251, 253, 243);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: rgba(251, 253, 243, 0.9);
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h3 class="text-center">Add New Room</h3>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="rid">Room ID</label>
                <input type="text" class="form-control" id="rid" name="rid" placeholder="Enter room ID" required />
            </div>
            <div class="form-group">
                <label for="roomtype">Room Type</label>
                <input type="text" class="form-control" id="roomtype" name="roomtype" placeholder="Enter room type" required />
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" required />
            </div>
            <div class="form-group">
                <label for="ava_guest">Available Guests</label>
                <input type="number" class="form-control" id="ava_guest" name="ava_guest" placeholder="Enter available guests" required />
            </div>
            <div class="form-group">
                <label for="numOfRooms">Number of Rooms</label>
                <input type="number" class="form-control" id="numOfRooms" name="numOfRooms" placeholder="Enter number of rooms" required />
            </div>
            <div class="form-group">
                <label for="image">Image (Upload)</label>
                <input type="file" class="form-control" id="image" name="image" required />
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Add New Room</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='adminroom.php'">View Rooms</button>
            </div>
        </form>
    </div>
    <script src="logout.js"></script>
</body>
</html>
