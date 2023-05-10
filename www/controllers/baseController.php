<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

parse_str($_SERVER['QUERY_STRING'], $query);
$body = json_decode(file_get_contents('php://input'), false);
$method = $_SERVER['REQUEST_METHOD'];

try {
    // test if body is a correct JSON (when we use body)
    if (json_last_error() != JSON_ERROR_NONE && $method != "GET" && $method != "DELETE"  && !isset($query['beer_id'])) {
        throw new Exception("Le body de la requête est mal formé.", 400);
    }

    // Récupération des variables.
    $id = isset($query['id']) ? (int) $query['id'] : '';
    $body = isset($body) ? $body : '';
    $filter = isset($query['filter']) ? (string) $query['filter'] : '%';
    $sort =  isset($query['sort']) ? (string) $query['sort'] : 'name';
    $perPage = isset($query['per_page']) ? (int) $query['per_page'] : 10;
    $page = isset($query['page']) ? (int) $query['page'] : 1;
    $beerId = isset($query['beer_id']) ? (int) $query['beer_id'] : '';
    $ingredientId = isset($query['ingredient_id']) ? (int) $query['ingredient_id'] : '';

    // Appel des méthodes souhaitées.
    switch($method) {
        case 'GET':
            if ($id) {
                $resultat = read($id);
            } else {
                $resultat = search($perPage, $page, $sort, $filter);
            }
            break;
        case 'POST':
            if (!empty($beerId)) {
                $resultat = addIngredient($beerId, $ingredientId);
            } else {
                $resultat = create($body);
            }
        
            break;
        case 'PUT':
        case 'PATCH':
            $resultat = update($id, $body);
            break;
        case 'DELETE':
            if (!empty($id)) {
                $resultat = delete($id);
            } else {
                $resultat = removeIngredient($beerId, $ingredientId);
            }
            
            break;
    }

    echo json_encode($resultat);

} catch(Exception $e) {
   
    if (gettype($e->getCode()) == "string") {
        $codeError = 500;
    } else {
        $codeError = $e->getCode();
    }

    http_response_code($codeError);
    echo json_encode(['message' => $e->getMessage()]);
}