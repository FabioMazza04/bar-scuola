<?php
    //include_once(__DIR__ . '/../../config/connect.php');
    include_once(CONNECT_DB_PATHFILE);

    $categories = array();
    $sql = 'SELECT * FROM `categorie`';
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die('Query failed :' . mysqli_error($conn));
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($categories, $row);
        }
    }

    http_response_code(200);
    echo json_encode($categories);
?>