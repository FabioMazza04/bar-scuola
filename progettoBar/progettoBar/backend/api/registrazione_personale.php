<?php
    include_once(CONNECT_DB_PATHFILE);

    $data = json_decode(file_get_contents("php://input"), true);

    if((empty($data["nome"])) || (empty($data["cognome"])) ||
       (empty($data["email"])) || (empty($data["password"])) ||
       (empty($data["ruolo"]))){
        http_response_code(400);
        echo json_encode(["message" => "Dati insufficenti."]);
        exit();
       }


    if(strpos($data["email"], "@itcgalilei.edu.it") === false){
        http_response_code(400);
        echo json_encode(["message" => "Email non autorizzata!"]);
        exit();
    }

    $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
    $data["tipo"] = "personale_scolastico";

    $stmt = $conn -> prepare("INSERT INTO profili (nome, cognome, email, password, tipo)
                                VALUES (?, ?, ?, ?, ?)");
    $stmt -> bind_param("sssss", $data["nome"], $data["cognome"], $data["email"], $data["password"], $data["tipo"]);

    if($stmt -> execute()){
        http_response_code(201);
    }else{
        http_response_code(500);
    }

    $id_profilo = $conn -> insert_id;

    $stmt = $conn -> prepare("INSERT INTO tessera (credito, id_profilo) 
                                VALUES (0, ?)");
    $stmt -> bind_param("i", $id_profilo);

    if($stmt -> execute()){
        http_response_code(201);
    }else{
        http_response_code(500);
    }
    $id_tessera = $conn -> insert_id;

    $stmt = $conn -> prepare("UPDATE profili SET id_tessera = ? WHERE id_profilo = ?");
    $stmt -> bind_param("ii", $id_tessera, $id_profilo);

    if($stmt -> execute()){
        http_response_code(201);
    }else{
        http_response_code(500);
    }

    if($data["tipo"] === "personale_scolastico"){
        $stmt = $conn -> prepare("INSERT INTO personale_scolastico (ruolo, id_profilo)
                                   VALUES (?, ?)");
        $stmt -> bind_param("si", $data["ruolo"], $id_profilo);

        if($stmt -> execute()){
            http_response_code(201);
            echo json_encode(["message" => "Personale registrato con successo!"]);
        }else{
            http_response_code(500);
            echo json_encode(["message" => "Errore nella registrazione del personale."]);
        }
    }

    $stmt -> close();
?>