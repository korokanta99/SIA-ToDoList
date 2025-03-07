<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "todolist_si");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST["action"])) {
    if ($_POST["action"] == "register") {
        register();
    } else if ($_POST["action"] == "login") {
        login();
    }
}

function register() {
    global $conn;

    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"]; // Password stored as plain text

    if (empty($username) || empty($email) || empty($password)) {
        echo "Please fill out the form!";
        exit;
    }


    // Insert user into database without password hashing
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    
    if (mysqli_query($conn, $query)) {
        echo "Registration successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}


function login(){

}
?>
