<?php
    include_once(CONNECT_DB_PATHFILE);

    $data = json_decode(file_get_contents("php://input"), true);

    if((empty($data["username"])) || (empty($data["psw"]))){
        http_response_code(400);
        echo json_encode(["message" => "Username e password mancanti."]);
        exit();
    }

    $stmt = $conn -> prepare("SELECT P.email, P.password, P.tipo, P.id_profilo, P.id_tessera, P.nome, PS.ruolo
                                FROM profili P 
                                LEFT JOIN personale_scolastico PS ON (PS.id_profilo = P.id_profilo)
                                WHERE P.email = ?");
    $stmt -> bind_param("s", $data["username"]);

    if($stmt -> execute()){
        $risultato = $stmt -> get_result() -> fetch_assoc();
        if($risultato == null){
            http_response_code(404);
            echo json_encode(["message" => "Nessun risultato trovato."]);
            exit();
        }
        if(password_verify($data["psw"], $risultato["password"])){
            http_response_code(200);
            echo json_encode(["message" => "Login effetuato!", "tipo" => $risultato["tipo"], "id_profilo" => $risultato["id_profilo"], "id_tessera" => $risultato["id_tessera"], "nome" => $risultato["nome"], "ruolo" => $risultato["ruolo"]]);
        }else{
            http_response_code(401);
            echo json_encode(["message" => "Credenziali non verificate."]);
        }
    }else{
        http_response_code(500);
        echo json_encode(["message" => "Errore nella selezione dei campi."]);
    }

    $stmt -> close();
?>