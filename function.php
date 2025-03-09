<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "todolist_si");

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

    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]); // Stored in plain text

    if (empty($username) || empty($email) || empty($password)) {
        echo "Please fill out the form!";
        exit;
    }

    // Check if email is already registered
    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
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

function login(){
    global $conn;

    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    if (empty($email) || empty($password)) {
        echo "Please fill in all fields!";
        exit;
    }

    $query = "SELECT id, username, password FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 1) {
        mysqli_stmt_bind_result($stmt, $id, $username, $db_password);
        mysqli_stmt_fetch($stmt);

        // Compare passwords directly (not secure)
        if ($password == $db_password) { 
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            // Set cookies for 7 days
            setcookie("user_id", $id, time() + (7 * 24 * 60 * 60), "/");
            setcookie("username", $username, time() + (7 * 24 * 60 * 60), "/");
            setcookie("email", $email, time() + (7 * 24 * 60 * 60), "/");

            header("Location: index.html");
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "User not found!";
    }

    mysqli_stmt_close($stmt);
}


if (isset($_GET["logout"])) {
    session_destroy();
    setcookie("user_id", "", time() - 3600, "/");
    setcookie("username", "", time() - 3600, "/");
    setcookie("email", "", time() - 3600, "/");
    header("Location: signin.php");
    exit();
}

?>
