<?php

parse_str($_SERVER['QUERY_STRING'], $query);
$body = json_decode(file_get_contents('php://input'), false);
$method = $_SERVER['REQUEST_METHOD'];

try {
    header('Content-Type:application/json;charset=utf-8');

    // test if body is a correct JSON (when we use body)
    if (json_last_error() != JSON_ERROR_NONE && $method != "GET" && $method != "DELETE"  && !isset($query['beer_id'])) {
        throw new Exception("Le body de la requête est mal formé.", 400);
    }

    // Récupération des variables.
    $id = isset($query['id']) ? (int) $query['id'] : '';
    $body = isset($body) ? $body : '';
    $beerId = isset($query['beer_id']) ? (int) $query['beer_id'] : '';
    $ingredientId = isset($query['ingredient_id']) ? (int) $query['ingredient_id'] : '';

    // Appel des méthodes souhaitées.
    switch($method) {
        case 'GET':
            if ($id) {
                $resultat = read($id);
            } else {
                $resultat = search();
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
    http_response_code($e->getCode());
    echo json_encode(['message' => $e->getMessage()]);
}