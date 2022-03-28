<?php


namespace Router;

use Api\Api;

require ('src/Api/Api.php');

class Router
{

    private $ApiEndpoint = [
        "/survey_1",
        "/survey_2",
        "/survey_3",
    ];

    private $api;

    public function __construct()
    {
        $this->api = new Api();
    }

    public function run()
    {
        $uriParams= parse_url($_SERVER['REQUEST_URI']);

        if (in_array($uriParams['path'], $this->ApiEndpoint))
            $this->api->request($_SERVER['REQUEST_URI']);
        else if ($uriParams['path'] == '/')
            $this->displayReadMe();
        else
            $this->display404();
    }

    public function displayReadMe()
    {
        echo file_get_contents('template/index.html');
    }

    public function display404()
    {
        echo "Nothing here";
    }
}