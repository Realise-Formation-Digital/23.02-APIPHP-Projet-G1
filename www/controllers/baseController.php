<?php

parse_str($_SERVER['QUERY_STRING'], $query);
$body = json_decode(file_get_contents('php://input'));
$method = $_SERVER['REQUEST_METHOD'];

try {
    header('Content-Type:application/json;charset=utf-8');

    // Récupération des variables.
    $id = isset($query['id']) ? $query['id'] : '';
    $body = isset($body) ? $body : '';

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
    echo json_encode(['message' => $e->getMessage()]);
}