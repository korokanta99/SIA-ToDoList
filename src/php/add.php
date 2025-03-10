<?php
    header('Content-Type: application/json');
    error_reporting(E_ALL);
    ini_set('display_errors', 0);

    include_once("db_connect.php");

    $response = [
        "status" => 400,
        "data" => -1,
        "message" => "Addition failed."
    ];
    if (!isset($_SESSION['user_id'])) {
        $response['message'] = "User not logged in.";
        echo json_encode($response);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $task_name = trim($_REQUEST['new-task-title']);
    $description = trim($_REQUEST['new-task-desc']);
    $deadline = trim($_REQUEST['new-task-deadline']);
    $task_status = "pending";

    $isValid = true;


    if (empty($task_name)) {
        $isValid = false;
        $retVal = "Title is required.";
    }

     if ($isValid) {
        try {
            $stmt = $con->prepare("INSERT INTO tasks (user_id, task_name, description, deadline, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $user_id, $task_name, $description, $deadline, $task_status);
            $stmt->execute();
            $stmt->close();
            $data = mysqli_insert_id($con);
            $status = 200;
            $retVal = "Task added successfully.";
        } catch (Exception $e) {
            $retVal = $e->getMessage();
        }
    }


    $myObj = array(
        'status' => $status,
        'data' => $data,
        'message' => $retVal  
    );

    $myJSON = json_encode($myObj, JSON_FORCE_OBJECT);
    echo $myJSON;
?>