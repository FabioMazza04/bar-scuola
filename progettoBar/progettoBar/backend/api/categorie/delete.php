<?php
    include_once(CONNECT_DB_PATHFILE);

    $data = json_decode(file_get_contents("php://input"),true);

    if(empty($data["id_categoria"])){
        http_response_code(400);
        echo json_encode(["message" => "id_categoria obbliagtorio."]);
        exit();
    }

    $stmt = $conn -> prepare("DELETE FROM categorie WHERE id_categoria = ?");
    $stmt -> bind_param("i", $data["id_categoria"]);

    if($stmt -> execute()){
        if($stmt -> affected_rows == 0){
            http_response_code(404);
            echo json_encode(["message" => "Id non presente nel database."]);
        }else{
            http_response_code(200);
            echo json_encode(["message" => "Categoria eliminata con successo!"]);
        }
    }else{
        http_response_code(500);
        echo json_encode(["message" => "Errore nella cancellazione della categoria."]);
    }

    $stmt -> close();
?>