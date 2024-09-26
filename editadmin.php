<?php
session_start();

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

// Get the admin ID from the URL
$aid = $_GET['id'] ?? null;

if (!$aid) {
    echo '<script>alert("Invalid admin ID."); window.location.href="adminview.php";</script>';
    exit();
}

// Fetch admin details
$sql = "SELECT * FROM admin WHERE aid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $aid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
} else {
    echo '<script>alert("Admin not found."); window.location.href="adminview.php";</script>';
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Update admin details
    $stmt = $conn->prepare("UPDATE admin SET name = ?, email = ?, password = ?, phone = ?, address = ? WHERE aid = ?");
    $stmt->bind_param("sssssi", $name, $email, $password, $phone, $address, $aid);

    if ($stmt->execute()) {
        echo '<script>alert("Admin updated successfully."); window.location.href="adminview.php";</script>';
    } else {
        echo '<script>alert("Error updating admin.");</script>';
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
            <li class="nav-item active">
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
                <li class="nav-item">
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
        <div class="form-container" style="width: 1200px !important;">
            <h3 class="text-center">Update Admin Details</h3>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="name" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($admin['name']); ?>" placeholder="Enter your name" required />
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" placeholder="Enter your email address" required />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($admin['password']); ?>" placeholder="Enter your password" required />
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="phone" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($admin['phone']); ?>" placeholder="Enter your Phone Number" required />
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="address" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($admin['address']); ?>" placeholder="Enter your address" required />
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-book text-black mr-2">Update Admin</button>
                <button type="button" class="btn btn-book text-black mr-2" onclick="window.location.href='adminview.php'">View Admins</button>
            </div>
        </div>
    </form>
</div>






   

    <script src="logout.js"></script>
</body>
</html>
