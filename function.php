<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "todolist");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle Registration or Login actions
if (isset($_POST["action"])) {
    if ($_POST["action"] == "register") {
        register();
    } else if ($_POST["action"] == "login") {
        login();
    }
}

function register() {
    global $conn;

    $username = mysqli_real_escape_string($conn, $_POST["user_name"]);
    $email = mysqli_real_escape_string($conn, $_POST["user_email"]);
    $password = mysqli_real_escape_string($conn, $_POST["user_password"]); // Stored in plain text

    if (empty($username) || empty($email) || empty($password)) {
        echo "Please fill out the form!";
        exit;
    }

    // Check if email is already registered
    $stmt = mysqli_prepare($conn, "SELECT user_id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo "Email is already registered!";
        mysqli_stmt_close($stmt);
        exit;
    }
    mysqli_stmt_close($stmt);

    // Insert new user
    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);

    if (mysqli_stmt_execute($stmt)) {
        echo "Registration successful!";
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
}

function login() {
    global $conn;
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    
    if (empty($email) || empty($password)) {
        echo "Please fill in all fields!";
        exit;
    }
    
    $query = "SELECT user_id, user_name, user_email, user_password FROM users WHERE user_email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) == 1) {
        mysqli_stmt_bind_result($stmt, $id, $username, $user_email, $db_password);
        mysqli_stmt_fetch($stmt);
        
        if ($password == $db_password) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $username;
            
            // Set cookies with the values used for signing in
            setcookie("login_email", $email, time() + (7 * 24 * 60 * 60), "/");
            setcookie("login_password", $password, time() + (7 * 24 * 60 * 60), "/", "", true);
            
            header("Location: index.php");
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "User not found!";
    }
    mysqli_stmt_close($stmt);
}