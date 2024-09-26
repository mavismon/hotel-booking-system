<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$passwd = "";
$database = "hotel";
$conn = mysqli_connect($host, $user, $passwd, $database) or die("Could not connect to the database");

// Check if order ID is set in the URL
if (isset($_GET['oid'])) {
    $orderid = intval($_GET['oid']);
    // echo "Order ID: " . htmlspecialchars($orderid); // Debugging line

    // Fetch order details
    // Fetch order details with food names
$detailQuery = "
SELECT od.quantity, od.subtotal, f.foodname, f.price
FROM order_detail od
JOIN food f ON od.fid = f.fid
WHERE od.oid = $orderid
";

    $detailResult = mysqli_query($conn, $detailQuery);

    if (!$detailResult) {
        die("Error fetching order details: " . mysqli_error($conn));
    }

    // Fetch order information for header
    $orderQuery = "
        SELECT o.date, o.remarks, o.total
        FROM `order` o
        WHERE o.oid = $orderid
    ";
    $orderResult = mysqli_query($conn, $orderQuery);

    if (!$orderResult) {
        die("Error fetching order information: " . mysqli_error($conn));
    }

    $orderInfo = mysqli_fetch_assoc($orderResult);
} else {
    echo "No order ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
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
<body>
    <div class="container mt-5">
        <h2 class="my-4 text-center">Order Details</h2>

        <?php if ($orderInfo): ?>
            <p><strong>Date:</strong> <?php echo date('M d, Y h:i A', strtotime($orderInfo['date'])); ?></p>
            <p><strong>Remarks:</strong> <?php echo htmlspecialchars($orderInfo['remarks']); ?></p>
            <p><strong>Total Sales:</strong> $<?php echo number_format($orderInfo['total'], 2); ?></p>

            <table class="table table-bordered mt-4">
    <thead>
        <tr>
            <th>Food Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($detailResult && mysqli_num_rows($detailResult) > 0): ?>
            <?php while ($detailRow = mysqli_fetch_assoc($detailResult)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($detailRow['foodname']); ?></td>
                    <td>$<?php echo number_format($detailRow['price'], 2); ?></td>
                    <td><?php echo intval($detailRow['quantity']); ?></td>
                    <td>$<?php echo number_format($detailRow['subtotal'], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No details found for this order.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

        <?php else: ?>
            <p>No order information found.</p>
        <?php endif; ?>

        <a href="cusorder.php" class="btn custom-btn-add mt-3">Back to Orders</a>
    </div>

    <script src="logout.js"></script>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>