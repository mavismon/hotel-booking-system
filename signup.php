
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
} else {
    echo "Database connected successfully!";
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name']; 
    $email = $_POST['email'];
    $password =$_POST['password']; // Hash the password
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO admin (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $name, $email, $password, $phone, $address);

    if ($stmt->execute()) {
        echo "Data inserted successfully!";
        $_SESSION['loggedin'] = true;
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        header("Location: adminview.php");
        exit();
    } else {
        echo '<script>alert("Error: ' . $stmt->error . '");</script>';
    }
    

    $stmt->close();
}

$conn->close(); // Close the database connection
?>
