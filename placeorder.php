<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if cart session exists
$cart = $_SESSION['cart'] ?? [];

// Database connection
$host = "localhost";
$user = "root";
$passwd = "";
$database = "hotel";
$connect = mysqli_connect($host, $user, $passwd, $database);

if (!$connect) {
    die("Could not connect to the database: " . mysqli_connect_error());
}

// Retrieve POST data
$room_id = $_POST['room_id'] ?? '';
$phone = $_POST['phone'] ?? '';
$remarks = $_POST['remarks'] ?? ''; // Handle remarks, default to empty string

// Debug: Print POST data
echo "<pre>";
print_r($_POST);
echo "</pre>";

// Simple validation
if ($room_id && $phone && !empty($cart)) {
    // Escape the user input to avoid SQL injection
    $room_id = mysqli_real_escape_string($connect, $room_id);
    $phone = mysqli_real_escape_string($connect, $phone);
    $remarks = mysqli_real_escape_string($connect, $remarks);

    // Calculate total price
    $total_price = 0;

    foreach ($cart as $item) {
        if (isset($item['price'], $item['quantity'])) {
            $total_price += $item['price'] * $item['quantity'];
        }
    }

    // Insert the order into the `order` table with date and total
    $order_query = "INSERT INTO `order` (rid, phone, date, total, remarks) VALUES ('$room_id', '$phone', NOW(), $total_price, '$remarks')";
    $result = mysqli_query($connect, $order_query);
    
    if ($result) {
        // Get the inserted order ID
        $order_id = mysqli_insert_id($connect);

        // Insert order items
        foreach ($cart as $item) {
            if (isset($item['foodName'], $item['price'], $item['quantity'])) {
                $foodName = mysqli_real_escape_string($connect, $item['foodName']);
                $price = $item['price'];
                $quantity = $item['quantity'];

                // Retrieve the food ID
                $food_query = "SELECT fid FROM food WHERE foodname = '$foodName'";
                $food_result = mysqli_query($connect, $food_query);
                
                if ($food_result && mysqli_num_rows($food_result) > 0) {
                    $food_row = mysqli_fetch_assoc($food_result);
                    $food_id = $food_row['fid'];

                    // Calculate subtotal
                    $subtotal = $price * $quantity;

                    // Insert order item into `order_detail`
                    $order_item_query = "INSERT INTO `order_detail` (oid, fid, quantity, subtotal) VALUES ('$order_id', '$food_id', $quantity, $subtotal)";
                    if (!mysqli_query($connect, $order_item_query)) {
                        echo "Error inserting order item: " . mysqli_error($connect);
                    }
                } else {
                    echo "Failed to retrieve food ID for $foodName. Error: " . mysqli_error($connect);
                }
            } else {
                echo "Missing data for an item.";
            }
        }

        // Clear the cart
        unset($_SESSION['cart']);

        // Success message and redirect
        echo "<script>
            alert('Order placed successfully. Your order ID is $order_id.');
            window.location.href='menu.php';
        </script>";
    } else {
        echo "Failed to place the order. Error: " . mysqli_error($connect);
    }
} else {
    echo "Invalid input or empty cart.";
}

// Close the database connection
mysqli_close($connect);
?>
