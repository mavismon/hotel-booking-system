<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT name FROM admin WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        // Fetch the admin's name
        $row = $result->fetch_assoc();
        $name = $row['name'];

        // Valid credentials
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $name; // Store the name in the session

        header("Location: adminview.php"); // Redirect to the admin home page
        exit();
    } else {
        // Invalid credentials
        echo '<script>alert("Invalid Email or Password."); window.location.href="admin.php";</script>';
        exit();
    }

    $stmt->close();
}

$conn->close(); // Close the database connection
?>
