<?php
    include_once(CONNECT_DB_PATHFILE);

    $data = json_decode(file_get_contents("php://input"), true);

    if((empty($data["id_offerta"])) || (empty($data["nome_offerta"])) ||
        (empty($data["percentuale_sconto"])) || (empty($data["data_inizio_offerta"])) ||
        (empty($data["data_fine_offerta"])) ||(empty($data["solo_tessera"]))){

        http_response_code(400);
        echo json_encode(["message" => "Dati insufficienti!"]);
        exit();
    }

    $stmt = $conn -> prepare("UPDATE offerte 
                                SET nome_offerta = ?, percentuale_sconto = ?, data_inizio_offerta = ?, data_fine_offerta = ?, solo_tessera = ?
                                WHERE id_offerta = ?");
    $stmt -> bind_param("sissii", $data["nome_offerta"], $data["percentuale_sconto"], $data["data_inizio_offerta"], $data["data_fine_offerta"], $data["solo_tessera"], $data["id_offerta"]);

    if($stmt -> execute()){
        if($stmt -> affected_rows == 0){
            http_response_code(404);
            echo json_encode(["message" => "Id non presente nel database."]);
        }else{
            http_response_code(200);
            echo json_encode(["message" => "Offerta aggiornata con successo!"]);
        }
    }else{
        http_response_code(500);
        echo json_encode(["message" => "Errore nell'aggiornamento dell'offerta."]);
    }

    $stmt -> close();
?>