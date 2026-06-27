<?php
    include_once(CONNECT_DB_PATHFILE);

    $offerte = array();

    $sql = "SELECT O.*, P.nome_prodotto FROM offerte O
            JOIN prodotti P 
            ON (O.id_prodotto = P.id_prodotto)";
    $result = mysqli_query($conn, $sql);

    if(!$result){
        die("Query fallita! :".mysqli_error($conn));
    }else{
        foreach($result as $row){
            array_push($offerte, $row);
        }
    }

    http_response_code(200);
    echo json_encode($offerte);
?>