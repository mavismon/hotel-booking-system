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

// Get the booking ID from the URL
$bid = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$bid) {
    echo '<script>alert("Invalid booking ID."); window.location.href="adminbooking.php";</script>';
    exit();
}

// Fetch booking details
$sql = "SELECT * FROM booking WHERE bid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $booking = $result->fetch_assoc();
} else {
    echo '<script>alert("Booking not found."); window.location.href="adminbooking.php";</script>';
    exit();
}

// Handle form submission for booking update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debugging: Output all POST data
    var_dump($_POST); // Check what values are coming from the form

    $rid = $_POST['rid'];
    $bdate = $_POST['bdate'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $extrabed = $_POST['extrabed'];
    $checkInDate = $_POST['checkInDate'];
    $checkOutDate = $_POST['checkOutDate']; 

    // Debugging output for check-in and check-out dates
    echo "Check-in Date: " . $checkInDate . "<br>"; 
    echo "Check-out Date: " . $checkOutDate . "<br>"; 

    // Additional validation: check if check-in date is before the check-out date
    if (strtotime($checkInDate) > strtotime($checkOutDate)) {
        echo '<script>alert("Check-out date cannot be earlier than Check-in date.");</script>';
    } else {
    

        if (strtotime($checkInDate) > strtotime($checkOutDate)) {
            echo '<script>alert("Check-out date cannot be earlier than Check-in date.");</script>';
        } else {
            
            $sql = "UPDATE booking SET rid = '$rid', bdate = '$bdate', name = '$name', phone = '$phone', extrabed = $extrabed, checkInDate = '$checkInDate', checkOutDate = '$checkOutDate' WHERE bid = $bid";
            
            if ($conn->query($sql) === TRUE) {
                echo '<script>alert("Booking updated successfully."); window.location.href="adminbooking.php";</script>';
            } else {
                echo "Error updating record: " . $conn->error;
            }
        }
          $stmt->close();
    }
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
         body {
            background-color: rgb(251, 253, 243);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            /* background: url("./images/login.png") no-repeat center center; */
            background-size: cover;
        }
        .form-container-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 500%;
        }
        .form-container {
    width: 1000px; /* Set a specific width */
    max-width: 100%; /* Ensure it does not exceed the screen width */
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: rgba(251, 253, 243, 0.9);
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    margin: 0 auto; /* Center the form */
}

        .custom-container {
            max-width: 800px;
        }

        .img-rounded {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1,
        h2,
        h3 {
            font-family: "Arial", sans-serif;
        }

        p.lead {
            font-size: 1.25rem;
        }

        .navbar-brand,
        .nav-link {
            font-family: "Arial", sans-serif;
            color: #ffffff;
        }

        .navbar {
            background-color: rgb(207, 245, 205);
        }

        .icon-style {
            font-size: 22px;
            color: rgb(0, 0, 0);
            margin-right: 8px;
        }

        .brand-name {
            font-size: 20px;
            font-weight: bold;
            color: rgb(0, 0, 0);
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: color 0.3s ease;
        }

        .navbar-brand:hover .brand-name {
            color: #342f2f;
        }

        .fixed-size {
            width: 70%;
            height: 250px;
            object-fit: cover;
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

        .custom-btn {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-book {
            transition: background-color 0.3s ease, color 0.3s ease;
            background-color: rgb(207, 245, 205);
            font-size: large;
            font-weight: 500;
        }

        .custom-btn:active {
            background-color: rgb(207, 245, 205);
            color: black !important;
        }

        .btn-book:active {
            background-color: rgb(0, 0, 0);
            color: rgb(255, 255, 255) !important;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <i class="bi bi-house-door icon-style"></i>
            <span class="brand-name">Welcome admin<?php echo !empty($_SESSION['name']) ? ', ' . htmlspecialchars($_SESSION['name']) : ''; ?></span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
            <li class="nav-item ">
                    <a class="nav-link" href="adminview.php">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="adminfood.php">Food</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cusorder.php">Order</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="message.php">Message</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="adminroom.php">Rooms</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="adminbooking.php">Booking</a>
                </li>
                <li class="nav-item">
                    <button id="logoutBtn" class="nav-link btn">Logout</button>
                </li>
            </ul>
        </div>
    </nav>

   
    <div class="form-container-wrapper mt-5 py-5">
        <form action="" method="POST">
        <div class="form-container mt-5" style="width: 1200px !important;">

                <h3 class="text-center">Update Booking Details</h3>
                <div class="form-group">
                    <label for="rid">Room ID</label>
                    <input type="text" class="form-control" id="rid" name="rid" value="<?php echo htmlspecialchars($booking['rid']); ?>" placeholder="Enter Room ID" required />
                </div>
                <div class="form-group">
                    <label for="bdate">Birthday Date</label>
                    <input type="date" class="form-control" id="bdate" name="bdate" value="<?php echo htmlspecialchars($booking['bdate']); ?>" required />
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($booking['name']); ?>" placeholder="Enter Name" required />
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($booking['phone']); ?>" placeholder="Enter Phone Number" required />
                </div>
                <div class="form-group">
                    <label for="extrabed">Extra Bed</label>
                    <input type="number" class="form-control" id="extrabed" name="extrabed" value="<?php echo htmlspecialchars($booking['extrabed']); ?>" placeholder="Extra Bed (0 or 1)" min="0" max="1" required />
                </div>
                <div class="form-group">
                  
                    <label for="checkInDate">Check-In Date:</label>
                     <input type="date" name="checkInDate" value="<?php echo $booking['checkInDate']; ?>" required>

                </div>
                <div class="form-group">
                <label for="checkOutDate">Check-Out Date:</label>
    <input type="date" name="checkOutDate" value="<?php echo $booking['checkOutDate']; ?>" required>


                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success">Update Booking</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='adminbooking.php'">Cancel</button>
                </div>
            </div>
        </form>
</div>

    <script src="logout.js"></script>
</body>
</html>
