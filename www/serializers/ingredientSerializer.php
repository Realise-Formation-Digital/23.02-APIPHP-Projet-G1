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
        ],
        "add" => $ingredient->getAmountAdd(),
        "attribute" => $ingredient->getAmountAttribute()
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

        if ($body->type != "malt" && $body->type != "hops") {
            throw new Exception("Le type ne peut être que malt ou hops.", 400);
        }
    } else {
        throw new Exception("Le type ne peut pas être nul.", 400);
    }

    if (isset($body->name)) {
        $tempIngredient->setName($body->name);

        if (strlen($body->name) > 50) {
            $tempIngredient->setName(substr($body->name, 0, 50));
        }
    } else {
        throw new Exception("Le nom ne peut pas être nul.", 400);
    }

    if (isset($body->amount)) {
        if (isset($body->amount->value)) {
            $tempIngredient->setAmountValue($body->amount->value);
        } else {
            throw new Exception("Le amount_value ne peut pas être nul.", 400);
        }

        if (isset($body->amount->unit)) {
            $tempIngredient->setAmountUnit($body->amount->unit);

            if (strlen($body->amount->unit) > 15) {
                $tempIngredient->setAmountUnit(substr($body->amount->unit, 0, 15));
            }
        } else {
            throw new Exception("Le amount_add ne peut pas être nul.", 400);
        }
    }

    if (isset($body->add)) {
        $tempIngredient->setAmountAdd($body->add);

        if (strlen($body->add) > 15) {
            $tempIngredient->setAmountAdd(substr($body->add, 0, 15));
        }
    } else {
        $tempIngredient->setAmountAdd(null);
    }

    if (isset($body->attribute)) {
        $tempIngredient->setAmountAttribute($body->attribute);

        if (strlen($body->attribute) > 15) {
            $tempIngredient->setAmountAttribute(substr($body->attribute, 0, 15));
        }
    } else {
        $tempIngredient->setAmountAttribute(null);
    }
    return $tempIngredient;
}

