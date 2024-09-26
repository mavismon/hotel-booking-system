<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if cart session exists
$cart = $_SESSION['cart'] ?? [];

// Remove an item if requested
if (isset($_POST['remove_index'])) {
    $removeIndex = (int)$_POST['remove_index'];
    if (isset($cart[$removeIndex])) {
        unset($cart[$removeIndex]);
        $_SESSION['cart'] = array_values($cart); // Reindex array after removing
    }
    // Return updated cart page after removing
    header('Location: order.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Orders - Hotel Booking System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .quantity-buttons {
            display: flex;
            align-items: center;
        }
        .quantity-buttons button {
            width: 30px;
            height: 30px;
        }
        .quantity-input {
            width: 60px;
            text-align: center;
            border: 1px solid #ced4da;
            border-radius: .25rem;
        }
        body {
            background-color: rgb(251, 253, 243);
        }
        .custom-container {
            max-width: 1200px; /* Adjust the max-width as needed */
        }
        .img-rounded {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1, h2, h3 {
            font-family: "Arial", sans-serif; /* Change font-family as needed */
        }
        p.lead {
            font-size: 1.25rem; /* Adjust font size for lead paragraph */
        }
        .navbar-brand, .nav-link {
            font-family: "Arial", sans-serif;
            color: #ffffff;
        }
        .navbar {
            background-color: rgb(207, 245, 205);
        }
        .btn {
            background-color: rgb(207, 245, 205);
            color: black;
        }
        .btn:hover {
            background-color: #145a32; /* Darker green on hover */
            color: white;
        }
        .fixed-size {
            width: 100%;
            height: 250px; /* Set a fixed height for all images */
            object-fit: cover; /* Ensure images cover the set height and width */
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <a class="navbar-brand" href="#">
        <span class="brand-name">Hotel Queen</span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="home.html">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
            <li class="nav-item"><a class="nav-link" href="menu.php">Menu</a></li>
            <li class="nav-item"><a class="nav-link" href="room.html">Rooms</a></li>
            <li class="nav-item active"><a class="nav-link" href="orders.php">My Orders</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-5 pt-5">
    <h2 class="text-center">My Order Lists</h2>
    <?php if (!empty($cart)): ?>
    <table class="table">
        <thead>
            <tr>
                <th>Food Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody id="cart-table-body">
            <?php
            $totalAmount = 0;
            foreach ($cart as $index => $item):
                $itemTotal = $item['price'] * $item['quantity'];
                $totalAmount += $itemTotal;
            ?>
            <tr data-index="<?php echo $index; ?>">
                <td><?php echo htmlspecialchars($item['foodName']); ?></td>
                <td>$<?php echo htmlspecialchars($item['price']); ?></td>
                <td>
                    <div class="quantity-buttons">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeQuantity(<?php echo $index; ?>, -1)">-</button>
                        <input type="text" class="quantity-input" id="quantity-<?php echo $index; ?>" value="<?php echo htmlspecialchars($item['quantity']); ?>" readonly />
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeQuantity(<?php echo $index; ?>, 1)">+</button>
                    </div>
                </td>
                <td id="total-<?php echo $index; ?>">$<?php echo number_format($itemTotal, 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Total Amount:</strong></td>
                <td id="grand-total">$<?php echo number_format($totalAmount, 2); ?></td>
            </tr>
        </tfoot>
    </table>
    <div class="text-center">
        <button class="btn btn-primary" data-toggle="modal" data-target="#orderModal">Order Now</button>
    </div>
    <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Place Your Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form id="orderForm" method="POST" action="placeorder.php">
                    <div class="form-group">
                        <label for="room_id">Room ID</label>
                        <input type="text" id="room_id" name="room_id" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <input type="text" id="remarks" name="remarks" class="form-control" optional>
                    </div>
                    <button type="submit" class="btn btn-primary">Place Order</button>
                </form>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>Food Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4" class="text-center">Your cart is empty.</td>
            </tr>
        </tbody>
    </table>
<?php endif; ?>

</div>

<script>
function changeQuantity(index, change) {
    const quantityInput = document.getElementById(`quantity-${index}`);
    let currentQuantity = parseInt(quantityInput.value, 10);

    // If quantity goes to zero, remove item
    if (currentQuantity + change <= 0) {
        removeItem(index);
        return;
    }

    currentQuantity += change;
    quantityInput.value = currentQuantity;
    updateTotals(index, currentQuantity);
}

function updateTotals(index, quantity) {
    const row = document.querySelector(`tr[data-index='${index}']`);
    const price = parseFloat(row.querySelector('td:nth-child(2)').innerText.replace('$', ''));
    const itemTotal = price * quantity;
    
    document.getElementById(`total-${index}`).innerText = `$${itemTotal.toFixed(2)}`;
    updateGrandTotal();
}

function updateGrandTotal() {
    let grandTotal = 0;
    document.querySelectorAll('#cart-table-body tr').forEach(row => {
        const total = parseFloat(row.querySelector('td:nth-child(4)').innerText.replace('$', ''));
        grandTotal += total;
    });
    document.getElementById('grand-total').innerText = `$${grandTotal.toFixed(2)}`;
}

function removeItem(index) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '';
    const hiddenField = document.createElement('input');
    hiddenField.type = 'hidden';
    hiddenField.name = 'remove_index';
    hiddenField.value = index;
    form.appendChild(hiddenField);
    document.body.appendChild(form);
    form.submit();
}
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
