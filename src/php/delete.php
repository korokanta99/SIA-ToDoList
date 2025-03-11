<?php
include_once("db_connect.php");

$retVal = "Delete failed.";
$status = 400;


error_log("Received DELETE request: " . print_r($_POST, true));


if (!isset($_POST['task_id'])) {
    error_log("Error: task_id is missing");
    echo json_encode(['status' => 400, 'message' => 'Task ID missing.']);
    exit;
}

$task_id = trim($_POST['task_id']);
error_log("Deleting task with ID: " . $task_id);

if (!empty($task_id)) {
    try {
        $stmt = $con->prepare("DELETE FROM tasks WHERE task_id = ?");
        $stmt->bind_param("i", $task_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $status = 200;
            $retVal = "Task deleted successfully.";
        } else {
            $retVal = "No task found with that ID.";
        }

        $stmt->close();
    } catch (Exception $e) {
        $retVal = $e->getMessage();
    }
} else {
    $retVal = "Invalid task ID.";
}

echo json_encode(['status' => $status, 'message' => $retVal]);

?>