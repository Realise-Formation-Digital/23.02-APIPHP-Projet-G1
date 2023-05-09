<?php

require_once("../models/Database.php");

class Ingredient extends Database
{

    private ?int $id;
    private ?string $type;
    private ?string $name;
    private ?float $amountValue;
    private ?string $amountUnit;
    private ?string $amountAdd;
    private ?string $amountAttribute;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getAmountValue(): ?string
    {
        return $this->amountValue;
    }

    public function setAmountValue(?float $amountValue): void
    {
        $this->amountValue = $amountValue;
    }

    public function getAmountUnit(): ?string
    {
        return $this->amountUnit;
    }

    public function setAmountUnit(?string $amountUnit): void
    {
        $this->amountUnit = $amountUnit;
    }

    public function getAmountAdd(): ?string
    {
        return $this->amountAdd;
    }

    public function setAmountAdd(?string $amountAdd): void
    {
        $this->amountAdd = $amountAdd;
    }

    public function getAmountAttribute(): ?string
    {
        return $this->amountAttribute;
    }

    public function setAmountAttribute(?string $amounAttribute): void
    {
        $this->amountAttribute = $amounAttribute;
    }


    /**
     * Method to get all ingredients from db
     *
     * @return array
     */
    public function search($perPage, $page, $sort, $filter): array
    {
        try {
            
            $stmt = $this->pdo->prepare('SELECT * FROM ingredients WHERE type LIKE :type ORDER BY :sort LIMIT :perPage OFFSET :page');
            $stmt->bindValue("perPage", $perPage, PDO::PARAM_INT);
            $stmt->bindValue("page", $perPage * ($page-1), PDO::PARAM_INT);
            $stmt->bindValue("sort", $sort);
            $stmt->bindValue("type", $filter);

            $stmt->execute();
           
            $ingredients = $stmt->fetchAll(PDO::FETCH_OBJ);
            //var_dump($ingredients);

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
    public function create(Ingredient $ingredient): Ingredient
    {
        try {
            //test if ingredient name exists (unique)
            $ingredientExist = $this->ingredientExistByNameType($ingredient->getName(), $ingredient->getType());
            if ($ingredientExist) {
                throw new Exception("L'ingrédient avec ce nom existe déjà.", 400);
            }

            $stmt = $this->pdo->prepare("INSERT INTO ingredients (type, name, amount_value, amount_unit, amount_add, amount_attribute) VALUES (:type, :name, :amount_value, :amount_unit, :amount_add, :amount_attribute)");
            $stmt->execute([
                "type" => $ingredient->getType(),
                "name" => $ingredient->getName(),
                "amount_value" => $ingredient->getAmountValue(),
                "amount_unit" => $ingredient->getAmountUnit(),
                "amount_add" => $ingredient->getAmountAdd(),
                "amount_attribute" => $ingredient->getAmountAttribute(),
            ]);
            $id = $this->pdo->lastInsertId();
            $ingredient->setId($id);
            return $ingredient;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Method to get a unique ingredient by id from db
     *
     * @param int $id
     * @return Ingredient
     */
    public function read(int $id): Ingredient
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM ingredients WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $ingredient = $stmt->fetch(PDO::FETCH_OBJ);

            //test if beer exists
            if (!$ingredient) {
                throw new Exception("L'ingrédient d'id $id n'existe pas", 400);
            }

            $ingredientObj = new Ingredient();
            $ingredientObj->setId($ingredient->id);
            $ingredientObj->setName($ingredient->name);
            $ingredientObj->setType($ingredient->type);
            $ingredientObj->setAmountValue($ingredient->amount_value);
            $ingredientObj->setAmountUnit($ingredient->amount_unit);
            $ingredientObj->setAmountAdd($ingredient->amount_add);
            $ingredientObj->setAmountAttribute($ingredient->amount_attribute);

            return $ingredientObj;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Method to update an ingredient by id in db
     * Connect to DB
     * prepare request
     * execute statement []
     * @param int $id
     * @return Ingredient
     */
    public function update(int $id, Ingredient $ingredient): Ingredient
    {
        try {
            //test if ingredient exists
            $this->read($id);

            $stmt = $this->pdo->prepare("UPDATE ingredients SET type = :type, name = :name, amount_value = :amountValue, amount_unit = :amountUnit, amount_add = :amountAdd, amount_attribute = :amountAttribute WHERE id = :id");
            $stmt->execute(
                [
                    "type" => $ingredient->getType(),
                    "name" => $ingredient->getName(),
                    "amountValue" => $ingredient->getAmountValue(),
                    "amountUnit" => $ingredient->getAmountUnit(),
                    "amountAdd" => $ingredient->getAmountAdd(),
                    "amountAttribute" => $ingredient->getAmountAttribute(),
                    "id" => $id
                ]);
            $ingredient->setId($id);
            return $ingredient;

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Method to delete an ingredient by id in db
     *
     * @param int $id
     * @return string
     */
    public function delete(int $id): string
    {
        try {
            //test if ingredient exists
            $this->read($id);

            $stmt = $this->pdo->prepare("DELETE FROM ingredients WHERE id = :id");
            $stmt->execute([
                "id" => $id
            ]);
            return "L'ingredient d'id $id a été supprimée";
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function ingredientExistByNameType(string $name, string $type): bool|Ingredient {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM ingredients WHERE name = :name AND type = :type");
            $stmt->execute([
                "name" => $name,
                "type" => $type
            ]);
            $ingredient = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$ingredient) {
                return false;
            } else {
                return $this->read($ingredient->id);
            }

        } catch (Exception $e) {
            throw $e;
        }
    }
}
