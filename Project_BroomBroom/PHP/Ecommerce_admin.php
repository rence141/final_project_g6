<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General Body Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Navbar Styling */
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

        /* Content Section */
        .content {
            padding: 30px;
            margin-top: 60px;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 30px;
            color: #343a40;
        }

        /* Portfolio Section Styling */
        .portfolio {
            margin-top: 40px;
        }

        .portfolio h2 {
            text-align: center;
            font-size: 28px;
            color: #343a40;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .portfolio-items {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            justify-items: center;
        }

        /* Portfolio Item Card Styling */
        .portfolio-item {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .portfolio-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .portfolio-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 2px solid #ddd;
        }

        .portfolio-item h3 {
            margin: 15px;
            font-size: 20px;
            color: #333;
        }

        .portfolio-item p {
            margin: 0 15px 15px;
            color: #555;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .navbar {
                flex-direction: column;
                text-align: center;
            }

            .portfolio h2 {
                font-size: 24px;
            }

            .portfolio-items {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media screen and (max-width: 480px) {
            .portfolio-items {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <div class="navbar">
        <h2>Admin Dashboard</h2>
        <div>
            <a href="/Project_BroomBroom/PHP/ItemDetails.php">Item Details</a>
            <a href="/Project_BroomBroom/PHP/checkoutList_admin.php">Checkout List</a>
            <a href="/Project_BroomBroom/redirect/signin.html">Logout</a>
        </div>
    </div>

    <div class="content">
        <h1>Admin Portfolio</h1>

        <!-- Portfolio Section -->
        <div class="portfolio">
            <h2>Our Recent Projects</h2>
            <div class="portfolio-items">
                <div class="portfolio-item">
                    <img src="/img/scrammbler.jpeg" alt="Project 1">
                    <h3>Scrambbler Game</h3>
                    <p>This project is a classic word scrambler game built with JavaScript and CSS to improve problem-solving skills.</p>
                </div>
                <div class="portfolio-item">
                    <img src="/img/monster.jpeg" alt="Project 2">
                    <h3>Monster World</h3>
                    <p>Explore the monster world! A fun and interactive game created using HTML5, CSS, and JavaScript.</p>
                </div>
                <div class="portfolio-item">
                    <img src="/img/Moto.jpeg" alt="Project 3">
                    <h3>Moto Racing</h3>
                    <p>A thrilling moto racing game developed with advanced JavaScript functionalities and beautiful CSS animations.</p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
