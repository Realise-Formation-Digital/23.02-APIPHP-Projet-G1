<?php

function serializeBeer(Beer $beer): array
{
    $year = substr($beer->getFirstBrewed(), 0, 4);
    $month = substr($beer->getFirstBrewed(), 5, 2);

    $maltIngredients = [];
    $hopsIngredients = [];
    foreach ($beer->getIngredients() as $ingredient) {
        if ($ingredient->getType() == "malt") {
            $maltIngredients[] = serializeBeerIngredient($ingredient);
        } else {
            $hopsIngredients[] = serializeBeerIngredient($ingredient);
        }
    }

    return [
        'id' => $beer->getId(),
        'name' => $beer->getName(),
        "tagline" => $beer->getTagline(),
        "first_brewed" => $month . "/" . $year,
        "description" => $beer->getDescription(),
        "image_url" => $beer->getImageUrl(),
        "food_pairing" => [
            $beer->getFoodPairing1(),
            $beer->getFoodPairing2(),
            $beer->getFoodPairing3()
        ],
        "brewers_tips" => $beer->getBrewersTips(),
        "contribued_by" => $beer->getContributedBy(),
        "ingredients" => ["malt" => $maltIngredients, "hops" => $hopsIngredients]
    ];
}

/**
 * @throws Exception
 */
function deserializeBeer(stdClass $body): Beer
{
    $tempBeer = new Beer();

    if (isset($body->name)) {
        $tempBeer->setName($body->name);

        if (strlen($body->name) > 25) {
            $tempBeer->setName(substr($body->description, 0, 25));
        }
    } else {
        throw new Exception("Le nom ne peut pas être nul.", 400);
    }

    if (isset($body->tagline)) {
        $tempBeer->setTagline($body->tagline);

        if (strlen($body->tagline) > 50) {
            $tempBeer->setTagline(substr($body->description, 0, 50));
        }
    } else {
        throw new Exception("Le tagline ne peut pas être nul.", 400);
    }

    if (isset($body->first_brewed)) {

// test if format date like 05/2023
        if (!preg_match("/^[0-9]{2}\/[0-9]{4}$/", $body->first_brewed)) {
            throw new Exception("La date doit être au format MM/YYYY. Exemple: 05/2023");
        }

        $year = substr($body->first_brewed, 3, 4);
        $month = substr($body->first_brewed, 0, 2);
        $tempBeer->setFirstBrewed($year . "-" . $month . "-" . "01");

    } else {
        throw new Exception("Le first brewed ne peut pas être nul.", 400);
    }

    if (isset($body->description)) {
        $tempBeer->setDescription($body->description);

        if (strlen($body->description) > 250) {
            $tempBeer->setDescription(substr($body->description, 0, 250));
        }
    } else {
        throw new Exception("La description ne peut pas être nulle.", 400);
    }

    if (isset($body->image_url)) {
        $tempBeer->setImageUrl($body->image_url);

        if (strlen($body->image_url) > 500) {
            throw new Exception("L'url de l'image de la bière ne peut pas avoir plus de 500 caractères", 400);
        }
    } else {
        throw new Exception("L'url de l'image peut pas être nulle.", 400);
    }

    if (isset($body->brewers_tips)) {
        $tempBeer->setBrewersTips($body->brewers_tips);

        if (strlen($body->brewers_tips) > 500) {
            throw new Exception("Le brewer tips de la bière ne peut pas avoir plus de 500 caractères", 400);
        }
    } else {
        throw new Exception("Le brewer tips ne peut pas être nul.", 400);
    }

    if (isset($body->contributed_by)) {
        $tempBeer->setContributedBy($body->contributed_by);

        if (strlen($body->contributed_by) > 50) {
            throw new Exception("La contribution de la bière ne peut pas avoir plus de 50 caractères", 400);
        }
    } else {
        throw new Exception("La contribution ne peut pas être nulle.", 400);
    }

    if (isset($body->food_pairing)) {
        if (isset($body->food_pairing[0])) {
            $tempBeer->setFoodPairing1($body->food_pairing[0]);

            if (strlen($body->food_pairing[0]) > 50) {
                $tempBeer->setFoodPairing1(substr($body->food_pairing[0], 0, 50));
            }
        }

        if (isset($body->food_pairing[1])) {
            $tempBeer->setFoodPairing2($body->food_pairing[1]);

            if (strlen($body->food_pairing[1]) > 50) {
                $tempBeer->setFoodPairing2(substr($body->food_pairing[1], 0, 50));
            }
        }

        if (isset($body->food_pairing[2])) {
            $tempBeer->setFoodPairing3($body->food_pairing[2]);

            if (strlen($body->food_pairing[2]) > 50) {
                $tempBeer->setFoodPairing3(substr($body->food_pairing[2], 0, 50));
            }
        }

    }
    return $tempBeer;
}

//avec cette function on a change JSON pour objet
function serializeBeerIngredient(Ingredient $ingredient): array
{
    return [
        'id' => $ingredient->getId(),
        "name" => $ingredient->getName(),
        "amount" => [
            "value" => $ingredient->getAmountValue(),
            "unit" => $ingredient->getAmountUnit(),
            "add" => $ingredient->getAmountAdd(),
            "attribute" => $ingredient->getAmountAttribute()
        ]
    ];
}
