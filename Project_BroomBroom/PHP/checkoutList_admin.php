<?php
// Start the session
session_start();

// Include the database connection
include_once "db.php";

// Check if the user is logged in and is an admin
if (!isset($_SESSION['UserType']) || $_SESSION['UserType'] !== 'Admin') {
    // If not logged in or not an admin, redirect to login page
    header("Location: /Project_BroomBroom/redirect/signin.html");
    exit();
}

// Fetch checkout orders from the database (including voucher_price and total_price)
$sql = "SELECT checkout.item_name, checkout.ITEM_PRICE, checkout.ITEM_QUANTITY, checkout.voucher_price, checkout.total_price, checkout.checkout_date 
        FROM checkout
        ORDER BY checkout.checkout_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Checkout Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #343a40;
            color: white;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar h2 {
            margin: 0;
            font-size: 26px;
            font-weight: 600;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            border-radius: 5px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .navbar a:hover {
            background-color: #0056b3;
        }

        .content {
            padding: 30px;
            margin-top: 60px;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 30px;
            color: #343a40;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #343a40;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:hover td {
            background-color: #f1f1f1;
        }

    </style>
</head>

<body>

    <!-- Navbar -->
    <div class="navbar">
        <h2>Admin Dashboard</h2>
        <div>
            <a href="/Project_BroomBroom/PHP/ItemDetails.php">Item Details</a>
            <a href="/Project_BroomBroom/redirect/signin.html">Logout</a>
        </div>
    </div>

    <div class="content">
        <h1>Checkout Orders</h1>

        <!-- Display Checkout Orders in a Table -->
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Voucher Price</th>
                    <th>Total Price</th>
                    <th>Checkout Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if there are any results
                if ($result->num_rows > 0) {
                    // Loop through and display each checkout order
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['item_name']) . "</td>
                                <td>" . number_format($row['ITEM_PRICE'], 2) . "</td>
                                <td>" . htmlspecialchars($row['ITEM_QUANTITY']) . "</td>
                                <td>" . number_format($row['voucher_price'], 2) . "</td>
                                <td>" . number_format($row['total_price'], 2) . "</td>
                                <td>" . htmlspecialchars($row['checkout_date']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No orders found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>

<?php
// Close the database connection
$conn->close();
?>
