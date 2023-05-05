<?php

require_once("../models/Database.php");

class Ingredient extends Database
{
    private ?int $id;
    private $type;
    private $name;
    private $amountValue;
    private $amountUnit;
    private $amountAdd;
    private $amountAttribute;


    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id) {
        $this->id = $id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getAmountValue()
    {
        return $this->amountValue;
    }

    public function setAmountValue($amountValue)
    {
        $this->amountValue = $amountValue;
    }

    public function getAmountUnit()
    {
        return $this->amountUnit;
    }

    public function setAmountUnit($amountUnit)
    {
        $this->amountUnit = $amountUnit;
    }

    public function getAmountAdd()
    {
        return $this->amountAdd;
    }

    public function setAmountAdd($amountAdd)
    {
        $this->amountAdd = $amountAdd;
    }

    public function getAmountAttribute()
    {
        return $this->amountAttribute;
    }

    public function setAmountAttribute($amounAttribute)
    {
        $this->amountAttribute = $amounAttribute;
    }
    /**
     * Method to get all ingredients from db
     *
     * @return array
     */
    public function search(): array {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM ingredients');
            $stmt->execute();
            $ingredients = $stmt->fetchAll(PDO::FETCH_OBJ);

            $ingredientsObj = [];
            foreach ($ingredients as $ingredient) {

                $tempIngredient = new Ingredient();
                $tempIngredient->setId($ingredient->id);
                $tempIngredient->setType($ingredient->type);
                $tempIngredient->setName($ingredient->name);
                $tempIngredient->setAmountValue($ingredient->amount_value);
                $tempIngredient->setAmountUnit($ingredient->amount_unit);
                $tempIngredient->setAmountAdd($ingredient->amount_add);
                $tempIngredient->setAmountAttribute($ingredient->amount_attribute);
                $ingredientsObj[] = $tempIngredient;

            }
            return $ingredientsObj;
        } catch (Exception $e) {
            throw $e;
        }
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
        try {
            $stmt = $this->pdo->prepare("DELETE FROM ingredients WHERE id = :id");
            $stmt->execute([
                "id" => $id
            ]);
            return "L'ingredient d'id $id a été supprimée";
        } catch(Exception $e) {
            throw $e;
        }
    }

}