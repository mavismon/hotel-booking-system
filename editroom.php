<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$rid = $_GET['id'] ?? null;

if (!$rid) {
    echo "Invalid room ID.<br>";
    exit();
}

$sql = "SELECT * FROM room WHERE rid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $rid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $room = $result->fetch_assoc();
} else {
    echo "Room not found.<br>";
    exit();
}

if (isset($_POST['update'])) {
    $roomtype = $_POST['room_type'] ?? '';
    $price = $_POST['price'] ?? 0;
    $ava_guest = $_POST['ava_guest'] ?? 0;
    $numOfRooms = $_POST['noOfRooms'] ?? 0;

    // Image handling
    if (is_uploaded_file($_FILES['image']['tmp_name'])) {
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
        } else {
            echo "File upload error: " . $_FILES['image']['error'] . "<br>";
        }
    }

    // Prepare the update statement
    if (isset($imageData)) {
        $stmt = $conn->prepare("UPDATE room SET roomtype=?, price=?, ava_guest=?, numOfRooms=?, image=? WHERE rid=?");
        $stmt->bind_param("sdisss", $roomtype, $price, $ava_guest, $numOfRooms, $imageData, $rid);
    } else {
        $stmt = $conn->prepare("UPDATE room SET roomtype=?, price=?, ava_guest=?, numOfRooms=? WHERE rid=?");
        $stmt->bind_param("sdiss", $roomtype, $price, $ava_guest, $numOfRooms, $rid);
    }

    if (!$stmt) {
        echo "SQL error: " . $conn->error . "<br>";
    } else {
        if ($stmt->execute()) {
            echo '<script>alert("Room details updated successfully."); window.location.href="adminroom.php";</script>';
        } else {
            echo "Error updating room: " . $stmt->error . "<br>";
        }
    }
}
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
    
    width: 600px; /* Set a specific width */
    max-width: 100%; /* Ensure it does not exceed the screen width */
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: rgba(251, 253, 243, 0.9);
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    margin: 0 auto; 
}
.form-container-wrapper {
  
    margin-top: 100px; /* Adjust the value as needed */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    width: 500%;
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
                <li class="nav-item active">
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

            </ul>
        </div>
    </nav>


    <div class="form-container-wrapper">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . htmlspecialchars($rid); ?>" method="post" enctype="multipart/form-data">
        <div class="form-container" >
            <h3 class="text-center ">Update Room Details</h3>
            <div class="form-group">
                <label for="room_type">Room Type</label>
                <input type="text" class="form-control" id="room_type" name="room_type" value="<?php echo htmlspecialchars($room['roomtype']); ?>" placeholder="Enter Room Type" required />
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($room['price']); ?>" placeholder="Enter Room Price" required />
            </div>
            <div class="form-group">
                <label for="ava_guest">Available Guests</label>
                <input type="number" class="form-control" id="ava_guest" name="ava_guest" value="<?php echo htmlspecialchars($room['ava_guest']); ?>" placeholder="Enter Number of Guests" required />
            </div>
            <div class="form-group">
                <label for="noOfRooms">Number of Rooms</label>
                <input type="number" class="form-control" id="noOfRooms" name="noOfRooms" value="<?php echo htmlspecialchars($room['numOfRooms']); ?>" placeholder="Enter Number of Rooms" required />
            </div>
            <div class="form-group">
    <label for="image">Photo</label>
    <input type="file" class="form-control" id="image" name="image" />
    <small>Current photo:</small>
    <br />
            <img src="getimage.php?rid=<?php echo urlencode($room['rid']); ?>" 
                alt="Room Image" width="100" height="100" 
                onerror="this.onerror=null; this.src='images/default-image.jpg';">
        </div>

            <div class="text-center">
                <button type="submit" name="update" class="btn btn-book text-black mr-2">Update Room</button>
                <button type="button" class="btn btn-book text-black mr-2" onclick="window.location.href='adminroom.php'">View Rooms</button>
            </div>
        </div>
    </form>
</div>



    <script src="logout.js"></script>
</body>
</html>