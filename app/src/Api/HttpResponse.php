<?php


namespace Api;


class HttpResponse
{
    /**
     * @param array $data
     * @param int $code
     */
    public function Response(array $data, int $code)
    {
        header_remove();
        header("Content-type: application/json; charset=utf-8");
        http_response_code($code);
        echo json_encode($data);
        exit();
    }
}