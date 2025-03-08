<?php
    include_once("db_connect.php");

    $status = 200;
    $data = array();
    $count = 0;

    if (!isset($_SESSION['users_id'])) {
        $myObj = array(
            'status' => 401, // Unauthorized
            'message' => "User not logged in."
        );
        echo json_encode($myObj, JSON_FORCE_OBJECT);
        exit();
    }

    $users_id = $_SESSION['users_id']; 

    $stmt = $con->prepare("SELECT task_name, description, deadline FROM tasks WHERE users_id = ? ORDER BY task_name");
    $stmt->bind_param("i", $users_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();

    $myObj = array(
        'status' => $status,
        'data' => $data,
        'count' => $count
    );

    $myJSON = json_encode($myObj, JSON_FORCE_OBJECT);
    echo $myJSON;
?>


