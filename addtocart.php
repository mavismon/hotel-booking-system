<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if cart session exists, if not, create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Retrieve POST data
$foodName = $_POST['foodname'] ?? '';
$price = $_POST['price'] ?? '';
$quantity = $_POST['quantity'] ?? '';

// Simple validation
if ($foodName && is_numeric($price) && is_numeric($quantity) && $quantity > 0) {
    // Add to cart
    $_SESSION['cart'][] = [
        'foodName' => htmlspecialchars($foodName),
        'price' => (float)$price,
        'quantity' => (int)$quantity
    ];

    // Respond with a success message
    echo json_encode([ "The food has been added to the cart. Quantity: $quantity Total: $$price each."]);
} else {
    // Respond with an error message
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
}
?>
