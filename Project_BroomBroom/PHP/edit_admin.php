<?php
@include 'db.php';

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    // Step 1: Fetch the item details from the 'items' table for editing
    $select = mysqli_query($conn, "SELECT * FROM items WHERE id = '$id'");
    $product = mysqli_fetch_assoc($select);

    if (!$product) {
        echo "Item not found.";
        exit;
    }

    // Step 2: Handle form submission for editing the item
    if (isset($_POST['update'])) {
        $item_name = $_POST['item_name'];
        $item_description = $_POST['item_description'];
        $item_price = $_POST['item_price'];
        $item_image = $_FILES['item_image']['name'];

        // Handle image upload
        if ($item_image) {
            // Validate image upload (only allow specific types, e.g., jpg, png)
            $allowed_extensions = ['jpg', 'jpeg', 'png'];
            $file_extension = pathinfo($item_image, PATHINFO_EXTENSION);

            if (in_array($file_extension, $allowed_extensions)) {
                move_uploaded_file($_FILES['item_image']['tmp_name'], "uploaded_img/" . $item_image);

                // Delete old image from the server if it exists
                $old_image = "uploaded_img/" . $product['ITEM_IMAGE'];
                if (file_exists($old_image)) {
                    unlink($old_image);
                }
            } else {
                echo "Invalid file type. Only JPG, JPEG, and PNG are allowed.";
                exit;
            }
        } else {
            $item_image = $product['ITEM_IMAGE']; // Keep old image if no new image is uploaded
        }

        // Step 3: Update the item in the 'items' table
        $update_item_query = "UPDATE UpdateItems SET ITEM_NAME='$item_name', ITEM_DESCRIPTION='$item_description', ITEM_PRICE='$item_price', ITEM_IMAGE='$item_image' WHERE id='$id'";
        if (mysqli_query($conn, $update_item_query)) {
            // Step 4: Insert the updated data into the 'UpdatedItems' table
            $insert_update_query = "INSERT INTO UpdatedItems (ITEM_NAME, ITEM_DESCRIPTION, ITEM_PRICE, ITEM_IMAGE, action) 
                                    VALUES ('$item_name', '$item_description', '$item_price', '$item_image', 'Updated')";
            mysqli_query($conn, $insert_update_query);

            // Redirect to ItemDetails.php page after successful update
            header('Location: Project_BroomBroom/PHP/ItemDetails.php');
            exit(); // Ensure no further code is executed after the redirect
        } else {
            echo "Failed to update the item.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        input[type="file"] {
            padding: 5px;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }

        img {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Item</h1>

    <!-- Edit Form -->
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="item_name" value="<?php echo isset($product['ITEM_NAME']) ? $product['ITEM_NAME'] : ''; ?>" placeholder="Item Name" required><br>

        <textarea name="item_description" placeholder="Item Description" required><?php echo isset($product['ITEM_DESCRIPTION']) ? $product['ITEM_DESCRIPTION'] : ''; ?></textarea><br>

        <input type="number" name="item_price" value="<?php echo isset($product['ITEM_PRICE']) ? $product['ITEM_PRICE'] : ''; ?>" placeholder="Item Price" required><br>

        <div class="form-group">
            <label>Current Image:</label>
            <img src="uploaded_img/<?php echo isset($product['ITEM_IMAGE']) ? $product['ITEM_IMAGE'] : ''; ?>" alt="Item Image">
        </div>

        <input type="file" name="item_image" placeholder="Choose a new image"><br>

        <button type="submit" name="update" class="btn">Update Item</button>
    </form>
</div>

</body>
</html>
