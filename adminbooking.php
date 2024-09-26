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

$name = $_SESSION['name']; // Get the admin's name from the session

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel";

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to retrieve all admins

$sql = "SELECT bid, rid, bdate, name, phone, extrabed, checkInDate, checkOutDate FROM booking ";

// Execute the SQL query
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die('Error executing query: ' . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
    color: white !important; /* Black text on hover */
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
    background-color: rgb(0, 0, 0); /* Red background on hover */
    color: white; /* Black text on hover */
}

    </style>
</head>
<body>
    <!-- Navigation -->
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

    <div class="container mt-5 py-5">
        <h2 class="text-center">Manage Bookings</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Room ID</th>
                    <th>Birthday Date</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Extrabed</th>
                    <th>Check in Date</th>
                    <th>Check out Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through each row of the result set
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['bid']); ?></td>
                            <td><?php echo htmlspecialchars($row['rid']); ?></td>
                            <td><?php echo htmlspecialchars($row['bdate']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['extrabed']); ?></td>
                            <td><?php echo htmlspecialchars($row['checkInDate']); ?></td>
                            <td><?php echo htmlspecialchars($row['checkOutDate']); ?></td>
                           
                            <td>
                            <a href="editbooking.php?id=<?php echo $row['bid']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="deletebooking.php?id=<?php echo urlencode($row['bid']); ?>" class="btn custom-btn-delete btn-sm" onclick="return confirm('Are you sure you want to cancel this booking?');">Cancel</a>

                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='6'>No Bookings found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="text-center">
    <a href="addbooking.php" class="btn custom-btn-add">Add New Booking</a>
</div>
    </div>

    <script src="logout.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
