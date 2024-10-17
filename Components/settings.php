<?php require('../connect.php');
 session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>settings</title>
    <link rel="stylesheet" href="../navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
        if(isset($_SESSION['logged-in'])){
            echo"
            <div class='logout-box'>
             $_SESSION[username]
            <a href='../logout.php'><button>Logout</button></a>
            </div>";
            
        }
        else{
            echo"<a href='./register.php'><button>Register</button></a>";
            echo"<a href='./login.php'><button>Login</button></a>";
        }
            ?>
        </div>
    </div>
    </header>
<?php
if(isset($_SESSION['logged-in'])){
        echo"<h1>Welcome $_SESSION[username] to the settings</h1>";
    }
?>

</body>
</html>