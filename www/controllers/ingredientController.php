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
//cette function est une partie de SCRUD, Search pour chercher un ingredient dans la table ingredient.
function search():array
{

$ingredient = new Ingredient();
$ingredients = $ingredient->search();
$serializedIngredients = [];
foreach ($ingredients as $ingredient) {
    $serializedIngredients[] = serializeIngredient($ingredient);
}
return $serializedIngredients;
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
$serializedIngredients = [];
foreach ($ingredients as $ingredient) {
    $serializedIngredients[] = serializeIngredient($ingredient);
}
return $serializedIngredients;
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
    $ingredient = new Ingredient();
    $message = $ingredient->delete($id);
    return ["message" => $message];
}
//avec cette function on a change JSON pour objet
function serializeIngredient(Ingredient $ingredient): array
{
    return [
        'id'=>$ingredient->getId(),
        "type"=>$ingredient->getType(),
        "name"=>$ingredient->getName(),
        "amount_value"=>$ingredient->getAmountValue(),
        "amount_unit"=>$ingredient->getAmountUnit(),
        "amount_add"=>$ingredient->getAmountAdd(),
        "amount_attribute"=>$ingredient->getAmountAttribute()
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
