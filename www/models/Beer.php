<?php

require_once("../models/Database.php");
require_once("../models/Ingredient.php");
require_once("../controllers/beerController.php");

class Beer extends Database
{

    private ?int $id;

    private ?string $name;

    private string $tagline;

    private string $firstBrewed;

    private string $description;

    private string $imageUrl;

    private string $brewersTips;

    private string $contributedBy;

    private ?string $foodPairing1;

    private ?string $foodPairing2;

    private ?string $foodPairing3;

    private array $ingredients = [];
    
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
   

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTagline(): string
    {
        return $this->tagline;
    }

    /**
     * @param string $tagline
     */
    public function setTagline(string $tagline): void
    {
        $this->tagline = $tagline;
    }

    /**
     * @return string
     */
    public function getFirstBrewed(): string
    {
        return $this->firstBrewed;
    }

    /**
     * @param string $firstBrewed
     */
    public function setFirstBrewed(string $firstBrewed): void
    {
        $this->firstBrewed = $firstBrewed;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     */
    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return string
     */
    public function getBrewersTips(): string
    {
        return $this->brewersTips;
    }

    /**
     * @param string $brewersTips
     */
    public function setBrewersTips(string $brewersTips): void
    {
        $this->brewersTips = $brewersTips;
    }

    /**
     * @return string
     */
    public function getContributedBy(): string
    {
        return $this->contributedBy;
    }

    /**
     * @param string $contributedBy
     */
    public function setContributedBy(string $contributedBy): void
    {
        $this->contributedBy = $contributedBy;
    }

    /**
     * @return string|null
     */
    public function getFoodPairing1(): ?string
    {
        return $this->foodPairing1;
    }

    /**
     * @param string|null $foodPairing1
     */
    public function setFoodPairing1(?string $foodPairing1): void
    {
        $this->foodPairing1 = $foodPairing1;
    }

    /**
     * @return string|null
     */
    public function getFoodPairing2(): ?string
    {
        return $this->foodPairing2;
    }

    /**
     * @param string|null $foodPairing2
     */
    public function setFoodPairing2(?string $foodPairing2): void
    {
        $this->foodPairing2 = $foodPairing2;
    }

    /**
     * @return string|null
     */
    public function getFoodPairing3(): ?string
    {
        return $this->foodPairing3;
    }

    /**
     * @param string|null $foodPairing3
     */
    public function setFoodPairing3(?string $foodPairing3): void
    {
        $this->foodPairing3 = $foodPairing3;
    }


    public function getIngredients(): array
    {
        return $this->ingredients;
    }

   
    public function setIngredients(array $ingredients): void
    {
        $this->ingredients = $ingredients;
    }


    /**
     * Method to get all beers from db
     *
     * @return array
     * @throws Exception
     */
    public function search($perPage, $page, $sort, $filter): array
    {
        try {
            //Filter : recover beer with ingredients, order by asc,
            $stmt = $this->pdo->prepare ("SELECT * FROM beers LEFT JOIN beer_ingredient ON beers.id = beer_ingredient.ingredient_id JOIN ingredients ON ingredients.id = beer_ingredient.ingredient_id WHERE ingredients.name = :filter ORDER BY beers.$sort LIMIT :perPage OFFSET :page");
            $stmt->bindValue("perPage", $perPage, PDO::PARAM_INT);
            $stmt->bindValue("page", $perPage *($page-1), PDO::PARAM_INT);
            $stmt->bindValue("filter", $filter);
            $stmt->execute();

            $beers = $stmt->fetchAll(PDO::FETCH_OBJ);

            $beersObj = [];
            foreach ($beers as $beer) {
                // recover ingredients from beer
                $stmt = $this->pdo->prepare("SELECT * FROM beer_ingredient WHERE beer_id = :id");
                $stmt->execute([
                    'id' => $beer->id
                ]);
                $beersIngredients = $stmt->fetchAll(PDO::FETCH_OBJ);
                $ingredients = [];
                foreach($beersIngredients as $beerIngredient){
                    $tempIngredient = new Ingredient();
                    $tempIngredient = $tempIngredient->read($beerIngredient->ingredient_id);
                    $ingredients[] = $tempIngredient;
                }

                $tempBeer = new Beer();
                $tempBeer->setId($beer->id);
                $tempBeer->setName($beer->name);
                $tempBeer->setTagline($beer->tagline);
                $tempBeer->setFirstBrewed($beer->first_brewed);
                $tempBeer->setDescription($beer->description);
                $tempBeer->setImageUrl($beer->image_url);
                $tempBeer->setBrewersTips($beer->brewers_tips);
                $tempBeer->setContributedBy($beer->contributed_by);
                $tempBeer->setFoodPairing1($beer->food_pairing1);
                $tempBeer->setFoodPairing2($beer->food_pairing2);
                $tempBeer->setFoodPairing3($beer->food_pairing3);
                $tempBeer->setIngredients($ingredients);
                $beersObj[] = $tempBeer;
            }

            return $beersObj;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Method to add a beer in db
     *
     * @param Beer $beer
     * @return Beer
     * @throws Exception
     */
    public function create(Beer $beer): Beer
    {
        try {
            //test if beer name exists (unique)
            $beerExist = $this->beerExistByName($beer->getName());
            if ($beerExist) {
                throw new Exception("La bière avec ce nom existe déjà.", 400);
            }

            $stmt = $this->pdo->prepare("INSERT INTO beers (name, tagline, first_brewed, description, image_url, brewers_tips, contributed_by, food_pairing1, food_pairing2, food_pairing3) VALUES (:name, :tagline, :first_brewed, :description, :image_url, :brewers_tips, :contributed_by, :food_pairing1, :food_pairing2, :food_pairing3)");
            $stmt->execute([
                "name" => $beer->getName(),
                "tagline" => $beer->getTagline(),
                "first_brewed" => $beer->getFirstBrewed(),
                "description" => $beer->getDescription(),
                "image_url" => $beer->getImageUrl(),
                "brewers_tips" => $beer->getBrewersTips(),
                "contributed_by" => $beer->getContributedBy(),
                "food_pairing1" => $beer->getFoodPairing1(),
                "food_pairing2" => $beer->getFoodPairing2(),
                "food_pairing3" => $beer->getFoodPairing3(),
            ]);
            $id = $this->pdo->lastInsertId();
            $beer->setId($id);
            return $beer;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Method to get a unique beer by id from db
     *
     * @param int $id
     * @return Beer
     * @throws Exception
     */
    public function read(int $id): Beer
    {
        try {
           
            $stmt = $this->pdo->prepare("SELECT * FROM beers WHERE id = :id");
            $stmt->execute([
                'id' => $id
            ]);
            $beer = $stmt->fetch(PDO::FETCH_OBJ);

            //test if beer exists
            if (!$beer) {
                throw new Exception("La bière d'id $id n'existe pas", 400);
            }

            $stmt = $this->pdo->prepare("SELECT * FROM beer_ingredient WHERE beer_id = :id");
            $stmt->execute([
                'id' => $id
            ]);
            $beersIngredients = $stmt->fetchAll(PDO::FETCH_OBJ);

            $ingredients = [];
            foreach($beersIngredients as $beerIngredient){
                $tempIngredient = new Ingredient();
                $tempIngredient = $tempIngredient->read($beerIngredient->ingredient_id);
                $ingredients[] = $tempIngredient;
            }

            $beerObj = new Beer();
            $beerObj->setId($beer->id);
            $beerObj->setName($beer->name);
            $beerObj->setTagline($beer->tagline);
            $beerObj->setFirstBrewed($beer->first_brewed);
            $beerObj->setDescription($beer->description);
            $beerObj->setImageUrl($beer->image_url);
            $beerObj->setBrewersTips($beer->brewers_tips);
            $beerObj->setContributedBy($beer->contributed_by);
            $beerObj->setFoodPairing1($beer->food_pairing1);
            $beerObj->setFoodPairing2($beer->food_pairing2);
            $beerObj->setFoodPairing3($beer->food_pairing3);
            $beerObj->setIngredients($ingredients);

            return $beerObj;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Method to update a beer by id in db
     *
     * @param int $id
     * @param Beer $beer
     * @return Beer
     * @throws Exception
     */
    public function update(int $id, Beer $beer): Beer
    {
        //test if beer exists
        $this->read($id);

        try {
            $stmt = $this->pdo->prepare("UPDATE beers SET name = :name, tagline = :tagline, first_brewed = :first_brewed, description = :description, image_url = :image_url, brewers_tips = :brewers_tips, contributed_by = :contributed_by, food_pairing1 = :food_pairing1, food_pairing2 = :food_pairing2, food_pairing3 = :food_pairing3 WHERE id = :id");
            $stmt->execute([
                "name" => $beer->getName(),
                "tagline" => $beer->getTagline(),
                "first_brewed" => $beer->getFirstBrewed(),
                "description" => $beer->getDescription(),
                "image_url" => $beer->getImageUrl(),
                "brewers_tips" => $beer->getBrewersTips(),
                "contributed_by" => $beer->getContributedBy(),
                "food_pairing1" => $beer->getFoodPairing1(),
                "food_pairing2" => $beer->getFoodPairing2(),
                "food_pairing3" => $beer->getFoodPairing3(),
                "id" => $id
            ]);

            // test if updated
            $count = $stmt->rowCount();
            if ($count == 0) {
                throw new Exception("La bière n'a pas été mise-à-jour.", 400);
            }

            $beer->setId($id);
            return $beer;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Method to delete beer by id in db
     *
     * @param int $id
     * @return string
     * @throws Exception
     */
    public function delete(int $id): string
    {
        //test if beer exists
        $this->read($id);

        try {
            $stmt = $this->pdo->prepare("DELETE FROM beers WHERE id = :id");
            $stmt->execute([
                "id" => $id
            ]);

            // test if deleted
            $count = $stmt->rowCount();
            if ($count == 0) {
                throw new Exception("La bière n'a pas été supprimée.", 400);
            }

            return "La bière d'id $id été supprimée";
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**Foreign key -add ingredient to table beer */
    public function addIngredient(int $beerId, int $ingredientId): Beer
    {
        try {
            //test if ingredient and beer exist
            $this->read($beerId);
            $ingredient = new Ingredient();
            $ingredient->read($ingredientId);

            // test if beer has ingredient
            $stmt = $this->pdo->prepare("SELECT * FROM beer_ingredient WHERE beer_id = :beer_id AND ingredient_id = :ingredient_id");
            $stmt->execute([
                'beer_id' => $beerId,
                'ingredient_id' => $ingredientId,
            ]);
            $beerIngredient = $stmt->fetch(PDO::FETCH_OBJ);

            // add ingredient if not present
            if (!$beerIngredient) {
                $stmt = $this->pdo->prepare("INSERT INTO beer_ingredient (beer_id, ingredient_id) VALUES (:beerId, :ingredientId)");
                $stmt->execute([
                    "beerId" => $beerId,
                    "ingredientId" => $ingredientId
                ]);
            }

            return $this->read($beerId);
            
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function removeIngredient(int $beerId, int $ingredientId): Beer
    {
        try {
            $this->read($beerId);
            $ingredient = new Ingredient();
            $ingredient->read($ingredientId);

            $stmt = $this->pdo->prepare("DELETE FROM beer_ingredient WHERE beer_id = :beerId AND ingredient_id = :ingredientId");
            $stmt->execute([
                "beerId" => $beerId,
                "ingredientId" => $ingredientId
            ]);

            return $this->read($beerId);
            
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function beerExistByName(string $name): bool|Beer {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM beers WHERE name = :name");
            $stmt->execute([
                "name" => $name,
            ]);
            $beer = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$beer) {
                return false;
            } else {
                return $this->read($beer->id);
            }

        } catch (Exception $e) {
            throw $e;
        }
    }
}
