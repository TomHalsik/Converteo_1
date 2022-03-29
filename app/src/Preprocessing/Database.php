<?php


namespace Preprocessing;


use PDO\DatabaseManager;

require ('JsonParser.php');
require (dirname(__FILE__) . '/../PDO/DatabaseManager.php');

class Database
{
    private $jsonParser;

    private $pdo;

    public function __construct()
    {
        $this->jsonParser = new JsonParser();
        $this->pdo = new DatabaseManager();
    }

    public function populate(string $jsonPath)
    {
        $params = $this->jsonParser->parse($jsonPath);

        $valuesArray = [];
        foreach ($params['values'] as $values) {
            $valuesArray[] = sprintf("(%s)", implode(', ', $values));
        }
        $valuesInsert = implode(',', $valuesArray);

        $sql = sprintf("INSERT INTO `%s` (%s) VALUES %s", $params['table'], implode(', ', $params['fields']), $valuesInsert);
        $this->pdo->pdo->query($sql);
    }
}