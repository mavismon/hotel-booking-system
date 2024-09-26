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

    // Get booking ID
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    // Debugging: Print the ID
    echo "Received Booking ID: " . htmlspecialchars($id) . "<br>";

    if ($id === null || $id <= 0) {
        throw new Exception("Invalid booking ID.");
    }

    // Get the associated room ID from the booking to increase numOfRooms later
    $sqlGetRoom = "SELECT rid FROM booking WHERE bid = ?";
    $stmtGetRoom = $conn->prepare($sqlGetRoom);

    if (!$stmtGetRoom) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }
    
    $stmtGetRoom->bind_param("i", $id);
    $stmtGetRoom->execute();
    $result = $stmtGetRoom->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("Booking ID does not exist.");
    }

    $booking = $result->fetch_assoc();
    $rid = $booking['rid']; // Get the room ID
    $stmtGetRoom->close();

    // Delete the booking
    $stmtDelete = $conn->prepare("DELETE FROM booking WHERE bid = ?");
    if (!$stmtDelete) {
        throw new Exception("Error preparing delete statement: " . $conn->error);
    }
    
    $stmtDelete->bind_param("i", $id);

    if ($stmtDelete->execute()) {
        if ($stmtDelete->affected_rows > 0) {
            // Update the number of rooms by adding +1 to the room with the associated rid
            $sqlUpdateRooms = "UPDATE room SET numOfRooms = numOfRooms + 1 WHERE rid = ?";
            $stmtUpdateRooms = $conn->prepare($sqlUpdateRooms);
            
            if (!$stmtUpdateRooms) {
                throw new Exception("Error preparing update statement: " . $conn->error);
            }

            $stmtUpdateRooms->bind_param("s", $rid);

            if ($stmtUpdateRooms->execute()) {
                echo '<script>alert("Booking deleted successfully and room availability updated."); window.location.href="adminbooking.php";</script>';
            } else {
                throw new Exception("Error updating room availability: " . $stmtUpdateRooms->error);
            }
            
            $stmtUpdateRooms->close();
        } else {
            throw new Exception("No booking found with this ID.");
        }
    } else {
        throw new Exception("Error executing delete query: " . $stmtDelete->error);
    }

    $stmtDelete->close();
    $conn->close();

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . ' on line ' . $e->getLine();
}
?>
