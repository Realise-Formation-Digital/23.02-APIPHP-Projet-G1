<?php

require_once("./Database.php");

class Ingredient extends Database
{
    /**
     * Method to get all ingredients from db
     *
     * @return array
     */
    public function search(): array {
        return [];
    }

    /**
     * Method to add an ingredient in db
     *
     * @param Ingredient $ingredient
     * @return Ingredient
     */
    public function create(Ingredient $ingredient): Ingredient {

    }

    /**
     * Method to get a unique ingredient by id from db
     *
     * @param int $id
     * @return Ingredient
     */
    public function read(int $id): Ingredient {

    }

    /**
     * Method to update an ingredient by id in db
     *
     * @param int $id
     * @return Ingredient
     */
    public function update(int $id): Ingredient {

    }

    /**
     * Method to delete an ingredient by id in db
     *
     * @param int $id
     * @return string
     */
    public function delete(int $id): string {

    }

}