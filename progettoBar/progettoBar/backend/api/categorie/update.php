<?php
    include_once(CONNECT_DB_PATHFILE);

    $data = json_decode(file_get_contents("php://input"), true);

    if((empty($data["id_categoria"])) || empty($data["nome_categoria"])){
        http_response_code(400);
        echo json_encode(["message" => "id_categoria e nome_categoria obbligatori."]);
        exit();
    }

    $stmt = $conn -> prepare("UPDATE categorie SET nome_categoria = ? WHERE id_categoria = ?");
    $stmt -> bind_param("si", $data["nome_categoria"], $data["id_categoria"]);

    if($stmt -> execute()){
        if($stmt -> affected_rows == 0){
            http_response_code(404);
        echo json_encode(["message" => "Id inesistente del database."]);  
        }else{
            http_response_code(200);
        echo json_encode(["message" => "Categoria aggiornata con successo!"]);    
        }
    }else{
        http_response_code(500);
        echo json_encode(["message" => "Errore nell'aggiornamento della categoria."]);
    }
    $stmt -> close();
?>