<?php
session_start(); // Start or resume a session

// Initialize cart if not already done
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to apply voucher discount
function applyVoucherDiscount($voucher_code) {
    // Define valid vouchers and their respective discounts
    $valid_vouchers = [
        "DISCOUNT10" => 0.10, 
        "SAVE20" => 0.20,     
        "KYLARUPOK" => 0.99,
        "JAMESBUDGETMEAL" => 0.90,
        "SELWYNBUDGETMEAL" => 0.80,
    ];

    // Check if the voucher code is valid
    if (array_key_exists($voucher_code, $valid_vouchers)) {
        return $valid_vouchers[$voucher_code]; // Return the discount percentage
    } else {
        return 0; // No discount if the voucher is invalid
    }
}

// Handle adding products to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if it's an add to cart request
    if (isset($_POST['item_name']) && isset($_POST['item_price'])) {
        $item_name = $_POST['item_name'] ?? '';
        $item_price = $_POST['item_price'] ?? 0;

        if (!empty($item_name) && $item_price > 0) {
            $found = false;
            foreach ($_SESSION['cart'] as &$cart_item) {
                if ($cart_item['name'] === $item_name) {
                    $cart_item['quantity'] += 1;
                    $cart_item['total_price'] = $cart_item['price'] * $cart_item['quantity']; // Update total price
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $_SESSION['cart'][] = [
                    'name' => $item_name,
                    'price' => $item_price,
                    'quantity' => 1,
                    'total_price' => $item_price // Set the initial total price
                ];
            }
        }
    }

    // Handle voucher code
    if (isset($_POST['voucher_code'])) {
        $voucher_code = $_POST['voucher_code'];
        $_SESSION['voucher_code'] = $voucher_code; // Store the voucher code in session
        $discount_percentage = applyVoucherDiscount($voucher_code);
        $_SESSION['voucher_discount'] = $discount_percentage; // Store discount in session
    }

    // Check if it's a clear cart request
    if (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = []; // Clear the cart
    }
}

// Display the cart
function displayCart() {
    if (!empty($_SESSION['cart'])) {
        echo "<h1>Your Cart</h1>";
        echo "<div class='cart-table'>";
        $total_cart_value = 0; // To store the total value of the cart
        foreach ($_SESSION['cart'] as $cart_item) {
            $item_price = floatval($cart_item['price']); // Ensure it's a float
            $total_price = floatval($cart_item['total_price']); // Ensure it's a float

            echo "<div class='cart-item'>";
            echo "<div class='cart-item-name'>{$cart_item['name']}</div>";
            echo "<div class='cart-item-price'>₱" . number_format($item_price, 2) . "</div>"; // PHP symbol
            echo "<div class='cart-item-quantity'>Quantity: {$cart_item['quantity']}</div>";
            echo "<div class='cart-item-total'>Total: ₱" . number_format($total_price, 2) . "</div>"; // PHP symbol
            echo "</div>";
            $total_cart_value += $total_price; // Add each item's total price to the cart total
        }

        // Apply voucher discount if applicable
        if (isset($_SESSION['voucher_code']) && $_SESSION['voucher_code'] != "") {
            $voucher_code = $_SESSION['voucher_code'];
            $discount_percentage = isset($_SESSION['voucher_discount']) ? 
            $_SESSION['voucher_discount'] : 0; // Default to 0 if no discount is set


            if ($discount_percentage > 0) {
                $discount_value = $total_cart_value * $discount_percentage;
                $total_cart_value -= $discount_value; // Apply the discount to the total
                echo "<p>Voucher Applied: {$voucher_code} (" . number_format($discount_percentage * 100, 2) . "% off)</p>";
                echo "<p><strong>Total after Discount: ₱" . number_format($total_cart_value, 2) . "</strong></p>";
            } else {
                echo "<p><strong>No valid voucher applied.</strong></p>";
            }
        }

        echo "<h3>Total Cart Value: ₱" . number_format($total_cart_value, 2) . "</h3>"; // Display total value of the cart in PHP
        echo "<form action='checkout.php' method='POST'>
                <button class='checkout-btn' type='submit'>Proceed to Checkout</button>
              </form>";
    } else {
        echo "<p>Your cart is empty.</p>";
    }

    // Clear Cart Button
    echo "<form action='' method='POST'>
            <button class='clear-btn' type='submit' name='clear_cart' value='1'>Clear Cart</button>
          </form>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
        /* Global Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f1f1f1;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        /* Navbar Styling */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(45deg, #6a11cb, #2575fc); /* Gradient background */
            color: white;
            padding: 15px 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            text-decoration: none;
            color: #fff;
            letter-spacing: 2px;
        }

        .navbar-links {
            display: flex;
            gap: 30px;
        }

        .navbar-links a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .navbar-links a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffdd57;
        }

        /* Cart Section */
        .cart-table {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 20px;
        }

        .cart-item {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .cart-item-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .cart-item-price,
        .cart-item-quantity,
        .cart-item-total {
            font-size: 16px;
            color: #777;
            margin-bottom: 8px;
        }

        .checkout-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2575fc;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .checkout-btn:hover {
            background-color: #6a11cb;
        }

        .clear-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .clear-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <header>
        <a href="#home" class="logo">BroomBroom</a>
        <div class="navbar-links">
            <a href="../PHP/ecommerce_user.php">Home</a>
            <a href="../PHP/cart.php">Cart</a>
        </div>
    </header>

    <!-- Voucher Input Section -->
    <div>
        <form action="" method="POST">
            <label for="voucher_code">Enter Voucher Code:</label>
            <input type="text" id="voucher_code" name="voucher_code" placeholder="Enter code here">
            <button type="submit">Apply Voucher</button>
        </form>
    </div>

    <!-- Cart Section -->
    <?php displayCart(); ?>

</body>
</html>
