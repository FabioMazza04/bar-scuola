<?php
    include_once(CONNECT_DB_PATHFILE);

    $data = json_decode(file_get_contents("php://input"), true);

    if((empty($data["nome_offerta"])) || (empty($data["percentuale_sconto"])) ||
        (empty($data["data_inizio_offerta"])) || (empty($data["data_fine_offerta"])) ||
        (!isset($data["solo_tessera"])) || (empty($data["id_prodotto"]))){
          
        http_response_code(400);
        echo json_encode(["message" => "Dati insufficienti!"]);
        exit();  
    }

    $stmt = $conn -> prepare("INSERT INTO offerte (nome_offerta, percentuale_sconto, data_inizio_offerta, data_fine_offerta, solo_tessera, id_prodotto)
                                VALUES (?, ?, ?, ?, ?, ?)");
    $stmt -> bind_param("sissii", $data["nome_offerta"], $data["percentuale_sconto"], $data["data_inizio_offerta"], $data["data_fine_offerta"], $data["solo_tessera"], $data["id_prodotto"]);
    if($stmt -> execute()){
        http_response_code(201);
        echo json_encode(["message" => "Offerta creata con successo!"]);
    }else{
        http_response_code(500);
        echo json_encode(["message" => "Errore nella creazione dell'offerta."]);
    }

    $stmt -> close();
?>