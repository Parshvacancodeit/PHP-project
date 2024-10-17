<?php
require('../connect.php');
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_POST['add_item'])) {
    // Get form data
    $item_name = $_POST['item_name'];
    $product_code = $_POST['product_code'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];
    $min_stock = $_POST['min_stock'];
    $user_email = $_SESSION['email']; // Assuming user email is stored in session

    // Prepare SQL query
    $query = "INSERT INTO inventory (item_name, product_code, quantity, category, min_stock, user_email) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssissi", $item_name, $product_code, $quantity, $category, $min_stock, $user_email);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('Item added successfully!'); window.location.href='inventory.php';</script>";
    } else {
        echo "<script>alert('Error adding item: " . $stmt->error . "'); window.location.href='inventory.php';</script>";
    }

    $stmt->close();
}
?>
