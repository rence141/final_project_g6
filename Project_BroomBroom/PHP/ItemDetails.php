<?php
include('db.php');

// Handle Add, Edit, Delete
if (isset($_POST['add'])) {
    $name = $_POST['item_name'];
    $description = $_POST['item_description'];
    $price = $_POST['item_price'];
    $image = $_FILES['item_image']['name'];

    // Upload image
    move_uploaded_file($_FILES['item_image']['tmp_name'], "uploaded_img/" . $image);

    $query = "INSERT INTO items (item_name, item_description, item_price, item_image) 
              VALUES ('$name', '$description', '$price', '$image')";
    mysqli_query($conn, $query);
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM items WHERE id=$id";
    mysqli_query($conn, $query);
}

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $name = $_POST['item_name'];
    $description = $_POST['item_description'];
    $price = $_POST['item_price'];
    $image = $_FILES['item_image']['name'];

    if ($image != "") {
        move_uploaded_file($_FILES['item_image']['tmp_name'], "uploaded_img/" . $image);
        $query = "UPDATE items SET item_name='$name', item_description='$description', item_price='$price', item_image='$image' WHERE id=$id";
    } else {
        $query = "UPDATE items SET item_name='$name', item_description='$description', item_price='$price' WHERE id=$id";
    }
    mysqli_query($conn, $query);
}

$items = mysqli_query($conn, "SELECT * FROM items");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Details Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #343a40; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        tr:hover { background-color: #ddd; }
        .btn { padding: 8px 15px; background-color: #007bff; color: white; border-radius: 5px; text-decoration: none; }
        .btn:hover { background-color: #0056b3; }
        .delete-btn { background-color: #dc3545; }
        .delete-btn:hover { background-color: #a71d2a; }
    </style>
</head>
<body>

    <h1>Item Details Dashboard</h1>

    <!-- Add New Item Form -->
    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="item_name" placeholder="Item Name" required><br><br>
        <textarea name="item_description" placeholder="Item Description" required></textarea><br><br>
        <input type="number" name="item_price" placeholder="Item Price" required><br><br>
        <input type="file" name="item_image" required><br><br>
        <button type="submit" name="add" class="btn">Add Item</button>
    </form>

    <h2>Items List</h2>
    <table>
        <thead>
            <tr>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Product Description</th>
                <th>Product Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($items)) { ?>
                <tr>
                    <td><img src="uploaded_img/<?php echo $row['ITEM_IMAGE']; ?>" alt="Image" width="100"></td>
                    <td><?php echo $row['ITEM_NAME']; ?></td>
                    <td><?php echo $row['ITEM_DESCRIPTION']; ?></td>
                    <td>$<?php echo $row['ITEM_PRICE']; ?>/-</td>
                    <td>
                        <a href="edit_admin.php" class="btn edit-btn">Edit</a>
                        <a href="Project_BroomBroom/PHP/deleteProducts.php" class="btn delete-btn">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $item = mysqli_query($conn, "SELECT * FROM items WHERE id=$id");
        $data = mysqli_fetch_assoc($item);
        ?>
        <h2>Edit Item</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            <input type="text" name="item_name" value="<?php echo $data['item_name']; ?>" required><br><br>
            <textarea name="item_description" required><?php echo $data['item_description']; ?></textarea><br><br>
            <input type="number" name="item_price" value="<?php echo $data['item_price']; ?>" required><br><br>
            <input type="file" name="item_image"><br><br>
            <button type="submit" name="edit" class="btn">Update Item</button>
            
        </form>
    <?php } ?>
    
</body>
</html>
