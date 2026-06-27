<?php
    include_once(CONNECT_DB_PATHFILE);

    $data = json_decode(file_get_contents("php://input"), true);
    $ricariche = [5, 10, 15, 20, 50];

    if((empty($data["id_tessera"])) || (empty($data["importo"]))){
        http_response_code(400);
        echo json_encode(["message" => "Dati insufficienti."]);
        exit();
    }

    if(!in_array($data["importo"], $ricariche)){
        http_response_code(400);
        echo json_encode(["message" => "Importo non presente tra le opzioni (5, 10, 15, 20, 50)."]);
        exit();
    }

    $stmt = $conn -> prepare("UPDATE tessera SET credito = credito + ?
                                WHERE id_tessera = ? ");
    $stmt -> bind_param("di", $data["importo"], $data["id_tessera"]);
    $stmt -> execute();
    
    $data_ricarica = date("Y-m-d");
    $ora_ricarica = date("H:i:s");
    $metodo_pagamento = "carta";
    $stmt = $conn -> prepare("INSERT INTO ricariche_tessera (importo, data_ricarica, ora_ricarica, metodo_pagamento)
                                VALUES (?, ?, ?, ?)");
    $stmt -> bind_param("dsss", $data["importo"], $data_ricarica, $ora_ricarica, $metodo_pagamento);
    $stmt -> execute();
    $id_ricarica = $conn -> insert_id;

    $stmt = $conn -> prepare("INSERT INTO tessera_riacariche_tessera (id_tessera, id_ricarica)
                                VALUES (?, ?)");
    $stmt -> bind_param("ii", $data["id_tessera"], $id_ricarica);

    if($stmt -> execute()){
        http_response_code(201);
        echo json_encode(["message" => "Ricarica effetuata con successo!"]);
    }else{
        http_response_code(500);
        echo json_encode(["message" => "Errore nella ricarica."]);
    }

    $stmt -> close();
?>  