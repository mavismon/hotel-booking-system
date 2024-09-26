<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

$name = $_SESSION['name'];

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rid = $_POST['rid'];
    $bdate = $_POST['bdate'];
    $guestName = $_POST['name'];
    $phone = $_POST['phone'];
    $extrabed = $_POST['extrabed'];
    $checkInDate = $_POST['checkInDate'];
    $checkOutDate = $_POST['checkOutDate'];

    // Insert new booking
    $stmt = $conn->prepare("INSERT INTO booking (rid, bdate, name, phone, extrabed, checkInDate, checkOutDate) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $rid, $bdate, $guestName, $phone, $extrabed, $checkInDate, $checkOutDate);

    if ($stmt->execute()) {
        echo '<script>alert("Booking added successfully."); window.location.href="adminbooking.php";</script>';
    } else {
        echo '<script>alert("Error adding booking.");</script>';
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
    <title>Add Booking</title>
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
            <span>Welcome admin<?php echo !empty($name) ? ', ' . htmlspecialchars($name) : ''; ?></span>
        </a>
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

            <h3 class="text-center">Add New Booking Here</h3>
            <div class="form-group">
                <label for="rid">Room ID</label>
                <input type="text" class="form-control" id="rid" name="rid" placeholder="Enter Room ID" required />
            </div>
            <div class="form-group">
                <label for="bdate">Birthday Date</label>
                <input type="date" class="form-control" id="bdate" name="bdate" required />
            </div>
            <div class="form-group">
                <label for="name">Guest Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Guest Name" required />
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone Number" required />
            </div>
            <div class="form-group">
                <label for="extrabed">Extra Bed (0 for No, 1 for Yes)</label>
                <input type="number" class="form-control" id="extrabed" name="extrabed" placeholder="Enter 0 or 1" required />
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
                <button type="submit" class="btn btn-book">Add Booking</button>
                <button type="button" class="btn btn-book" onclick="window.location.href='adminbooking.php'">View Bookings</button>
            </div>
        </form>
    </div>

    <script src="logout.js"></script>
</body>

</html>
