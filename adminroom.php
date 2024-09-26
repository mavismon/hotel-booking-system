<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel"; // replace with your database name

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch all rooms
$sql = "SELECT rid, roomtype, price, ava_guest, numOfRooms FROM room";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Hotel Rooms</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
      body {
            background-color: rgb(251, 253, 243);
        }
        .custom-container {
            max-width: 1200px;
        }
        .navbar {
            background-color: rgb(207, 245, 205);
        }
        .brand-name {
            font-size: 20px;
            font-weight: bold;
            color: rgb(0, 0, 0);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .table-striped th, .table-striped td {
            text-align: center;
        }
        .btn {
            background-color: rgb(207, 245, 205);
        }
        .custom-btn-delete {
            background-color: rgb(207, 245, 205); /* Original green */
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .custom-btn-delete:hover {
            background-color: rgb(255, 0, 0); /* Red background on hover */
            color: white !important; /* White text on hover */
        }
        .custom-btn-add {
            display: inline-block; /* Ensure the link behaves like a button */
            padding: 10px 20px; /* Adjust padding as needed */
            background-color: rgb(207, 245, 205); /* Original green background */
            color: black; /* Text color */
            text-align: center; /* Center text */
            text-decoration: none; /* Remove underline */
            border-radius: 5px; /* Optional: round the corners */
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .custom-btn-add:hover {
            background-color: rgb(0, 0, 0); /* Black background on hover */
            color: white; /* White text on hover */
        }
</style>
<body>
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <i class="bi bi-house-door icon-style"></i>
            <span class="brand-name">Welcome admin<?php echo !empty($name) ? ', ' . htmlspecialchars($name) : ''; ?></span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="adminview.php">Admin</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="adminfood.php">Food</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cusorder.php">Order</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="message.php">Message</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="adminroom.php">Rooms</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="adminbooking.php">Booking</a>
                </li>
                <li class="nav-item">
                    <button id="logoutBtn" class="nav-link btn">Logout</button>
                </li>
            </ul>
        </div>
    </nav>



    <div class="container mt-3 py-1">
        <h2 class="text-center my-4 mt-5">Manage Room </h2>
        <table class="table table-striped ">
            <thead>
    
   
        <tr>
            <th>Room Id</th>
            <th>Room Type</th>
            <th>Price</th>
            <th>Available Guests</th>
            <th>Number of Rooms</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        
        <?php
        if ($result->num_rows > 0) {
            // Output data for each room
            while($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $row['rid']; ?></td>
                    <td><?php echo $row['roomtype']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['ava_guest']; ?></td>
                    <td><?php echo $row['numOfRooms']; ?></td>
                    <td>
                        <img src="getimage.php?rid=<?php echo $row['rid']; ?>" alt="Room Image" style="width:100px; height:100px;">
                    </td>
                    <td>
                    <a href="editroom.php?id=<?php echo $row['rid']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="deleteroom.php?id=<?php echo urlencode($row['rid']); ?>" class="btn custom-btn-delete btn-sm" onclick="return confirm('Are you sure you want to delete this room?');">Delete</a>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='7'>No rooms available</td></tr>";
        }
        ?>
    </table>
    <div class="text-center">
        <button type="button" class="btn btn-book" onclick="window.location.href='addnewroom.php'">Add New Room</button>
        </div>
    </div>
    
</body>
</html>

<?php
$conn->close();
?>
