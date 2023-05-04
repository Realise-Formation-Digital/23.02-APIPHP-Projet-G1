<?php

require_once("./Database.php");

class Beer extends Database
{

    private int $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Method to get all beers from db
     *
     * @return array
     */
    public function search(): array {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM beers");
            $stmt->execute();
            $beers = $stmt->fetchAll(PDO::FETCH_OBJ);

            $beersObj = [];
            foreach($beers as $beer) {
                $tempBeer = new Beer();
                $tempBeer->setId($beers->id);
                $beersObj[] = $tempBeer;
            }

            return $beersObj;

        } catch(Exception $e) {
            throw $e;
        }
        return [];
    }

    /**
     * Method to add a beer in db
     *
     * @param Beer $beer
     * @return Beer
     */
    public function create(Beer $beer): Beer {

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