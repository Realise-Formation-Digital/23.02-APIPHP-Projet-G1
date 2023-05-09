<?php

require_once("../models/Beer.php");
require_once("../serializers/beerSerializer.php");
require_once("./baseController.php");

/**
 * GET a beer by a id
 * Instantciation of a beer
 * read the beer selected with the instanciation
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

