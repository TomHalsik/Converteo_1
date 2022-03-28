<?php

namespace Api;

use PDO;
use PDO\DatabaseManager;
use PDOStatement;

require ('HttpResponse.php');
require ('RequestParser.php');
require (dirname(__FILE__) . '/../PDO/DatabaseManager.php');

class Api
{

    private $databaseManager;

    private $httpResponse;

    private $requestParser;

    public function __construct()
    {
        $this->httpResponse = new HttpResponse();
        $this->requestParser = new RequestParser();
        $this->databaseManager = new DatabaseManager();
    }

    public function request(string $uri)
    {
        if ($this->databaseManager->pdo == NULL)
            $this->httpResponse->Response(["Error" => "No database connexion"], 500);

        $sql = $this->requestParser->getSql($uri);
        if (!empty($sql['error'])){
            $this->httpResponse->Response($sql['error'], 400);
            exit();
        }

        $requester = $this->databaseManager->pdo->prepare($sql['request'], array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        try {
            $requester->execute($sql['values']);
        } catch (\PDOException $e) { }


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