<?php
    include_once("db_connect.php");
    $retVal = "Edit failed.";
    $isValid = true;
    $status = 400;

    $task_id = trim($_POST['task_id']);
    $task_name = trim($_POST['task_name']);
    $description = trim($_POST['description']);
    $deadline = trim($_POST['deadline']);

    if ($isValid) {
        $stmt = $con->prepare("SELECT task_id FROM tasks WHERE task_id = ?");
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows === 0) {
            $isValid = false;
            $retVal = "Edit failed. Task not found.";
        }
    }

    if ($isValid) {
        try {
            $stmt = $con->prepare("UPDATE tasks SET task_name = ?, description = ?, deadline = ? WHERE task_id = ?");
            $stmt->bind_param("sssi", $task_name, $description, $deadline, $task_id);
            $stmt->execute();
            $stmt->close();

            $status = 200;
            $retVal = "Task updated successfully.";
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
