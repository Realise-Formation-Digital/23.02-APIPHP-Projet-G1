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
    return [
        'id' => $beer->getId(),
        'name' => $beer->getName(),
        "tagline" => $beer->getTagline(),
        "first_brewed" => $beer->getFirstBrewed(),
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

function deserializeBody(stdClass $body): Beer {
    $tempBeer = new Beer();

    if (isset($body->id)) {
        $tempBeer->setId($body->id);
    }

    if (isset($body->name)) {
        $tempBeer->setName($body->name);
    }

    if (isset($body->tagline)) {
        $tempBeer->setTagline($body->tagline);
    }

    if (isset($body->first_brewed)) {
        $tempBeer->setFirstBrewed($body->first_brewed);
    }

    if (isset($body->description)) {
        $tempBeer->setDescription($body->description);
    }

    if (isset($body->image_url)) {
        $tempBeer->setImageUrl($body->image_url);
    }

    if (isset($body->brewers_tips)) {
        $tempBeer->setBrewersTips($body->brewers_tips);
    }

    if (isset($body->contribued_by)) {
        $tempBeer->setContribuedBy($body->contribued_by);
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

