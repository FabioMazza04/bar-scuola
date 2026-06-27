<?php
    include_once(CONNECT_DB_PATHFILE);

    $data = json_decode(file_get_contents("php://input"), true);

    if((empty($data["nome_prodotto"])) || empty($data["prezzo_prodotto"])){
        http_response_code(400);
        echo json_encode(["message" => "Nome prodotto e prezzo obbligatori."]);
        exit();
    }

    $stmt = $conn -> prepare("INSERT INTO prodotti (nome_prodotto, descrizione_prodotto, prezzo_prodotto, qta_default, qta_attuale, id_categoria)
                              VALUES(?, ?, ?, ?, ?, ?)");
    $stmt -> bind_param("ssdiii", $data["nome_prodotto"], $data["descrizione_prodotto"], $data["prezzo_prodotto"], $data["qta_default"], $data["qta_attuale"], $data["id_categoria"]);

    if($stmt -> execute()){
        http_response_code(201);
        echo json_encode(["message" => "Prodotto creato con successo!", "id" => $conn -> insert_id]);
    }else{
        http_response_code(500);
        echo json_encode(["message" => "Errore nella creazione del prodotto."]);
    }

    $stmt -> close();
?>