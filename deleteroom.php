<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Check if the user is logged in
    if (!isset($_SESSION['loggedin'])) {
        throw new Exception('User is not logged in.');
    }

    // Check if the ID is passed in the URL
    if (!isset($_GET['id'])) {
        throw new Exception('Room ID is not passed.');
    }
    
    // Get the room ID from the URL
    $id = $_GET['id'];
    echo "Received ID: " . htmlspecialchars($id) . "<br>";

    // Validate the room ID as a non-empty string (allow letters and numbers)
    if (empty($id) || !preg_match('/^[A-Za-z0-9]+$/', $id)) {
        throw new Exception('Invalid room ID format.');
    }

    // Proceed with database deletion
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hotel";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Prepare the DELETE query using a prepared statement
    $stmt = $conn->prepare("DELETE FROM room WHERE rid = ?");
    if (!$stmt) {
        throw new Exception("Error preparing delete statement: " . $conn->error);
    }

    $stmt->bind_param("s", $id);  // 's' denotes string type
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo '<script>alert("Room deleted successfully."); window.location.href="adminroom.php";</script>';
    } else {
        throw new Exception("No room found with this ID.");
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . ' on line ' . $e->getLine();
}
