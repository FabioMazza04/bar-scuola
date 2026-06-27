<?php
    include_once(CONNECT_DB_PATHFILE);

    $data = json_decode(file_get_contents("php://input"), true); //funzione che prende il json inviato dal post, e lo converte in un array php

    if(empty($data["nome_categoria"])){
        http_response_code(400);
        echo json_encode(["message" => "Nome categoria obbliagtorio"]);
        exit();
    }

    $stmt = $conn -> prepare("INSERT INTO categorie (nome_categoria) VALUES (?)");
    $stmt -> bind_param("s", $data["nome_categoria"]);

    if($stmt -> execute()){
        http_response_code(201);
        echo json_encode(["message" => "Categoria creata", "id" => $conn -> insert_id]);
    }else{
        http_response_code(500);
        echo json_encode(["message" => "Errore nella creazione"]);
    }

    $stmt -> close();
?>