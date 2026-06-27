<?php
// Definizione HTTP headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

define('PROJECT_NAME', 'BAR/progettoBar/progettoBar/backend');
define('CONNECT_DB_PATHFILE', __DIR__ . '/connect.php');

// Estraggo tipo di richiesta e metodo HTTP
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method  = $_SERVER['REQUEST_METHOD'];

// Gestione del preflight CORS
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Rotta base per l'invocazione degli endpoint PHP
$base = '/' . PROJECT_NAME . '/index.php';

//echo $base;

switch ($request) {
    case '/' . PROJECT_NAME . '/index.php/prodotti':
        if ($method == 'GET') {
            require __DIR__ . '/api/prodotti/read.php';
        } elseif ($method == 'PUT') {
            require __DIR__ . '/api/prodotti/update.php';
        } elseif ($method == 'POST') {
            require __DIR__ . '/api/prodotti/create.php';
        } elseif ($method == 'DELETE') {
            require __DIR__ . '/api/prodotti/delete.php';
        }
        break;
    case '/' . PROJECT_NAME . '/index.php/categorie':
        if ($method == 'GET') {
            require __DIR__ . '/api/categorie/read.php';
        } elseif ($method == 'PUT') {
            require __DIR__ . '/api/categorie/update.php';
        } elseif ($method == 'POST') {
            require __DIR__ . '/api/categorie/create.php';
        } elseif ($method == 'DELETE') {
            require __DIR__ . '/api/categorie/delete.php';
        }
        break;
    case '/' . PROJECT_NAME . '/index.php/login':
        if ($method == 'POST') {
            require __DIR__ . '/api/login.php';
        }  elseif ($method == 'PUT') {
            http_response_code(405);
            echo json_encode(["message" => "Metodo non consentito."]);
        } elseif ($method == 'GET') {
            http_response_code(405);
            echo json_encode(["message" => "Metodo non consentito."]);
        } elseif ($method == 'DELETE') {
            http_response_code(405);
            echo json_encode(["message" => "Metodo non consentito."]);
        }
        break;
    case '/' . PROJECT_NAME . '/index.php/registrazione':
        if ($method == 'POST') {
            require __DIR__ . '/api/registrazione.php';
        }  elseif ($method == 'PUT') {
            http_response_code(405);
            echo json_encode(["message" => "Metodo non consentito."]);
        } elseif ($method == 'GET') {
            http_response_code(405);
            echo json_encode(["message" => "Metodo non consentito."]);
        } elseif ($method == 'DELETE') {
            http_response_code(405);
            echo json_encode(["message" => "Metodo non consentito."]);
        }
        break;
    case '/' . PROJECT_NAME . '/index.php/registrazione-personale':
        if ($method == 'POST') {
            require __DIR__ . '/api/registrazione_personale.php';
        }  elseif ($method == 'PUT') {
            http_response_code(405);
            echo json_encode(["message" => "Metodo non consentito."]);
        } elseif ($method == 'GET') {
            http_response_code(405);
            echo json_encode(["message" => "Metodo non consentito."]);
        } elseif ($method == 'DELETE') {
            http_response_code(405);
            echo json_encode(["message" => "Metodo non consentito."]);
        }
        break;
    case '/' . PROJECT_NAME . '/index.php/tessera':
        if ($method == 'POST') {
            http_response_code(405);
            echo json_encode(["message" => "Metodo non consentito."]);
        }  elseif ($method == 'PUT') {
            http_response_code(405);
            echo json_encode(["message" => "Metodo non consentito."]);
        } elseif ($method == 'GET') {
            require __DIR__ . '/api/tessera/read.php';
        } elseif ($method == 'DELETE') {
            http_response_code(405);
            echo json_encode(["message" => "Metodo non consentito."]);
        }
        break;
    case '/' . PROJECT_NAME . '/index.php/ordine':
        if ($method == 'POST') {
            require __DIR__ . '/api/ordine/create.php';
        }  elseif ($method == 'PUT') {
            require __DIR__ . '/api/ordine/update.php';
        } elseif ($method == 'GET') {
            require __DIR__ . '/api/ordine/read.php';
        } elseif ($method == 'DELETE') {
            http_response_code(405);
            echo json_encode(["message" => "Metodo non consentito."]);
        }
        break;
    case '/' . PROJECT_NAME . '/index.php/ricarica':
        if ($method == 'POST') {
            require __DIR__ . '/api/tessera/ricarica.php';
        }  elseif ($method == 'PUT') {
            http_response_code(405);
            echo json_encode(["message" => "Metodo non consentito."]);
        } elseif ($method == 'GET') {
            http_response_code(405);
            echo json_encode(["message" => "Metodo non consentito."]);
        } elseif ($method == 'DELETE') {
            http_response_code(405);
            echo json_encode(["message" => "Metodo non consentito."]);
        }
        break;
    case '/' . PROJECT_NAME . '/index.php/offerte':
        if ($method == 'POST') {
            require __DIR__ . '/api/offerte/create.php';
        }  elseif ($method == 'PUT') {
            require __DIR__ . '/api/offerte/update.php';
        } elseif ($method == 'GET') {
            require __DIR__ . '/api/offerte/read.php';
        } elseif ($method == 'DELETE') {
            require __DIR__ . '/api/offerte/delete.php';
        }
        break;
    default:
        http_response_code(404);
        echo json_encode(["message" => "Risorsa non trovata"]);
        break;

    
}
?>