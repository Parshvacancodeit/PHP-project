<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="../Styles/register.css">
</head>
<body>
<div class="container">
        <h2>Login Form</h2>
        <form action="../login_process.php" method="POST">
            <label>Enter Email / Username:</label>
            <input type="text"  name="email_username" required>
            
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <input type="submit" name="login" value="login">
        </form>
</div>
</body>
</html>