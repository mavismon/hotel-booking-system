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

    // Get the Food Menu ID
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    // Debugging: Print the ID (Can be removed in production)
    echo "<script>console.log('Received Food Menu ID: " . htmlspecialchars($id) . "');</script>";

    if ($id === null || $id <= 0) {
        throw new Exception("Invalid Food Menu ID.");
    }

    // Verify if the Food Menu ID exists
    $sql = "SELECT * FROM food WHERE fid = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Debugging: Print SQL query results (Can be removed in production)
    echo "<script>console.log('Number of rows: " . $result->num_rows . "');</script>";

    if ($result->num_rows === 0) {
        throw new Exception("Food Menu ID does not exist.");
    }

    // Delete Food Menu
    $stmt = $conn->prepare("DELETE FROM food WHERE fid = ?");
    if (!$stmt) {
        throw new Exception("Error preparing delete statement: " . $conn->error);
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo '<script>alert("Food Menu deleted successfully."); window.location.href="adminfood.php";</script>';
        } else {
            throw new Exception("No Food Menu found with this ID.");
        }
    } else {
        throw new Exception("Error executing delete query: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    // For debugging purposes
    echo "<script>console.error('Error: " . $e->getMessage() . " on line " . $e->getLine() . "');</script>";
}
