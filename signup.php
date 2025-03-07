<?php
require 'function.php';

if (isset($_SESSION["id"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="main.css">
    <script src="script.js" defer></script>
</head>
<body>
    <div class="container">
        <img src="pics/logo.png" alt="Logo" class="logo">
        <div class="form-box">
        
            <h2>Sign Up</h2>
            <p>Already have an account? <a href="signin.php">Log in</a></p>
            
            <form id="registerForm" autocomplete="off" action="signin.php" method="post">
                <input type="hidden" name="action" value="register">
                
                <label for="username">Name</label>
                <input type="text" name="username" id="username" required> <br>
                
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required> <br>
                
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required> <br>
                
                <button type="submit">Register</button>
            </form>
        </div>
    </div>
</body>
</html>
