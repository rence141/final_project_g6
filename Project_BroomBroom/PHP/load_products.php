<?php
// Database connection
$host = 'localhost';  // your database host
$dbname = 'sample';   // your database name
$username = 'root';   // your database username
$password = '';       // your database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get search term if present
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

    // Prepare the query
    $query = "SELECT * FROM item WHERE ITEM_NAME LIKE :searchTerm";
    $stmt = $conn->prepare($query);
    $stmt->execute(['searchTerm' => "%$searchTerm%"]);

    // Fetch products and return as JSON
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
