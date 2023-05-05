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
/**
 * function to update ingrediant
 * 
 */
function update(int $id, stdClass $body)
{
    $ingredient = deserializeBody($body);
    $updatedIngredient =$ingredient->update($id, $ingredient);
    return serializeIngredient($updatedIngredient);
}

function delete(int $id)
{
}
/**
 * with this function we change object for JSON
 * 
 * 
 */
function serializeIngredient(Ingredient $ingr): array
{
    return [
        'id' => $ingr->getId(),
        'name' => $ingr->getName(),
        "type" => $ingr->getType(),
        "amount_value" => $ingr->getAmountValue(),
        "amount_unit" => $ingr->getAmountUnit(),
        "amount_add" => $ingr->getAmountAdd(),
        "amount_attribute" => $ingr->getAmountAttribute()
    ];
}
/**
 * with this function we  change JSON for object
 */
function deserializeBody(stdClass $body): Ingredient {
    $tempIngredient = new Ingredient();

    if(isset($body->id)) {
        $tempIngredient->setId($body->id);
    }

    if(isset($body->type)) {
        $tempIngredient->setType($body->type);
    }

    if(isset($body->name)) {
        $tempIngredient->setName($body->name);
    }

    if(isset($body->amount_value)) {
        $tempIngredient->setAmountValue($body->amount_value);
    }

    if(isset($body->amount_unit)) {
        $tempIngredient->setAmountUnit($body->amount_unit);
    }

    if(isset($body->amount_add)) {
        $tempIngredient->setAmountAdd($body->amount_add);
    }

    if(isset($body->amount_attribute)) {
        $tempIngredient->setAmountAttribute($body->amount_attribute);
    }
    return $tempIngredient;
}
