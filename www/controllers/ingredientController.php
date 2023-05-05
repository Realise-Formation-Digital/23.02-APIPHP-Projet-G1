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
function create(array $body)
{
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
