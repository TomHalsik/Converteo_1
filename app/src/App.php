<?php


namespace Api;

require 'Api.php';

class App
{

    private $ApiEndpoint = [
        "/survey_1",
        "/survey_2",
        "/survey_3",
    ];

    public $api;

    public function __construct()
    {
        $this->api = new Api();
    }

    public function run()
    {
        $uri = explode('?', $_SERVER['REQUEST_URI']);

        if (in_array($uri[0], $this->ApiEndpoint))
            $this->api->request();
        else if ($uri[0] == '/')
            $this->displayReadMe();
        else
            echo "Nothing here";
    }

    public function displayReadMe()
    {
        echo "README";
    }
}