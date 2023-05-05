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
        $stmt = $this->pdo->prepare("SELECT * FROM ingredients WHERE :id");
        $stmt->execute(['id' => $id]);
        $ingredient = $stmt->fetch(PDO::FETCH_OBJ);
        var_dump($ingredient);

        $ingredientObj = new Ingredient();
        $ingredientObj->setId($ingredient->id);
        $ingredientObj->setName($ingredient->name);
        $ingredientObj->setType($ingredient->type);
        $ingredientObj->setAmountValue($ingredient->amount_value);
        $ingredientObj->setAmountUnit($ingredient->amount_unit);
        $ingredientObj->setAmountAdd($ingredient->amount_add);
        $ingredientObj->setAmountAttribute($ingredient->amount_attribute);
      
        var_dump($ingredientObj);
        
        return $ingredientObj;
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