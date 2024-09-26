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

    // Get admin ID
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    // Debugging: Print the ID
    echo "Received Admin ID: " . htmlspecialchars($id) . "<br>";

    if ($id === null || $id <= 0) {
        throw new Exception("Invalid admin ID.");
    }

    // Verify the admin ID exists
    $sql = "SELECT * FROM admin WHERE aid = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }
    
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Debugging: Print SQL query results
    echo "Number of rows: " . $result->num_rows . "<br>";

    if ($result->num_rows === 0) {
        throw new Exception("Admin ID does not exist.");
    }

    // Delete admin
    $stmt = $conn->prepare("DELETE FROM admin WHERE aid = ?");
    if (!$stmt) {
        throw new Exception("Error preparing delete statement: " . $conn->error);
    }
    
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo '<script>alert("Admin deleted successfully."); window.location.href="adminview.php";</script>';
        } else {
            throw new Exception("No admin found with this ID.");
        }
    } else {
        throw new Exception("Error executing delete query: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . ' on line ' . $e->getLine();
}
?>
