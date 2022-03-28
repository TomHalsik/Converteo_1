<?php
use Router\Router;
use Preprocessing\JsonParser;

require ('src/Preprocessing/JsonParser.php');
require('src/Router/Router.php');

$jsonParser = new JsonParser();
$jsonParser->parse(dirname(__FILE__) . "/data/survey_1.json");

$Router= new Router();
$Router->run();

