<?php

require_once("../models/Beer.php");
require_once("./baseController.php");

function read(int $id) {

}

/**
 * @throws Exception
 */
function search(): array {
    $beer = new Beer();
    $beers = $beer->search();

    $serializedBeers = [];
    foreach($beers as $beer) {
        $serializedBeers[] = serializeBeer($beer);
    }
    return $serializedBeers;
}
function create(array $body) {

}

function update(int $id, array $body) {

}

function delete(int $id) {

}

function serializeBeer(Beer $beer): array {
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

