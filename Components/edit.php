<?php
include('../connect.php');
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if (isset($_POST['update'])) {
        $item_name = $_POST['item_name'];
        $product_code = $_POST['product_code'];
        $quantity = $_POST['quantity'];
        $category = $_POST['category'];
        $min_stock = $_POST['min_stock'];
        $user_email = $_SESSION['email'];
        $price = $_POST['price'];  // Add this line to capture price
        $q3 = mysqli_query($conn, "UPDATE inventory SET item_name='$item_name', product_code='$product_code', quantity='$quantity', category='$category', min_stock='$min_stock', price='$price', user_email='$user_email' WHERE id=$id");

        if ($q3) {
            header('Location: inventory.php');
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }

    // Corrected query to fetch the inventory data by id
    $query1 = mysqli_query($conn, "SELECT * FROM inventory WHERE id='$id'");
    $query2 = mysqli_fetch_array($query1);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="../Styles/editinventory.css">
</head>
<body>
<h2>Edit Inventory Item</h2>
<form action="" method="POST">
    <label for="item_name">Item Name:</label>
    <input type="text" id="item_name" name="item_name" required value="<?php echo $query2['item_name']; ?>"><br><br>

    <label for="product_code">Product Code:</label>
    <input type="text" id="product_code" name="product_code" required value="<?php echo $query2['product_code']; ?>"><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" required value="<?php echo $query2['price']; ?>"><br><br>


    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required value="<?php echo $query2['quantity']; ?>"><br><br>

    <label for="category">Category:</label>
    <input type="text" id="category" name="category" value="<?php echo $query2['category']; ?>"><br><br>

    <label for="min_stock">Minimum Stock:</label>
    <input type="number" id="min_stock" name="min_stock" required value="<?php echo $query2['min_stock']; ?>"><br><br>

    <td><input type="reset" name="reset" /></td>
    <td><input type="submit" name="update" value="Update"/></td>
</form>

</body>
</html>
