<?php

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
function deserializeIngredient(stdClass $body): Ingredient
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
    }

    if (isset($body->add)) {
        $tempIngredient->setAmountAdd($body->add);
    } else {
        $tempIngredient->setAmountAdd(null);
    }

    if (isset($body->attribute)) {
        $tempIngredient->setAmountAttribute($body->attribute);
    } else {
        $tempIngredient->setAmountAttribute(null);
    }
    return $tempIngredient;
}

