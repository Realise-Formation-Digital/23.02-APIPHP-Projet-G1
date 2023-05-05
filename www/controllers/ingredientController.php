<?php
require_once("../models/Ingredient.php");
require_once("./baseController.php");

 /**
     * Get a single object from the database.
     * 
     * @return object
     */
function read(int $id)
{
    $ingr = new Ingredient();
    $ingredient = $ingr->read($id);
    
    return serializeIngredient($ingredient);
}

function search()
{
}

/**
 * Function that create an array of ingredient
 */
function create(stdClass $body)
{
    /**
     * first decode the JSON format in body
     * Then create object newIngredient with the content of body
     * Finally return encode in JSON format 
     */
    $ingredient = deserializeBody($body);
    $newIngredient = $ingredient->create($ingredient);
    return serializeBeer ($newIngredient);
}

function update(int $id, array $body)
{
}

function delete(int $id)
{
}

function serializeIngredient(Ingredient $ingr): array
{
    return [
        'id' => $ingr->getId(),
        'name' => $ingr->getName(),
        "type" => $ingr->getType(),
        "amountValue" => $ingr->getAmountValue(),
        "amountUnit" => $ingr->getAmountUnit(),
        "amountAdd" => $ingr->getAmountAdd(),
        "amountAttribute" => $ingr->getAmountAttribute()
    ];
}
