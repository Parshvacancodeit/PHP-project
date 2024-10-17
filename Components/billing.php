<?php
require('../connect.php'); // Ensure this file connects to your database
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize bill items in session if not set
if (!isset($_SESSION['billItems'])) {
    $_SESSION['billItems'] = [];
}

// Debugging: Check session status
if (!isset($_SESSION['logged-in']) || !isset($_SESSION['email'])) {
    echo "User is not logged in or email is not set.";
    exit; // Stop further execution
}

// Handle customer data submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['customer_name'])) {
    // Retrieve customer name and contact from POST
    $customer_name = trim($_POST['customer_name']);
    $customer_contact = trim($_POST['customer_contact']);
    
    // Store customer data in session
    $_SESSION['customer_name'] = $customer_name;
    $_SESSION['customer_contact'] = $customer_contact;
}

// Handle adding items to the bill
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_code'])) {
    $product_code = trim($_POST['product_code']);
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    $user_email = $_SESSION['email']; // Ensure this is set
    
    // Check if customer name and contact are set
    if (empty($_SESSION['customer_name']) || empty($_SESSION['customer_contact'])) {
        echo "Please enter customer details before adding items.<br>";
    } else {
        // Fetch product details for the logged-in user
        $stmt = $conn->prepare("SELECT item_name, price, quantity FROM inventory WHERE product_code = ? AND user_email = ?");
        $stmt->bind_param("ss", $product_code, $user_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();

            if ($product['quantity'] >= $quantity) {
                $_SESSION['billItems'][] = [
                    'item_name' => $product['item_name'],
                    'product_code' => $product_code,
                    'price' => $product['price'],
                    'quantity' => $quantity,
                    'subtotal' => $product['price'] * $quantity,
                ];
                echo "Item added successfully.<br>";
            } else {
                echo "Not enough stock for " . htmlspecialchars($product['item_name']) . ".<br>";
            }
        } else {
            echo "Item not found or you do not have permission to access this item.<br>";
        }
        $stmt->close();
    }
}

// Handle item deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item_index'])) {
    $delete_index = (int)$_POST['delete_item_index'];
    if (isset($_SESSION['billItems'][$delete_index])) {
        unset($_SESSION['billItems'][$delete_index]);
        $_SESSION['billItems'] = array_values($_SESSION['billItems']); // Re-index array
        echo "Item deleted successfully.<br>";
    } else {
        echo "Failed to delete item: index out of range.<br>";
    }
}

// Handle checkout
// Handle checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    // Ensure customer name and contact are not empty
    if (empty($_SESSION['customer_name']) || empty($_SESSION['customer_contact'])) {
        echo "Customer name and contact cannot be empty.<br>";
    } else {
        $customer_name = htmlspecialchars(trim($_SESSION['customer_name']));
        $customer_contact = htmlspecialchars(trim($_SESSION['customer_contact']));
        $total_bill = 0;
        $user_email = $_SESSION['email']; // Ensure user_email is being retrieved

        // Debugging: Check the user_email value
        echo "User email: " . htmlspecialchars($user_email) . "<br>"; // Add this line

        foreach ($_SESSION['billItems'] as $item) {
            $total_bill += $item['subtotal'];
            // Deduct quantity from inventory
            $update_stmt = $conn->prepare("UPDATE inventory SET quantity = quantity - ? WHERE product_code = ? AND user_email = ?");
            $update_stmt->bind_param("iss", $item['quantity'], $item['product_code'], $user_email);
            if ($update_stmt->execute()) {
                echo "Updated inventory for product: " . htmlspecialchars($item['product_code']) . "<br>";
            } else {
                echo "Failed to update inventory for product: " . htmlspecialchars($item['product_code']) . "<br>";
            }
            $update_stmt->close();
        }

        // Insert the bill into the database with user_email
        $insert_bill_stmt = $conn->prepare("INSERT INTO bills (customer_name, customer_contact, bill_total, user_email) VALUES (?, ?, ?, ?)");
        $insert_bill_stmt->bind_param("ssds", $customer_name, $customer_contact, $total_bill, $user_email); // Added user_email
        if ($insert_bill_stmt->execute()) {
            echo "Bill recorded successfully.<br>";
        } else {
            echo "Failed to record the bill: " . $insert_bill_stmt->error . "<br>"; // Show error if any
        }
        $insert_bill_stmt->close();

        // Clear the bill items and customer details from the session after checkout
        $_SESSION['billItems'] = [];
        unset($_SESSION['customer_name']);
        unset($_SESSION['customer_contact']);
        echo "Checkout successful! Thank you, " . htmlspecialchars($customer_name) . ".<br>";
    }
}


// Retain customer details after form submission
$customer_name = isset($_SESSION['customer_name']) ? htmlspecialchars($_SESSION['customer_name']) : '';
$customer_contact = isset($_SESSION['customer_contact']) ? htmlspecialchars($_SESSION['customer_contact']) : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing System</title>
    <link rel="stylesheet" href="../navbar.css">
    <link rel="stylesheet" href="../Styles/billing.css">
    <style>
        /* Basic styles for form and table */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
        }
        .bill-container {
            width: 80%;
            margin: auto;
        }
    </style>
</head>
<body>
<header>
    <div class="nav-containet">
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
            <?php if (isset($_SESSION['logged-in'])): ?>
                <div class='logout-box'><?= htmlspecialchars($_SESSION['username']) ?>
                    <a href='../logout.php'><button>Logout</button></a>
                </div>
            <?php else: ?>
                <a href='./register.php'><button>Register</button></a>
                <a href='./login.php'><button>Login</button></a>
            <?php endif; ?>
        </div>
    </div>
</header>

<div class="bill-container">
    <h2>Billing System</h2>

    <!-- Customer details form -->
    <form method="POST">
        <h3>Enter Customer Details</h3>
        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" value="<?= $customer_name; ?>" required><br><br>

        <label for="customer_contact">Customer Contact:</label>
        <input type="text" id="customer_contact" name="customer_contact" value="<?= $customer_contact; ?>" required><br><br>

        <button type="submit">Save Customer Details</button>
    </form>

    <!-- Item adding form -->
    <form method="POST">
        <h3>Add Item to Bill</h3>
        <label for="product_code">Product Code:</label>
        <input type="text" id="product_code" name="product_code" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1" required><br><br>

        <button type="submit">Add Item</button>
    </form>

    <!-- Display the billing table -->
    <h3>Current Bill Items</h3>
    <table>
        <tr>
            <th>Item Name</th>
            <th>Product Code</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
        <?php foreach ($_SESSION['billItems'] as $index => $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['item_name']); ?></td>
            <td><?= htmlspecialchars($item['product_code']); ?></td>
            <td><?= htmlspecialchars($item['price']); ?></td>
            <td><?= htmlspecialchars($item['quantity']); ?></td>
            <td><?= htmlspecialchars($item['subtotal']); ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="delete_item_index" value="<?= $index; ?>">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Checkout button -->
    <form method="POST">
        <button type="submit" name="checkout">Checkout</button>
    </form>
</div>
</body>
</html>
