<?php
    include_once("db_connect.php");
    $retVal = "Delete failed.";
    $status = 400;

    $task_id = trim($_REQUEST['task_id']);

    try {
        $stmt = $con->prepare("DELETE FROM tasks WHERE task_id = $task_id");
        $stmt->execute();
        $stmt->close();
        $status = 200;
        $retVal = "Task deleted.";
    } catch (Exception $e) {
        $retVal = $e->getMessage();
    }

    $myObj = array(
        'status' => $status,
        'message' => $retVal
    );

    $myJSON = json_encode($myObj, JSON_FORCE_OBJECT);
    echo $myJSON;
?>
