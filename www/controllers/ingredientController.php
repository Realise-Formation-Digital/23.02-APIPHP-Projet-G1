<?php

require_once("../models/Ingredient.php");
require_once("./baseController.php");

/**
 * Get a single object from the database.
 *
 * @param int $id
 * @return array
 */
function read(int $id): array
{
    $ingr = new Ingredient();
    $ingredient = $ingr->read($id);

    return serializeIngredient($ingredient);
}

//cette function est une partie de SCRUD, Search pour chercher un ingredient dans la table ingredient.
function search(): array
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
 * @throws Exception
 */
function create(stdClass $body): array
{
    /**
     * first decode the JSON format in body
     * Then create object newIngredient with the content of body
     * Finally return encode in JSON format
     */
    $ingredient = deserializeBody($body);
    $newIngredient = $ingredient->create($ingredient);
    return serializeIngredient($newIngredient);
}

/**
 * function to update ingrediant
 *
 */
function update(int $id, stdClass $body): array
{
    $ingredient = deserializeBody($body);
    $updatedIngredient = $ingredient->update($id, $ingredient);
    return serializeIngredient($updatedIngredient);
}

function delete(int $id): array
{
    $ingredient = new Ingredient();
    $message = $ingredient->delete($id);
    return ["message" => $message];
}

//avec cette function on a change JSON pour objet
function serializeIngredient(Ingredient $ingredient): array
{
    return [
        'id' => $ingredient->getId(),
        "type" => $ingredient->getType(),
        "name" => $ingredient->getName(),
        "amount" => [
            "value" => $ingredient->getAmountValue(),
            "unit" => $ingredient->getAmountUnit(),
            "add" => $ingredient->getAmountAdd(),
            "attribute" => $ingredient->getAmountAttribute()
        ]
    ];
}


/**
 * with this function we  change JSON for object
 */
function deserializeBody(stdClass $body): Ingredient
{
    $tempIngredient = new Ingredient();

    if (isset($body->type)) {
        $tempIngredient->setType($body->type);
    }

    if (isset($body->name)) {
        $tempIngredient->setName($body->name);
    }

    if (isset($body->amount)) {
        if (isset($body->amount->value)) {
            $tempIngredient->setAmountValue($body->amount->value);
        }

        if (isset($body->amount->unit)) {
            $tempIngredient->setAmountUnit($body->amount->unit);
        }

        if (isset($body->amount->add)) {
            $tempIngredient->setAmountAdd($body->amount->add);
        }

        if (isset($body->amount->attribute)) {
            $tempIngredient->setAmountAttribute($body->amount->attribute);
        }
    }
    return $tempIngredient;
}
