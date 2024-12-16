<?php
session_start();
include "db.php"; // Ensure this file contains a valid database connection setup

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sample"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize total cart value
$total_cart_value = 0;
$discount_percentage = isset($_SESSION['voucher_discount']) ? $_SESSION['voucher_discount'] : 0; // Get the discount from session

// Calculate the total value of the cart
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $cart_item) {
        $total_cart_value += $cart_item['price'] * $cart_item['quantity'];
    }
}

// Apply the discount if available
$discount_value = $total_cart_value * $discount_percentage;
$total_after_discount = $total_cart_value - $discount_value;

// Handle checkout process (if POST request is made)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['checkout'])) {
    // Get the total price after discount from the form
    $total_after_discount_input = $_POST['total_after_discount'];

    // Insert the cart items into the checkout table
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $cart_item) {
            $item_name = $cart_item['name'];
            $item_price = $cart_item['price'];
            $item_quantity = $cart_item['quantity'];
            $voucher_code = isset($_SESSION['voucher_code']) ? $_SESSION['voucher_code'] : NULL; // Get voucher code
            $voucher_price = isset($_SESSION['voucher_discount']) ? $_SESSION['voucher_discount'] : 0; // Get voucher discount percentage

            // If voucher code exists, mark as "applied"
            $voucher_status = $voucher_code ? "applied" : NULL;

            // Set the current timestamp for checkout_date
            $checkout_date = date("Y-m-d H:i:s"); // Explicitly setting the current timestamp

            // Prepare the SQL query with the correct column names
            $stmt = $conn->prepare("INSERT INTO checkout (item_name, item_quantity, item_price, checkout_date, VOUCHERS, voucher_price, total_price) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");

            // Correct type specifiers to match the bind variables
            $stmt->bind_param("sidsdss", $item_name, $item_quantity, $item_price, $checkout_date, $voucher_status, $voucher_price, $total_after_discount_input);
            
            // Execute the query
            if (!$stmt->execute()) {
                echo "Error: " . $stmt->error . "<br>";
            }
            $stmt->close();
        }

        // Clear the cart and voucher after successful checkout
        unset($_SESSION['cart']);
        unset($_SESSION['voucher_discount']); // Clear applied voucher discount
        unset($_SESSION['voucher_code']); // Clear voucher code
        echo "<p>Order placed successfully! Redirecting to our ecommerce store...</p>";
        header("Refresh: 3; url=../PHP/ecommerce_user.php");
        exit(); // Ensure the script stops after the redirect
    } else {
        echo "<p>Your cart is empty. Please add items before proceeding to checkout.</p>";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../CSS/checkout.css"> <!-- Link to your CSS file -->
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
        }

        /* Checkout Container */
        .checkout-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 80%;
            max-width: 900px;
        }

        .checkout-container h1 {
            text-align: center;
            margin-bottom: 40px;
        }

        /* Cart Summary */
        .cart-summary {
            margin-bottom: 40px;
        }

        .cart-summary h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #333;
        }

        .cart-summary table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .cart-summary th, .cart-summary td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .cart-summary th {
            background-color: #f7f7f7;
        }

        /* Order Summary */
        .order-summary {
            margin-bottom: 40px;
        }

        .order-summary h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #333;
        }

        .order-summary p {
            font-size: 1.1rem;
            margin: 10px 0;
        }

        .order-summary p span {
            font-weight: bold;
        }

        /* Checkout Form */
        .checkout-form {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .checkout-form button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            font-size: 1.1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .checkout-form button:hover {
            background-color: #45a049;
        }

        .checkout-form button:active {
            background-color: #388e3c;
        }

        /* Media Queries */
        @media (max-width: 768px) {
            .checkout-container {
                width: 95%;
            }

            .cart-summary table, .order-summary p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

    <div class="checkout-container">
        <h1>Checkout</h1>
        
        <!-- Display the items in the cart -->
        <div class="cart-summary">
            <h3>Your Cart</h3>
            <table>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
                <?php
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $cart_item) {
                        echo "<tr>";
                        echo "<td>" . $cart_item['name'] . "</td>";
                        echo "<td>" . $cart_item['quantity'] . "</td>";
                        echo "<td>" . number_format($cart_item['price'], 2) . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>

        <!-- Display the total and voucher details -->
        <div class="order-summary">
            <h3>Order Summary</h3>
            <p>Total Price: <?php echo number_format($total_cart_value, 2); ?></p>
            <p>Discount Applied: <?php echo $discount_percentage * 100 . '%'; ?></p>
            <p>Voucher Code: <?php echo isset($_SESSION['voucher_code']) ? $_SESSION['voucher_code'] : 'None'; ?></p>
            <p>Total After Discount: <?php echo number_format($total_after_discount, 2); ?></p>
        </div>

        <!-- Checkout Form -->
        <div class="checkout-form">
            <form action="checkout.php" method="POST">
                <input type="hidden" name="total_after_discount" value="<?php echo $total_after_discount; ?>"> <!-- Hidden input for total after discount -->
                <button type="submit" name="checkout">Complete Checkout</button>
            </form>
        </div>

    </div>

</body>
</html>
