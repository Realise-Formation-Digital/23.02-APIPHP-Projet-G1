<?php

require_once("../models/Beer.php");
require_once("../models/Ingredient.php");
require_once("./baseController.php");

/**
 * GET a beer by a id
 * Instantciation of a beer
 * read the beer selected with the instanciation
 * return the serialize version of selected beer.
 */
function read(int $id)
{
    throw new Exception("Il n'y a qu'une méthode POST pour cette route.", 404);
}

/**
 * @throws Exception
 */
function search(): array
{
    throw new Exception("Il n'y a qu'une méthode POST pour cette route.", 404);
}

/**
 * @throws Exception
 */
function create(stdClass $body): array
{

}

/**
 * @throws Exception
 */
function update(int $id, stdClass $body): array
{
    throw new Exception("Il n'y a qu'une méthode POST pour cette route.", 404);
}

/**
 * @throws Exception
 */
function delete(int $id): array
{
    throw new Exception("Il n'y a qu'une méthode POST pour cette route.", 404);
}