<?php require('connect.php'); 
    session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link rel="stylesheet" href='styles1.css'>
    <link rel="stylesheet" href='./navbar.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header>
    <div class="nav-containet">
        <a href="./index.php">
            <img src="./assets/logo.png" id="img">
        </a>
        <div class="Link-sec">
            <ul>
                <a href="./Components/billing.php"><li>Billing</li></a>
                <a href="./Components/inventory.php"><li>Inventory</li></a>
                <a href="./Components/report.php"><li>Report</li></a>
                <a href="./Components/settings.php"><li>Settings</li></a>
            </ul>
        </div>
        <div class="Login-button-sec">
            <?php
            if(isset($_SESSION['logged-in'])){
                echo"
                <div class='logout-box'>
                    $_SESSION[username]
                    <a href='logout.php'><button>Logout</button></a>
                </div>";
            } else {
                echo"<button onclick='openModal(\"registerModal\")'>Register</button>";
                echo"<button onclick='openModal(\"loginModal\")'>Login</button>";
            }
            ?>
        </div>
    </div>
    <div class="sticky-navbar" id="stickynav">
        <button onclick="showAlert()">Low Stock Alert</button>
        <button onclick="showNotification()">Notifications</button>
    </div>
    </header>

    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('loginModal')">&times;</span>
            <h2>Login Form</h2>
            <form action="./login_process.php" method="POST">
                <label>Enter Email / Username:</label>
                <input type="text" name="email_username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <input type="submit" name="login" value="Login">
            </form>
        </div>
    </div>

    <div id="registerModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('registerModal')">&times;</span>
            <h2>Registration Form</h2>
            <form action="./register_process.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <input type="submit" name="register" value="Register">
            </form>
        </div>
    </div>


    <section class="landing-page-section-1">
        <div class="page-1-overview">
            <div class="project-details">
                <h2>Billing and Inventory Management System</h2>
                <p>Welcome to our project! Our aim is to provide a comprehensive solution for managing inventory and billing. With our system, you can easily add items, track inventory levels, and generate invoices. Additionally, we provide notifications when stock levels fall below a predefined threshold, ensuring you never run out of essential items.</p>
                <p>Our platform integrates seamlessly with your existing processes, offering an intuitive interface for inventory management and billing. Whether you’re a small business or a large enterprise, our system is designed to meet your needs and simplify your operations.</p>
                <p>Features include real-time stock updates, customizable alerts, and detailed reports. Join us in transforming the way you manage your inventory and billing processes!</p>
                <button class="cta-button" onclick="openModal('registerModal')">Get Started</button>
            </div>
            <div class="image-section">
                <img src="./assets/landingimg.png" alt="Project Overview">
            </div>
        </div>
    </section>

    <?php
    if(isset($_SESSION['logged-in'])){
        echo"<h1>Welcome $_SESSION[username] to the website</h1>";
    }
    ?>




    <section class="how-to-use">
        <h2>How to Use</h2>
        <div class="how-to-use-content">
            <div class="step">
                <i class="fas fa-upload"></i>
                <h3>Upload Inventory</h3>
                <p>Add items into your inventory.</p>
            </div>
            <div class="step">
            <i class="fa-solid fa-file-invoice"></i>
                <h3>Billing</h3>
                <p>Retrieve the items from inventory while billing.</p>
            </div>
            <div class="step">
            <i class="fa-regular fa-bell"></i>
                <h3>Stock Alert</h3>
                <p>Get notified when stock falls below the target.</p>
            </div>
            <div class="step">
            <i class="fa-solid fa-chart-bar"></i>
                <h3>Generate Reports</h3>
                <p>Generate detailed reports to analyze and optimize your inventory and billing processes.</p>
            </div>
        </div>
    </section>

    <section class="extrainfo">
        <h2>How is it effective?</h2>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Harum, molestias! Necessitatibus nemo mollitia possimus impedit repellat, nesciunt cumque molestiae facere!</p>
    </section>

    <section class="table-section">
        <h2>Enrollment Details</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Enrollment Number</th>
            </tr>
            <tr>
                <td>Parshva Patel</td>
                <td>IU2241230355</td>
            </tr>
            <tr>
                <td>Niket Thakkar</td>
                <td>IU2241230342</td>
            </tr>
        </table>
    </section>

    <footer>
        <div class="footer-content">
            <h3>Inventrack</h3>
            <p>Your reliable partner for inventory and billing management.</p>
            <div class="socials">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = "block";
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }

        function showAlert() {
            alert('Low stock alert feature is under development.');
        }

        function showNotification() {
            alert('Notifications feature is under development.');
        }
    </script>

</body>

</html>
