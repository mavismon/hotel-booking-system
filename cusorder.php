<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$passwd = "";
$database = "hotel";
$conn = mysqli_connect($host, $user, $passwd, $database) or die("Could not connect to the database");

// Fetch orders
$orderQuery = "
    SELECT o.oid, o.date, o.remarks, o.total, r.rid 
    FROM `order` o
    JOIN room r ON o.rid = r.rid
";

$orderResult = mysqli_query($conn, $orderQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Orders</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
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
            background-color: rgb(207, 245, 205); 
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .custom-btn-delete:hover {
            background-color: rgb(255, 0, 0); 
            color: white !important; 
        }
        .custom-btn-add {
            display: inline-block; 
            padding: 10px 20px; 
            background-color: rgb(207, 245, 205); 
            color: black; 
            text-align: center; 
            text-decoration: none; 
            border-radius: 5px; 
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .custom-btn-add:hover {
            background-color: rgb(0, 0, 0); 
            color: white; 
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
            <li class="nav-item">
                <a class="nav-link" href="adminview.php">Admin</a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="adminfood.php">Food</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="cusorder.php">Order</a>
            </li>
            <li class="nav-item ">
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

<div class="container mt-3 py-1">
    <h2 class="my-4 mt-5 text-center">Customer Orders</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Order ID</th>
                <th>Room No</th>
                <th>Remarks</th>
                <th>Total Sales</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($orderResult && mysqli_num_rows($orderResult) > 0) {
                while ($row = mysqli_fetch_assoc($orderResult)) {
                    $orderID = $row['oid'];
                    $roomID = $row['rid'];
                    $date = date('M d, Y h:i A', strtotime($row['date']));
                    $remarks = $row['remarks'];
                    $total = $row['total'];

                    echo "<tr>";
                    echo "<td>{$date}</td>";
                    echo "<td>{$orderID}</td>";
                    echo "<td>{$roomID}</td>";
                    echo "<td>{$remarks}</td>";
                    echo "<td>$" . number_format($total, 2) . "</td>";
                    echo "<td><a href='cusorderdetail.php?oid={$orderID}' class='view-detail'>View Details</a></td>"; // Fixed here
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No orders found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="logout.js"></script>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
