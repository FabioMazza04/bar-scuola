<?php
    include_once(CONNECT_DB_PATHFILE);

    $id_tessera = $_GET["id_tessera"];

    if(empty($id_tessera)){
        http_response_code(400);
        exit();
    }

    $stmt = $conn -> prepare("SELECT * FROM tessera WHERE id_tessera = ?");
    $stmt -> bind_param("i", $id_tessera);
    $stmt -> execute();


    $risultato = $stmt->get_result()->fetch_assoc();
    if($risultato == null){
        http_response_code(404);
        echo json_encode(["message" => "Tessera non trovata."]);
    }else{
        http_response_code(200);
        echo json_encode(["credito" => $risultato["credito"]]);
    }

    $stmt -> close();
?>