<?php

parse_str($_SERVER['QUERY_STRING'], $query);
$body = json_decode(file_get_contents('php://input'), false);
$method = $_SERVER['REQUEST_METHOD'];

try {
    header('Content-Type:application/json;charset=utf-8');

    // test if body is a correct JSON (when we use body)
    if (json_last_error() != JSON_ERROR_NONE && $method != "GET" && $method != "DELETE") {
        throw new Exception("Le body de la requête est mal formé.", 400);
    }

    // Récupération des variables.
    $id = isset($query['id']) ? (int) $query['id'] : '';
    $body = isset($body) ? $body : '';
    $filter = isset($query['filter']) ? (string) $query['filter'] : '%';
    $sort =  isset($query['sort']) ? (string) $query['sort'] : 'name';
    $perPage = isset($query['per_page']) ? (int) $query['per_page'] : 10;
    $page = isset($query['page']) ? (int) $query['page'] : 1;

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
            $resultat = create($body);
            break;
        case 'PUT':
        case 'PATCH':
            $resultat = update($id, $body);
            break;
        case 'DELETE':
            $resultat = delete($id);
            break;
    }

    echo json_encode($resultat);

} catch(Exception $e) {
    http_response_code($e->getCode());
    echo json_encode(['message' => $e->getMessage()]);
}