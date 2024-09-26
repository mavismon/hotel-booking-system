<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Redirect non-logged-in users
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$fid = $_GET['id'] ?? null;
if (!$fid) {
    echo '<script>alert("Invalid admin ID."); window.location.href="adminfood.php";</script>';
    exit();
}

$sql = "SELECT * FROM food WHERE fid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $fid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $food = $result->fetch_assoc();
} else {
    echo '<script>alert("Food menu not found."); window.location.href="adminfood.php";</script>';
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $foodtype = $_POST['foodtype'];
    $foodname = $_POST['foodname'];
    $price = $_POST['price'];

    // Handle photo upload
    $photo = $food['photo']; // Default to existing photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "images/";
        $photo = basename($_FILES['photo']['name']);
        $targetFilePath = $targetDir . $photo;
        move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath);
    }

    $stmt = $conn->prepare("UPDATE food SET foodtype = ?, foodname = ?, photo = ?, price = ? WHERE fid = ?");
    $stmt->bind_param("ssssi", $foodtype, $foodname, $photo, $price, $fid);

    if ($stmt->execute()) {
        echo '<script>alert("Food menu updated successfully."); window.location.href="adminfood.php";</script>';
        exit();
    } else {
        echo '<script>alert("Error updating food menu."); window.location.href="adminfood.php";</script>';
        exit();
    }
    
    $stmt->close();
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
                <li class="nav-item ">
                    <a class="nav-link" href="adminbooking.php">Booking</a>
                </li>
                <li class="nav-item">
                    <button id="logoutBtn" class="nav-link btn">Logout</button>
                </li>
            </ul>
        </div>
    </nav>


    <div class="form-container-wrapper mt-5 py-5">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-container" style="width: 1200px !important;">
                <h3 class="text-center">Update Food Menu</h3>
                <div class="form-group">
                    <label for="foodtype">Food Type</label>
                    <input type="text" class="form-control" id="foodtype" name="foodtype" value="<?php echo htmlspecialchars($food['foodtype']); ?>" placeholder="Enter Type of Food" required />
                </div>
                <div class="form-group">
                    <label for="foodname">Food Name</label>
                    <input type="text" class="form-control" id="foodname" name="foodname" value="<?php echo htmlspecialchars($food['foodname']); ?>" placeholder="Enter Food Name" required />
                </div>
            
                <div class="form-group">
                    <label for="photo">Photo</label>
                    <!-- File input field to upload a new photo -->
                    <input type="file" class="form-control" id="photo" name="photo" />
                    
                    <!-- Displaying the current photo with fixed dimensions -->
                    <small>Current photo:<?php echo htmlspecialchars($food['photo']); ?></small>
                    <br />
                    <img src="images/<?php echo !empty($food['photo']) ? htmlspecialchars($food['photo']) : 'default-image.jpg'; ?>" 
                        alt="Food Image" width="100" height="100" 
                        onerror="this.onerror=null; this.src='images/default-image.jpg';">
                </div>



                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($food['price']); ?>" placeholder="Enter the price of food" required />
                </div>
              
                <div class="text-center">
                    <button type="submit" class="btn btn-book text-black mr-2">Update Food Menu</button>
                    <button type="button" class="btn btn-book text-black mr-2" onclick="window.location.href='adminfood.php'">View Menu</button>
                </div>
            </div>
        </form>
    </div>

    <script src="logout.js"></script>
</body>
</html>
