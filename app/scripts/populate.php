<?php
use Preprocessing\Database;

require (dirname(__FILE__) . './../src/Preprocessing/Database.php');

$database = new Database();
$database->populate(dirname(__FILE__) . "/../data/json/survey_1.json");
$database->populate(dirname(__FILE__) . "/../data/json/survey_2.json");
$database->populate(dirname(__FILE__) . "/../data/json/survey_3.json");