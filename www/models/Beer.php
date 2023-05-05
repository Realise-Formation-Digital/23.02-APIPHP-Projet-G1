<?php

require_once("../models/Database.php");

class Beer extends Database
{

    private ?int $id;

    private ?string $name;

    private string $tagline;

    private string $firstBrewed;

    private string $description;

    private string $imageUrl;

    private string $brewersTips;

    private string $contribuedBy;

    private ?string $foodPairing1;

    private ?string $foodPairing2;

    private ?string $foodPairing3;

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
    public function getContribuedBy(): string
    {
        return $this->contribuedBy;
    }

    /**
     * @param string $contribuedBy
     */
    public function setContribuedBy(string $contribuedBy): void
    {
        $this->contribuedBy = $contribuedBy;
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

    /**
     * Method to get all beers from db
     *
     * @return array
     * @throws Exception
     */
    public function search(): array {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM beers");
            $stmt->execute();
            $beers = $stmt->fetchAll(PDO::FETCH_OBJ);

            $beersObj = [];
            foreach($beers as $beer) {
                $tempBeer = new Beer();
                $tempBeer->setId($beer->id);
                $tempBeer->setName($beer->name);
                $tempBeer->setTagline($beer->tagline);
                $tempBeer->setFirstBrewed($beer->first_brewed);
                $tempBeer->setDescription($beer->description);
                $tempBeer->setImageUrl($beer->image_url);
                $tempBeer->setBrewersTips($beer->brewers_tips);
                $tempBeer->setContribuedBy($beer->contribued_by);
                $tempBeer->setFoodPairing1($beer->food_pairing1);
                $tempBeer->setFoodPairing2($beer->food_pairing2);
                $tempBeer->setFoodPairing3($beer->food_pairing3);
                $beersObj[] = $tempBeer;
            }
            return $beersObj;

        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * Method to add a beer in db
     *
     * @param Beer $beer
     * @return Beer
     */
    public function create(Beer $beer): Beer {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO beers (name, tagline, first_brewed, description, image_url, brewers_tips, contribued_by, food_pairing1, food_pairing2, food_pairing3) VALUES (:name, :tagline, :first_brewed, :description, :image_url, :brewers_tips, :contribued_by, :food_pairing1, :food_pairing2, :food_pairing3)");
            $stmt->execute([
                "name" => $beer->getName(),
                "tagline" => $beer->getTagline(),
                "first_brewed" => $beer->getFirstBrewed(),
                "description" => $beer->getDescription(),
                "image_url" => $beer->getImageUrl(),
                "brewers_tips" => $beer->getBrewersTips(),
                "contribued_by" => $beer->getContribuedBy(),
                "food_pairing1" => $beer->getFoodPairing1(),
                "food_pairing2" => $beer->getFoodPairing2(),
                "food_pairing3" => $beer->getFoodPairing3(),
            ]);
            $id = $this->pdo->lastInsertId();
            $beer->setId($id);
            return $beer;
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * Method to get a unique beer by id from db
     *
     * @param int $id
     * @return Beer
     */
    public function read(int $id): Beer {

    }

    /**
     * Method to update a beer by id in db
     *
     * @param int $id
     * @return Beer
     */
    public function update(int $id): Beer {

    }

    /**
     * Method to delete beer by id in db
     *
     * @param int $id
     * @return string
     */
    public function delete(int $id): string {

    }



}