<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();

include_once("db_connect.php");

$status = 200;
$data = array();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 401, 'message' => "User not logged in."]);
    exit();
}

try {
    $users_id = $_SESSION['user_id'];
    $stmt = $con->prepare("SELECT task_id, task_name, description, deadline, status FROM tasks WHERE user_id = ? ORDER BY task_name");
    $stmt->bind_param("i", $users_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    

    ob_end_clean();
    echo json_encode(['status' => $status, 'data' => $data, 'count' => count($data)]);
} catch (Exception $e) {
    echo json_encode(['status' => 500, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>