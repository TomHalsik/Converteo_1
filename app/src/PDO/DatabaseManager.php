<?php


namespace PDO;


use PDO;
use PDOException;

class DatabaseManager
{
    private const HOST = "db";

    private const USER = "user";

    private const PASSWORD = "test";

    private const DATABASE = "myDb";

    public $pdo;

    public function __construct()
    {
        $this->connect();
    }

    public function connect()
    {
        $dsn = sprintf("mysql:host=%s;dbname=%s", self::HOST, self::DATABASE);
        try {
            $this->pdo = new PDO($dsn, self::USER, self::PASSWORD);
        } catch (PDOException $e) {
            $this->pdo = null;
            echo $e;
        }
    }
}