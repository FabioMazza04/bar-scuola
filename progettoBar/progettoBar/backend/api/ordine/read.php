<?php
    include_once(CONNECT_DB_PATHFILE);

    $ordini = array();

    $sql = "SELECT O.id_ordine, O.data_ordine, O.stato_ordine, O.ora_ordine, O.totale,
                    P.nome, P.cognome,
                    GROUP_CONCAT(PR.nome_prodotto SEPARATOR ', ') AS prodotti_ordinati
            FROM ordini O
            JOIN profili P ON (O.id_profilo = P.id_profilo)
            JOIN ordini_dettaglio_ordine ODO ON (O.id_ordine = ODO.id_ordine)
            JOIN dettaglio_ordine DO ON (ODO.id_dettaglio = DO.id_dettaglio)
            JOIN prodotti_dettaglio_ordine PDO ON (DO.id_dettaglio = PDO.id_dettaglio)
            JOIN prodotti PR ON (PDO.id_prodotto = PR.id_prodotto)
            GROUP BY O.id_ordine
            ORDER BY O.data_ordine DESC, O.ora_ordine DESC";
    
    $result = mysqli_query($conn, $sql);
    if(!$result){
        die("Query fallita! :".mysqli_error($conn));
    }else{
        foreach($result as $row){
            array_push($ordini, $row);
        }
    }

    http_response_code(200);
    echo json_encode($ordini);
?>