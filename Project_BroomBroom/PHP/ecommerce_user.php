    <?php
    // Start the session to access session variables
    session_start();

    // Check if the user is logged in


    // Get the user's email from the session (set during login)
    $email = $_SESSION['email'];

    // Include the database connection
    include_once "db.php";  // Update with your actual path

    // Query to retrieve user's first and last name based on email
    $sql = "SELECT first_name, last_name FROM signup WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists in the database
    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();
        $userName = $user['first_name'] . " " . $user['last_name'];  // Concatenate first and last name
    } else {
        // If no user found, set a default name
        $userName = "User";
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Page</title>
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

            /* Products Section */
            .content {
                margin-top: 40px;
            }

            h1 {
                text-align: center;
                margin-bottom: 20px;
            }

            .welcome-message {
                font-size: 20px;
                text-align: center;
                margin-bottom: 30px;
                color: #2575fc;
            }

            .product-table {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                justify-content: space-evenly;
            }

            .product-card {
                background-color: #fff;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease-in-out;
                width: 100%;
                max-width: 300px;
            }

            .product-card:hover {
                transform: translateY(-10px);
            }

            .product-card img {
                width: 100%;
                height: auto;
                object-fit: cover;
            }

            .product-details {
                padding: 15px;
                text-align: center;
            }

            .product-name {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .product-description {
                font-size: 14px;
                color: #777;
                margin-bottom: 15px;
            }

            .product-price {
                font-size: 20px;
                color: #2575fc;
                margin-bottom: 15px;
            }

            .add-to-cart-btn {
                background-color: #2575fc;
                color: white;
                border: none;
                padding: 10px 20px;
                font-size: 16px;
                border-radius: 6px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .add-to-cart-btn:hover {
                background-color: #6a11cb;
            }

            /* Responsive Styling */
            @media screen and (max-width: 768px) {
                .product-table {
                    justify-content: center;
                }
            }

            @media screen and (max-width: 480px) {
                .product-table {
                    flex-direction: column;
                    align-items: center;
                }
            }

        </style>
    </head>
    <body>

        <!-- Navbar -->
        <header>
            <a href="#home" class="logo">BroomBroom</a>
            <div class="navbar-links">
                <a href="/Project_BroomBroom/redirect/landing_page.html">Home</a>
                <a href="/Project_BroomBroom/HTML/website.html">Official Website</a>
                <a href="/Project_BroomBroom/redirect/AboutUs.html">About Us</a>
                <a href="/Project_BroomBroom/redirect/Cart.html">Cart</a>
            </div>
        </header>

        <!-- Welcome Message -->
        <div class="welcome-message">
            <h2>Welcome, <?php echo htmlspecialchars($userName); ?>!</h2>
            <p>We're glad to have you back. Explore our latest products below.</p>
        </div>

        <!-- Products Section -->
        <div class="content">
            <h1>Explore Our Products</h1>
            <p>Your personalized shopping experience awaits. Browse through our exclusive products!</p>

            <div class="product-table">
                <!-- Example Product 1 -->
                <div class="product-card">
                    <img src="/img/monster.jpeg" alt="Product Image">
                    <div class="product-details">
                        <div class="product-name">Monster</div>
                        <div class="product-description">High-quality product for daily use.</div>
                        <div class="product-price">PHP 8999</div>
                        <form action="../PHP/addtocart.php" method="POST">
                            <input type="hidden" name="item_name" value="Monster">
                            <input type="hidden" name="item_price" value="8999">
                            <button type="submit" class="add-to-cart-btn">Add to cart</button>
                        </form>
                    </div>
                </div>

                <!-- Example Product 2 -->
                <div class="product-card">
                    <img src="/img/Moto.jpeg" alt="Product Image">
                    <div class="product-details">
                        <div class="product-name">Moto V3</div>
                        <div class="product-description">Durable and stylish product for various purposes.</div>
                        <div class="product-price">PHP 990,000.00</div>
                        <form action="../PHP/addtocart.php" method="POST">
                            <input type="hidden" name="item_name" value="Moto V3">
                            <input type="hidden" name="item_price" value="990000.00">
                            <button type="submit" class="add-to-cart-btn">Add to cart</button>
                        </form>
                    </div>
                </div>

                <!-- Example Product 3 -->
                <div class="product-card">
                    <img src="/img/R6.jpeg" alt="Product Image">
                    <div class="product-details">
                        <div class="product-name">Helix R6</div>
                        <div class="product-description">Eco-friendly and sustainable product.</div>
                        <div class="product-price">PHP 1,000,000.00</div>
                        <form action="../PHP/addtocart.php" method="POST">
                            <input type="hidden" name="item_name" value="Helix R6">
                            <input type="hidden" name="item_price" value="1000000.00">
                            <button type="submit" class="add-to-cart-btn">Add to cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </body>
    </html>
