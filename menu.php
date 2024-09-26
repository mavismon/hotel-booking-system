<?php
$host = "localhost";
$user = "root";
$passwd = "";
$database = "hotel";
$table_name = "food";

// Connect to the database
$connect = mysqli_connect($host, $user, $passwd, $database);
if (!$connect) {
    die("Could not connect to the database: " . mysqli_connect_error());
}

// Fetch data from the database
$query = "SELECT * FROM $table_name";
$result = mysqli_query($connect, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($connect));
}

mysqli_close($connect);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menu - Hotel Booking System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            font-family: "Arial", sans-serif;
            background-color: rgb(251, 253, 243);
        }
        .navbar {
            background-color: rgb(207, 245, 205);
        }
        .navbar-brand,
        .nav-link {
            color: #000000;
        }
        .nav-link:hover {
            color: #f8c41c;
        }
        .header-section {
            background: url("./images/contact.jpg") no-repeat center center;
            background-size: cover;
            padding: 180px 0;
            text-align: center;
            color: rgb(120, 59, 116);
            height: fit-content;
        }
        .header-section h2,
        .header-section p {
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            padding: 10px; /* Add some padding */
            color: #fff; /* White text color */
        }
        .contact-form-section {
            padding: 60px 0;
            background-color: rgb(251, 253, 243);
        }
        .contact-form-section .form-control {
            border-radius: 0;
        }
        .contact-info {
            padding: 60px 0;
        }
        .contact-info h3 {
            margin-bottom: 30px;
        }
        .contact-info .info-item {
            margin-bottom: 20px;
            font-size: 18px;
            margin-left: 20px;
        }
        .contact-info .info-item i {
            color: #000000;
            margin-right: 20px;
        }
        .footer {
            background-color: #333;
            color: white;
            padding: 60px 0;
        }
        .footer a {
            color: white;
            text-decoration: none;
        }
        .footer a:hover {
            color: #f8c41c;
        }
        .custom-btn {
            transition: background-color 0.3s ease, color 0.3s ease; /* Smooth transition for background and text color */
        }
        .btn-book {
            transition: background-color 0.3s ease, color 0.3s ease;
            background-color: rgb(207, 245, 205);
            font-size: large;
            font-weight: 500;
        }
        .custom-btn:active {
            background-color: rgb(207, 245, 205); /* Change background color when clicked */
            color: black !important; /* Change text color to black when clicked */
        }
        .menu-items {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .menu-item {
            width: calc(23% - 20px); /* 23% width with 10px margin for better spacing */
            margin: 20px;
            padding: 20px;
            background-color: #FCF7EC;
            border: 1px solid #ccc;
            border-radius: 10px;
            text-align: center;
            max-width: 300px;
        }
        .menu-item img {
            width: 230px;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .menu-item h3 {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }
        .menu-item p {
            margin: 5px 0;
            font-size: 14px;
            color: #888;
        }
        .menu-item span {
            font-weight: bold;
        }
        .quantity-selector {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        .quantity-selector input {
            width: 50px;
            text-align: center;
            margin: 0 5px;
        }
        .btn {
            padding: 5px 10px;
            background-color: #207245; /* Original green */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #145a32; /* Darker green on hover */
            color:white;
        }
        .btn-increment, .btn-decrement {
            width: 30px;
            height: 30px;
            font-size: 16px;
            line-height: 1;
            text-align: center;
        }
        .btn-add-to-cart {
            margin-top: 10px;
            background-color: rgb(207, 245, 205); /* Bootstrap success color */
            color: black;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light fixed-top ">
    <a class="navbar-brand d-flex align-items-center" href="#">
        <i class="bi bi-house-door icon-style"></i>
        <span class="brand-name">Hotel Queen</span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="home.html">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about.html">About</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="menu.php">Menu</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="room.html">Rooms</a>
            </li>
            <li class="nav-item">
    <a class="nav-link" href="order.php" id="myOrdersLink">My Orders</a>
</li>

        </ul>
    </div>
</nav>

<!-- Content section -->
<div class="content mt-5 pt-5 py-5">
    <h1 class="text-center">Explore Our Menu Here</h1>
    <ul class="menu-items">
    <?php
        if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $pname = htmlspecialchars($row['foodname']);
                $price = htmlspecialchars($row['price']);
                $photo = htmlspecialchars($row['photo']);

                // Define the image path
                $imagePath = 'images/' . $photo;

                // Check if the image file exists; use default if not
                if (!file_exists($imagePath) || empty($photo)) {
                    $imagePath = "images/default.jpg";
                }
    
                echo "<li class='menu-item'>";
                echo "<img src='" . $imagePath . "' alt='" . $pname . "'>";
                echo "<h3>" . $pname . "</h3>";
                echo "<p><span>Price:</span> $" . $price . "</p>";
    
                // Add input number with + and - buttons
                echo "<div class='quantity-selector'>";
                echo "<button class='btn btn-decrement' onclick='decrementQuantity(this)'>-</button>";
                echo "<input type='number' class='food-quantity' value='1' min='1'>";
                echo "<button class='btn btn-increment' onclick='incrementQuantity(this)'>+</button>";
                echo "</div>";
    
                // Add to cart button
                echo "<button class='btn btn-add-to-cart' onclick='addToCart(\"" . $pname . "\", " . $price . ", this)'>Add to Cart</button>";
    
                echo "</li>";
            }
        } else {
            echo "<p>Query failed: " . mysqli_error($connect) . "</p>";
        }
    ?>
    </ul>
</div>
<script>
    // Function to increase quantity
    function incrementQuantity(button) {
        const input = button.previousElementSibling;
        input.value = parseInt(input.value) + 1;
    }

    // Function to decrease quantity
    function decrementQuantity(button) {
        const input = button.nextElementSibling;
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }

    // Function to handle adding to cart


    function addToCart(foodName, price, button) {
        console.log('Add to Cart clicked');
        const quantity = button.previousElementSibling.querySelector('.food-quantity').value;
        
        // Add to cart via AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'addtocart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = xhr.responseText;
                alert(response);
                
                // Mark "My Orders" as active
                const myOrdersLink = document.getElementById('myOrdersLink');
                myOrdersLink.classList.add('active');
            } else {
                alert('Failed to add to cart. Status: ' + xhr.status);
            }
        };
        xhr.onerror = function() {
            alert('Request error.');
        };
        xhr.send(`foodname=${encodeURIComponent(foodName)}&price=${encodeURIComponent(price)}&quantity=${encodeURIComponent(quantity)}`);
    }



</script>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
