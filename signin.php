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
    <title>Sign In</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <div class="container">
        <img src="pics/logo.png" alt="Logo" class="logo">
        <div class="form-box">
            <h2>Sign in</h2>
            <p>New to ToDo? <a href="signup.php">Sign Up here</a></p>
            <form autocomplete="off" action="index.php" method="post">
                <input type="text" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <a href="#" class="forgot-password">Forgot password?</a>
                <button type="submit">Sign in</button>
            </form>
            <div class="social-login">
                <img src="pics/apple-logo.png" alt="Apple">
                <img src="pics/google-logo.png" alt="Google">
                <img src="pics/facebook-logo.png" alt="Facebook">
            </div>
        </div>
    </div>
</body>
</html>