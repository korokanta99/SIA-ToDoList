<?php
    include_once("db_connect.php");
    $retVal = "Status change failed.";
    $isValid = true;
    $status = 400;

    $task_id = trim($_POST['task_id']);
    $status = trim($_POST['task_status']);

    if ($isValid) {
        $stmt = $con->prepare("SELECT task_id FROM tasks WHERE task_id = ?");
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows === 0) {
            $isValid = false;
            $retVal = "Status change failed. Task not found.";
        }
    }

    if ($isValid) {
        try {
            $stmt = $con->prepare("UPDATE tasks SET status = ? WHERE task_id = ?");
            $stmt->bind_param("si", $status, $task_id);
            $stmt->execute();
            $stmt->close();

            $status = 200;
            $retVal = "Task status changed successfully.";
        } catch (Exception $e) {
            $retVal = $e->getMessage();
        }
    }

    $myObj = array(
        'status' => $status,
        'message' => $retVal  
    );

    $myJSON = json_encode($myObj, JSON_FORCE_OBJECT);
    echo $myJSON;
?>
