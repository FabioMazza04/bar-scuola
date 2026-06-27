<?php
    include_once(CONNECT_DB_PATHFILE);

    $prodotti = array();

    $sql = "SELECT P.* , C.nome_categoria, O.percentuale_sconto, O.solo_tessera 
            FROM prodotti P
            LEFT JOIN categorie C ON (P.id_categoria = C.id_categoria)
            LEFT JOIN offerte O ON (O.id_prodotto = P.id_prodotto
            AND CURDATE() BETWEEN O.data_inizio_offerta AND O.data_fine_offerta)
            GROUP BY P.id_prodotto";
    
    $result = mysqli_query($conn, $sql);
    if(!$result){
        die("Query fallita! :".mysqli_error($conn));
    }else{
        foreach($result as $row){
            array_push($prodotti, $row);
        }
    }

    http_response_code(200);
    echo json_encode($prodotti);
?>