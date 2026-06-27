<?php
    include_once(CONNECT_DB_PATHFILE);

    $data = json_decode(file_get_contents("php://input"), true);

    if((!empty($data["id_prodotto"])) &&
       (empty($data["nome_prodotto"]))&&
       (empty($data["prezzo_prodotto"]))&&
       (empty($data["descrizione_prodotto"]))&&
       (empty($data["qta_attuale"]))){

        $stmt = $conn -> prepare("UPDATE prodotti SET qta_attuale = qta_default
                                    WHERE id_prodotto = ?");
        $stmt -> bind_param("i", $data["id_prodotto"]);
    }else if((!empty($data["id_prodotto"]))  &&
             (!empty($data["nome_prodotto"]))&&
             (!empty($data["prezzo_prodotto"]))&&
             (!empty($data["descrizione_prodotto"]))){
        $stmt = $conn -> prepare("UPDATE prodotti SET nome_prodotto = ?, prezzo_prodotto = ?, descrizione_prodotto = ?, qta_default = ?
                                    WHERE id_prodotto = ?");
        $stmt -> bind_param("sdsii", $data["nome_prodotto"], $data["prezzo_prodotto"], $data["descrizione_prodotto"], $data["qta_default"], $data["id_prodotto"]);
    }else{
        http_response_code(400);
        echo json_encode(["message" => "Dati insufficienti!"]);
        exit();
    }
    if($stmt -> execute()){
        if($stmt->affected_rows == 0 && $conn->errno != 0){
            http_response_code(404);
            echo json_encode(["message" => "Id non presente nel database."]);
        }else{
            http_response_code(200);
            echo json_encode(["message" => "Prodotto aggiornato con successo!"]);
        }
    }else{
        http_response_code(500);
        echo json_encode(["message" => "Errore nell'aggiornamento del prodotto."]);
    }

    $stmt -> close();
?>