<?php
require('../connect.php');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle form submission for adding a new inventory item
if (isset($_POST['add_item'])) {
    // Get values from the request
    $item_name = $_REQUEST['item_name'];
    $product_code = $_REQUEST['product_code'];
    $quantity = $_REQUEST['quantity'];
    $category = $_REQUEST['category'];
    $min_stock = $_REQUEST['min_stock'];
    $user_email = $_SESSION['email']; // Assuming email is stored in session
    $price = $_REQUEST['price'];

    // Check if category is empty before inserting
    if (empty($category)) {
        echo "<p>Category cannot be empty!</p>";
    } else {
        // Direct insert query (not recommended for production without validation/sanitization)
        $insert_query = "INSERT INTO inventory (item_name, product_code, quantity, category, min_stock, price, user_email) VALUES ('$item_name', '$product_code', $quantity, '$category', $min_stock, $price, '$user_email')";
        
        // Execute the insert query
        if (mysqli_query($conn, $insert_query)) {
            header('Location: inventory.php'); // Redirect after success
            exit();
        } else {
            echo "<p>Error adding item: " . mysqli_error($conn) . "</p>";
        }
    }
}

// Handle delete action
if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];

    // Delete query
    $delete_query = "DELETE FROM inventory WHERE id=$id";
    
    if (mysqli_query($conn, $delete_query)) {
        header('Location: inventory.php'); // Redirect after deletion
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../Styles/inventory.css">
    <link rel="stylesheet" href="../navbar.css">
</head>
<body>
<header>
    <div class="nav-container">
        <a href="../index.php">
            <img src="../assets/logo.png" alt="Logo" id="img">
        </a>
        <div class="Link-sec">
            <ul>
                <a href="./billing.php"><li>Billing</li></a>
                <a href="./inventory.php"><li>Inventory</li></a>
                <a href="./report.php"><li>Report</li></a>
                <a href="./settings.php"><li>Settings</li></a>
            </ul>
        </div>
        <div class="Login-button-sec">
        <?php
        if (isset($_SESSION['logged-in'])) {
            echo "
            <div class='logout-box'>
                {$_SESSION['username']}
                <a href='../logout.php'><button>Logout</button></a>
            </div>";
        } else {
            echo "<a href='./register.php'><button>Register</button></a>";
            echo "<a href='./login.php'><button>Login</button></a>";
        }
        ?>
        </div>
    </div>
</header>

<div class="inventory-container">
    <h2>Add New Item to Inventory</h2>
    <form action="" method="POST" class="inventory-form">
        <label for="item_name">Item Name:</label>
        <input type="text" id="item_name" name="item_name" required>

        <label for="product_code">Product Code:</label>
        <input type="text" id="product_code" name="product_code" required>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>

        <label for="category">Category:</label>
        <input type="text" id="category" name="category" required>

        <label for="min_stock">Minimum Stock:</label>
        <input type="number" id="min_stock" name="min_stock" required>

        <button type="submit" name="add_item">Add Item</button>
    </form>

    <hr>

    <h2>Your Inventory</h2>
    <?php
    if (isset($_SESSION['logged-in'])) {
        $user_email = $_SESSION['email'];

        // Fetch inventory
        $query = "SELECT * FROM inventory WHERE user_email = '$user_email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<table class='inventory-table'>
                <tr>
                    <th>Item Name</th>
                    <th>Product Code</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Category</th>
                    <th>Minimum Stock</th>
                    <th>Modify</th>
                    <th>Delete</th>
                </tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "
                <tr>
                    <td>{$row['item_name']}</td>
                    <td>{$row['product_code']}</td>
                    <td>{$row['price']}</td>
                    <td>{$row['quantity']}</td>
                    <td>{$row['category']}</td>
                    <td>{$row['min_stock']}</td>
                    <td><a href='edit.php?id=".$row['id']."'><button class='edit-btn'>Edit</button></a></td>
                    <td><a href='?id=".$row['id']."&action=delete'><button class='delete-btn'>Delete</button></a></td>
                </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No items in your inventory yet.</p>";
        }
    } else {
        echo "<p>You need to be logged in to view and manage your inventory.</p>";
    }
    ?>
</div>

</body>
</html>
