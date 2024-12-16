<?php
session_start(); // Start or resume a session

// Check if the user is logged in (assuming user ID is stored in the session)
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header('Location: process_signin.php');
    exit;
}

// Initialize or retrieve the cart for the user
if (!isset($_SESSION['cart'][$_SESSION['user_id']])) {
    $_SESSION['cart'][$_SESSION['user_id']] = []; // Create empty cart if none exists
}

// Handle adding products to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'] ?? '';
    $item_price = $_POST['item_price'] ?? 0;

    if (!empty($item_name) && $item_price > 0) {
        $found = false;
        // Update cart for the specific user
        foreach ($_SESSION['cart'][$_SESSION['user_id']] as &$cart_item) {
            if ($cart_item['name'] === $item_name) {
                $cart_item['quantity'] += 1;
                $cart_item['total_price'] = $cart_item['price'] * $cart_item['quantity']; // Update total price
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][$_SESSION['user_id']][] = [
                'name' => $item_name,
                'price' => $item_price,
                'quantity' => 1,
                'total_price' => $item_price // Set the initial total price
            ];
        }
    }
}

// Display the cart for the specific user
function displayCart() {
    if (!empty($_SESSION['cart'][$_SESSION['user_id']])) {
        echo "<h2>Your Cart</h2><ul>";
        $total_cart_value = 0;
        foreach ($_SESSION['cart'][$_SESSION['user_id']] as $cart_item) {
            echo "<li>{$cart_item['name']} - $" . number_format($cart_item['price'], 2) . 
                 " (Quantity: {$cart_item['quantity']}) - Total: $" . number_format($cart_item['total_price'], 2) . "</li>";
            $total_cart_value += $cart_item['total_price'];
        }
        echo "</ul>";
        echo "<h3>Total Cart Value: $" . number_format($total_cart_value, 2) . "</h3>";
        echo "<form action='checkout.php' method='POST'><button class='checkout-btn' type='submit'>Proceed to Checkout</button></form>";
    } else {
        echo "<p>Your cart is empty.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
        /* Add some basic styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1, h2, h3 {
            text-align: center;
        }
        .checkout-btn {
            margin-top: 20px;
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .checkout-btn:hover {
            background-color: #218838;
        }
        ul {
            list-style-type: none;
            padding: 0;
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        li:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <h1>Your Shopping Cart</h1>
    <?php displayCart(); ?>
</body>
</html>
