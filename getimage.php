<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['rid'])) {
    $rid = $_GET['rid'];

    // Prepare SQL query to fetch the image
    $stmt = $conn->prepare("SELECT image FROM room WHERE rid = ?");
    $stmt->bind_param("s", $rid);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($imageData);
    $stmt->fetch();

    // Check if image data is available
    if ($stmt->num_rows > 0 && $imageData) {
        // Set the correct header for the image type (adjust based on the actual image type)
        header("Content-Type: image/jpeg"); // If you store different image types, make this dynamic
        echo $imageData;
    } else {
        // Display a placeholder or error message
        echo "No image found for Room ID: " . htmlspecialchars($rid);
    }

    $stmt->close();
}

$conn->close();

?>
