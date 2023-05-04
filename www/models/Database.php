<?php

require_once("../config.php");

class Database
{
    protected $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=" . HOST_NAME . ";dbname=" . DB_NAME, USER_NAME, PASSWORD);
        } catch(Exception $e) {
            throw $e;
        }
    }
}