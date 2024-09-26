<?php
session_start();

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
$table_name = "food";

$connect = mysqli_connect($host, $user, $passwd, $database) or die("Could not connect to database");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $foodType = $_POST['food-type'];
    $foodName = $_POST['food-name'];
    $price = $_POST['price'];

    // File upload directory
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $maxsize = 5242880; // 5MB
    $uploadOk = 1;

    // Select file type
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Valid file extensions
    $extensions_arr = array("png", "jpeg", "jpg", "gif");

    // Check if file is an actual image
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if ($check === false) {
        echo '<script>alert("File is not an image.");</script>';
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["photo"]["size"] > $maxsize || $_FILES["photo"]["size"] == 0) {
        echo '<script>alert("File too large. File must be less than 5MB.");</script>';
        $uploadOk = 0;
    }

    // Allow specific file formats
    if (!in_array($imageFileType, $extensions_arr)) {
        echo '<script>alert("Invalid file extension. Only JPG, JPEG, PNG & GIF files are allowed.");</script>';
        $uploadOk = 0;
    }

    // If everything is ok, try to upload the file
   
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                    echo "File uploaded to " . $target_file; // Debug line to check file path
                } else {
                    echo "File upload failed.";
                }
            
            
            // Check if price is a positive integer
            if (!is_numeric($price) || intval($price) <= 0) {
                echo '<script>alert("Invalid price. Price must be a positive integer.");</script>';
            } else {
                // Insert food item into the database
              //  $sql = "INSERT INTO food (foodtype, foodname, photo, price) VALUES ('$foodType', '$foodName', '$target_file', '$price')";
                $sql = "INSERT INTO food (foodtype, foodname, photo, price) VALUES ('$foodType', '$foodName', '" . basename($target_file) . "', '$price')";

                
                if (!mysqli_query($connect, $sql)) {
                    echo '<script>alert("Error adding new food item: ' . mysqli_error($connect) . '");</script>';
                } else {
                    echo '<script>alert("New food item added successfully."); window.location.href="adminfood.php";</script>';
                }
            }
        } else {
            echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
        }
    }


mysqli_close($connect);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Food Item</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: rgb(251, 253, 243);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-size: cover;
        }
        .form-container-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
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
        .btn-book {
            transition: background-color 0.3s ease, color 0.3s ease;
            background-color: rgb(207, 245, 205);
            font-size: large;
            font-weight: 500;
        }
        .btn-book:active {
            background-color: rgb(0, 0, 0);
            color: rgb(255, 255, 255) !important;
        }
    </style>
</head>
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
            <li class="nav-item ">
                    <a class="nav-link" href="adminview.php">Admin</a>
                </li>
                <li class="nav-item active">
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
                <li class="nav-item">
                    <a class="nav-link" href="adminbooking.php">Booking</a>
                </li>
                <li class="nav-item">
                    <button id="logoutBtn" class="nav-link btn">Logout</button>
                </li>
            </ul>
        </div>
    </nav>

    <div class="form-container-wrapper">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-container">
                <h3 class="text-center">Add New Food Item</h3>
                
                <div class="form-group">
                    <label for="food-type">Food Type</label>
                    <input type="text" class="form-control" id="food-type" name="food-type" placeholder="Enter food type" required />
                </div>

                <div class="form-group">
                    <label for="food-name">Food Name</label>
                    <input type="text" class="form-control" id="food-name" name="food-name" placeholder="Enter food name" required />
                </div>

                <div class="form-group">
                    <label for="photo">Photo (Upload)</label>
                    <input type="file" class="form-control" id="photo" name="photo" required />
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" required />
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-book">Add New Food</button>
                    <button type="button" class="btn btn-book" onclick="window.location.href='adminfood.php'">View Food Menu</button>
                </div>
            </div>
        </form>
    </div>
    <script src="logout.js"></script>
</body>
</html>
