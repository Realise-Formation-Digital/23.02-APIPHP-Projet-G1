<?php

require_once("../models/Ingredient.php");
require_once("../controllers/baseController.php");

function read(int $id) {

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
function create(array $body) {

}

function update(int $id, array $body) {

}
// cette function est une partie de SCRUD, Delete pour delete un ingredient dans la table ingredient.
function delete(int $id) {
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
