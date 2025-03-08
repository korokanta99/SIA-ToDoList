<?php
    include_once("db_connect.php");
    $retVal = "Addition failed.";
    $isValid = true;
    $status = 400;
    $data = -1;

    $user_id = trim($_REQUEST['users_id']);
    $task_name = trim($_REQUEST['task_name']);
    $description = trim($_REQUEST['description']);
    $deadline = trim($_REQUEST['deadline']);
    $task_status = "pending";


    if (empty($task_name)) {
        $isValid = false;
        $retVal = "Title is required.";
    }

     if ($isValid) {
        try {
            $stmt = $con->prepare("INSERT INTO tasks (users_id, task_name, description, deadline, status) VALUES (?, ?, ?, ?, ?)");
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