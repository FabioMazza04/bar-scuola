<?php
    include_once(CONNECT_DB_PATHFILE);

    $data = json_decode(file_get_contents("php://input"), true);

    if(empty($data["id_prodotto"])){
        http_response_code(400);
        echo json_encode(["message" => "Id prodotto obbligatorio."]);
        exit();
    }

    $stmt = $conn -> prepare("DELETE FROM prodotti WHERE id_prodotto = ?");
    $stmt -> bind_param("i", $data["id_prodotto"]);

    if($stmt -> execute()){
        if($stmt -> affected_rows == 0){
            http_response_code(404);
            echo json_encode(["message" => "Id non presente nel database."]);
        }else{
            http_response_code(200);
        echo json_encode(["message" => "Prodotto eliminato con successo!"]);
        }
    }else{
        http_response_code(500);
        echo json_encode(["message" => "Errore nell'eliminazione del prodotto."]);
    }

    $stmt -> close();
?>