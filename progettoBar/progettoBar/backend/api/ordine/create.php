<?php
    include_once(CONNECT_DB_PATHFILE);

    $data = json_decode(file_get_contents("php://input"), true);
    if(!isset($data["id_tessera"]) || !isset($data["id_profilo"]) || !isset($data["carrello"])){
        http_response_code(400);
        echo json_encode(["message" => "Dati insufficienti!"]);
        exit();
    }

    $stmt = $conn -> prepare("SELECT credito FROM tessera WHERE id_tessera = ?");
    $stmt -> bind_param("i", $data["id_tessera"]);
    


    if($stmt -> execute()){
        $result = $stmt -> get_result() -> fetch_assoc();
        $credito = $result["credito"];
        $credito = $credito ?? 0;
        $totale = 0;
        foreach($data["carrello"] as $item){
            $totale += $item["prezzo"] * $item["quantita"];
        }
        if($credito < $totale){
            http_response_code(400);
            echo json_encode(["message" => "Credito insufficente!"]);
        }else{
            $data_ordine = date('Y-m-d');
            $ora_ordine = date('H:i:s');
            $stato = "in preparazione";
            $stmt = $conn -> prepare("INSERT INTO ordini (data_ordine, ora_ordine, stato_ordine, totale, id_profilo)
                    VALUES (?, ?, ?, ?, ?);");
            $stmt -> bind_param("sssdi", $data_ordine, $ora_ordine, $stato, $totale, $data["id_profilo"]);
            $stmt->execute();
            $id_ordine = $conn -> insert_id;

            foreach($data["carrello"] as $item){
                $stmt = $conn -> prepare("INSERT INTO dettaglio_ordine (qta, prezzo, sconto_applicato)
                                            VALUES (?, ?, 0);");
                $stmt -> bind_param("id", $item["quantita"], $item["prezzo"]);
                $stmt -> execute();
                $id_dettaglio = $conn -> insert_id;

                $stmt = $conn -> prepare("INSERT INTO ordini_dettaglio_ordine (id_dettaglio, id_ordine)
                                            VALUES (?, ?);");
                $stmt -> bind_param("ii", $id_dettaglio, $id_ordine);
                $stmt -> execute();

                $stmt = $conn -> prepare("INSERT INTO prodotti_dettaglio_ordine (id_dettaglio, id_prodotto)
                                            VALUES (?, ?);");
                $stmt -> bind_param("ii", $id_dettaglio, $item["id"]);
                $stmt -> execute();

                $stmt = $conn -> prepare("UPDATE prodotti SET qta_attuale = qta_attuale - ? 
                                            WHERE id_prodotto = ?;");
                $stmt -> bind_param("ii", $item["quantita"], $item["id"]);
                $stmt -> execute();
            }

            $stmt = $conn -> prepare("UPDATE tessera SET credito = credito - ? 
                                        WHERE id_tessera = ?;");
            $stmt -> bind_param("di", $totale, $data["id_tessera"]);
            $stmt -> execute();
            http_response_code(201);
            echo json_encode(["message" => "Ordine confermato!"]);
        }
    }
    $stmt -> close();
?>