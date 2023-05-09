<?php

require_once("../models/Ingredient.php");
require_once("../serializers/ingredientSerializer.php");
require_once("./baseController.php");

/**
 * Get a single object from the database.
 *
 * @param int $id
 * @return array
 * @throws Exception
 */
function read(int $id): array
{
    $ingr = new Ingredient();
    $ingredient = $ingr->read($id);

    return serializeIngredient($ingredient);
}

/**
 * Cette function est une partie de SCRUD, Search pour chercher un ingredient dans la table ingredient.
 *
 * @param $perPage
 * @param $page
 * @param $sort
 * @param $filter
 * @return array
 * @throws Exception
 */
function search($perPage, $page, $sort, $filter): array
{
    $ingredient = new Ingredient();
    $ingredients = $ingredient->search($perPage, $page, $sort, $filter);
    $serializedIngredients = [];
    foreach ($ingredients as $ingredient) {
        $serializedIngredients[] = serializeIngredient($ingredient);
    }
    return $serializedIngredients;
}

/**
 * Function that create an array of ingredient
 * @throws Exception
 */
function create(stdClass $body): array
{
    /**
     * first decode the JSON format in body
     * Then create object newIngredient with the content of body
     * Finally return encode in JSON format
     */
    $ingredient = deserializeIngredient($body);
    $newIngredient = $ingredient->create($ingredient);
    return serializeIngredient($newIngredient);
}

/**
 * function to update ingrediant
 *
 */
function update(int $id, stdClass $body): array
{
    $ingredient = deserializeIngredient($body);
    $updatedIngredient = $ingredient->update($id, $ingredient);
    return serializeIngredient($updatedIngredient);
}

function delete(int $id): array
{
    $ingredient = new Ingredient();
    $message = $ingredient->delete($id);
    return ["message" => $message];
}