<?php

require_once("../models/Beer.php");
require_once("../serializers/beerSerializer.php");
require_once("./baseController.php");

/**
 * Instantciation of a beer
 * read selected beer
 * return the serialize version of selected beer.
 */
function read(int $id)
{
    $beerI = new Beer();
    $beerSelected = $beerI->read($id);
    return serializeBeer($beerSelected);
}

/**
 * @throws Exception
 */
function search($perPage, $page, $sort, $filter): array 
{
    if ($page <= 0) {
        throw new Exception("La page commence à 1.", 400);
    }

    if ($perPage <= 0) {
        throw new Exception("Il doit y avoir au moins une bière par page", 400);
    }

    if ($sort != "name") {
        throw new Exception("On ne peut trier que par nom (name).", 400);
    }

    $beer = new Beer();
    $beers = $beer->search($perPage, $page, $sort, $filter);

    $serializedBeers = [];
    foreach ($beers as $beer) {
        $serializedBeers[] = serializeBeer($beer);
    }
    return $serializedBeers;   
}

/**
 * @throws Exception
 */
function create(stdClass $body): array
{
    $beer = deserializeBeer($body);
    $newBeer = $beer->create($beer);
    return serializeBeer($newBeer);
}

/**
 * @throws Exception
 */
function update(int $id, stdClass $body): array
{
    $beer = deserializeBeer($body);
    $updatedBeer = $beer->update($id, $beer);
    return serializeBeer($updatedBeer);
}

/**
 * @throws Exception
 */
function delete(int $id): array
{
    $beer = new Beer();
    $message = $beer->delete($id);
    return ["message" => $message];
}

function addIngredient(int $beerId, int $ingredientId): array {
    $beer = new Beer();
    $newBeer = $beer->addIngredient($beerId, $ingredientId);
    return serializeBeer($newBeer);
}

function removeIngredient(int $beerId, int $ingredientId): array {
    $beer = new Beer();
    $beer = $beer->removeIngredient($beerId, $ingredientId);
    return serializeBeer($beer);
}

