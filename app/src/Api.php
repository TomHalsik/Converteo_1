<?php

namespace Api;

use PDO;
use PDOException;
use PDOStatement;

require ('HttpResponse.php');
require ('RequestParser.php');

class Api
{

    private const HOST = "db";

    private const USER = "user";

    private const PASSWORD = "test";

    private const DATABASE = "myDb";

    private $pdo = NULL;

    private $httpResponse;

    private $requestParser;

    public function __construct()
    {
        $this->httpResponse = new HttpResponse();
        $this->requestParser = new RequestParser();
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

    /**
     * @param string $sql
     * @param array $values
     */
    public function request()
    {
        if ($this->pdo == NULL)
            $this->httpResponse->Response(["Error" => "No database connexion"], 500);

        $sql = $this->requestParser->getSql();
        $requester = $this->pdo->prepare($sql['request'], array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $requester->execute($sql['values']);


        $this->response($requester);
    }

    /**
     * @param PDOStatement $requester
     */
    public function response(PDOStatement $requester)
    {
        if ($requester->errorCode() != "00000") {
            $error = $requester->errorInfo();
            $status = 400;
            $data = [
                "Status" => $status,
                "Error Message" => $error[2],
                "Error Code" => $error[0],
                "Error Driver Code" => $error[1],
            ];
        } else {
            $data = $requester->fetchAll(PDO::FETCH_ASSOC);
            $status = 200;
        }

        $this->httpResponse->Response($data, $status);
    }
}