<?php
require('../connect.php'); // Ensure this file connects to your database
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch bills only for the logged-in user
$bills = [];
if (isset($_SESSION['logged-in'])) {
    $user_email = $_SESSION['email']; // Assuming email is stored in session

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT bill_id, customer_name, customer_contact, bill_total, bill_date FROM bills WHERE user_email = ? ORDER BY bill_date DESC");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $bills[] = $row;
    }
    $stmt->close();
} else {
    echo "<p>You need to be logged in to view your billing report.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Reports</title>
    <link rel="stylesheet" href="../navbar.css">
    <link rel="stylesheet" href="../Styles/report.css">
    <style>
        /* Basic styles for table */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .report-container {
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

<div class="report-container">
    <h2>Your Billing Report</h2>

    <table>
        <thead>
            <tr>
                <th>Bill ID</th>
                <th>Customer Name</th>
                <th>Customer Contact</th>
                <th>Bill Total</th>
                <th>Bill Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($bills)): ?>
                <?php foreach ($bills as $bill): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($bill['bill_id']); ?></td>
                        <td><?php echo htmlspecialchars($bill['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($bill['customer_contact']); ?></td>
                        <td><?php echo number_format((float)$bill['bill_total'], 2, '.', ''); ?></td>
                        <td><?php echo htmlspecialchars($bill['bill_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No bills found for your account.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
