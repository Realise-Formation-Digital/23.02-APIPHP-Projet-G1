<?php

require_once("../models/Beer.php");
require_once("../models/Ingredient.php");
require_once("../serializers/beerSerializer.php");
require_once("../serializers/ingredientSerializer.php");
require_once("./baseController.php");

/**
 * GET a beer by a id
 * Instantciation of a beer
 * read the beer selected with the instanciation
 * return the serialize version of selected beer.
 */
function read(int $id)
{
    throw new Exception("Il n'y a qu'une méthode POST pour cette route.", 404);
}

/**
 * @throws Exception
 */
function search(): array
{
    throw new Exception("Il n'y a qu'une méthode POST pour cette route.", 404);
}

/**
 * @throws Exception
 */
function create(array $body): array
{
    foreach($body as $beerStdObj) {
        // deserialize beer
        $beer = deserializeBeer($beerStdObj);
        $beerExist = $beer->beerExistByName($beer->getName());

        // create beer if it doesn't exist
        if(!$beerExist) {
            $beer = $beer->create($beer);
        } else {
            $beer = $beerExist;
        }

        foreach($beerStdObj->ingredients->malt as $ingredientStdObj) {
            $ingredientStdObj->type = "malt";

            //test if ingredient exists
            $ingredient = deserializeIngredient($ingredientStdObj);
            $newIngredient = new Ingredient();
            $ingredientExist = $newIngredient->ingredientExistByNameType($ingredient->getName(), $ingredient->getType());

            // create ingredient if it doesn't exist
            if(!$ingredientExist) {
                $ingredient = $newIngredient->create($ingredient);
            } else {
                $ingredient = $ingredientExist;
            }

            // add ingredient to beer
            $beer->addIngredient($beer->getId(), $ingredient->getId());

        }

        foreach($beerStdObj->ingredients->hops as $ingredient) {
            $ingredient->type = "hops";

            //test if ingredient exists
            $ingredient = deserializeIngredient($ingredientStdObj);
            $newIngredient = new Ingredient();
            $ingredientExist = $newIngredient->ingredientExistByNameType($ingredient->getName(), $ingredient->getType());

            // create ingredient if it doesn't exist
            if(!$ingredientExist) {
                $ingredient = $newIngredient->create($ingredient);
            } else {
                $ingredient = $ingredientExist;
            }

            // add ingredient to beer
            $beer->addIngredient($beer->getId(), $ingredient->getId());
        }
    }

    $beer = new Beer();
    $beers = $beer->search();

    $serializedBeers = [];
    foreach ($beers as $beer) {
        $serializedBeers[] = serializeBeer($beer);
    }
    return $serializedBeers;
}

/**
 * @throws Exception
 */
function update(int $id, stdClass $body): array
{
    throw new Exception("Il n'y a qu'une méthode POST pour cette route.", 404);
}

/**
 * @throws Exception
 */
function delete(int $id): array
{
    throw new Exception("Il n'y a qu'une méthode POST pour cette route.", 404);
}