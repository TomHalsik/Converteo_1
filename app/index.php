<?php
use Router\Router;
use Preprocessing\Database;

require ('src/Preprocessing/Database.php');
require('src/Router/Router.php');

$database = new Database();
$database->populateDatabase(dirname(__FILE__) . "/data/json/survey_3.json");

$Router= new Router();
$Router->run();

