<?php

require_once("../models/Beer.php");
require_once("./baseController.php");

/**
 * GET a beer by a id
 * Instantciation of a beer
 * read the beer selected with the instanciation
 * return the serialize version of selected beer.
 */
function read(int $id)
{
    $beerI = new Beer();
    $beerSelected = $beerI->read($id);
    return serializeBeer($beerSelected);
}

/**
 * @throws Exception
 */
function search(): array
{
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
function create(stdClass $body): array
{
    $beer = deserializeBody($body);
    $newBeer = $beer->create($beer);
    return serializeBeer($newBeer);
}

/**
 * @throws Exception
 */
function update(int $id, stdClass $body): array
{
    $beer = deserializeBody($body);
    $updatedBeer = $beer->update($id, $beer);
    return serializeBeer($updatedBeer);
}

/**
 * @throws Exception
 */
function delete(int $id): array
{
    $beer = new Beer();
    $message = $beer->delete($id);
    return ["message" => $message];
}

function serializeBeer(Beer $beer): array
{
    $year = substr($beer->getFirstBrewed(), 0, 4);
    $month = substr($beer->getFirstBrewed(), 5, 2);
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
        "contribued_by" => $beer->getContribuedBy()
    ];
}

/**
 * @throws Exception
 */
function deserializeBody(stdClass $body): Beer {
    $tempBeer = new Beer();

    if (isset($body->name)) {
        $tempBeer->setName($body->name);

        if (strlen($body->name) > 25) {
            throw new Exception("Le nom de la bière ne peut pas avoir plus de 25 caractères", 400);
        }
    }

    if (isset($body->tagline)) {
        $tempBeer->setTagline($body->tagline);

        if (strlen($body->tagline) > 50) {
            throw new Exception("Le tagline de la bière ne peut pas avoir plus de 50 caractères", 400);
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
            throw new Exception("La description de la bière ne peut pas avoir plus de 250 caractères", 400);
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

    if (isset($body->contribued_by)) {
        $tempBeer->setContribuedBy($body->contribued_by);

        if (strlen($body->contribued_by) > 50) {
            throw new Exception("La contribution de la bière ne peut pas avoir plus de 50 caractères", 400);
        }
    } else {
        throw new Exception("La contribution ne peut pas être nulle.", 400);
    }

    if (isset($body->food_pairing)) {
        if(isset($body->food_pairing[0])) {
            $tempBeer->setFoodPairing1($body->food_pairing[0]);
        }

        if(isset($body->food_pairing[1])) {
            $tempBeer->setFoodPairing2($body->food_pairing[1]);
        }

        if(isset($body->food_pairing[2])) {
            $tempBeer->setFoodPairing3($body->food_pairing[2]);
        }

    }
    return $tempBeer;
}

