<?php
    include_once(CONNECT_DB_PATHFILE);

    $data = json_decode(file_get_contents("php://input"), true);

    if((empty($data["id_ordine"])) || (empty($data["stato_ordine"]))){
        http_response_code(400);
        echo json_encode(["message" => "Dati insufficienti."]);
        exit();
    }

    $stmt = $conn -> prepare("UPDATE ordini 
                                SET stato_ordine = ? 
                                WHERE id_ordine = ?");
    $stmt -> bind_param("si", $data["stato_ordine"], $data["id_ordine"]);

    if($stmt->execute()){
        http_response_code(200);
        echo json_encode(["message" => "Ordine aggiornato con successo!"]);
    }else{
        http_response_code(500);
        echo json_encode(["message" => "Errore nell'aggiornamento dell'ordine."]);
    }

    $stmt -> close();
?>