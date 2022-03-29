<?php


namespace Api;


class RequestParser
{

    private $error = [];

    private $operators = [
        "gte" => ">=",
        "lte" => "<=",
        "ngt" => "!>",
        "nlt" => "!<",
    ];

    /**
     * @param string $uri
     * @return array
     */
    public function getUriParams(string $uri)
    {
        $whereClauses = [];
        $values = [];

        if (str_contains( $uri, '?'))
        {
            $query = explode('?', $uri);
            $params = explode('&', end($query));
            foreach ($params as $param) {
                $singleParam = explode('=', $param);
                if ($singleParam[0] == "sort")
                    $sort = $singleParam[1];
                else if ($singleParam[0] == "fields")
                    $fields = $singleParam[1];
                else {
                    $values += [$singleParam[0] => $singleParam[1]];
                    $whereClauses[] = $singleParam[0];
                }
            }
        }


        return [
            "whereClauses" => $whereClauses,
            "sort" => $sort ?? null,
            "fields" => $fields ?? null,
            "values" => $values
        ];
    }

    /**
     * @param string $uri
     * @return array
     */
    public function getSql(string $uri)
    {
        $params = $this->getUriParams($uri);

        $table = $this->getTable($uri);
        $whereClauses = $this->getClauses($params['whereClauses']);
        $fields = $this->getFields($params['fields']);
        $sort = $this->getSort($params['sort']);
        $values = $this->cleanAllName($params['values']);

        $sql = sprintf("SELECT %s FROM %s WHERE %s %s", $fields, $table, $whereClauses, $sort);

        return [
            "request" => $sql,
            "values" => $values,
            "error" => $this->error,
        ];
    }


    /**
     * @return string
     */
    public function getFields($fields)
    {
        if (empty($fields))
            return '*';
        return $fields;
    }

    /**
     * @return string
     */
    public function getSort($sorts)
    {
        if (empty($sorts))
            return "";
        return sprintf("ORDER BY %s", $sorts);
    }

    /**
     * @param array $whereClauses
     * @return string
     */
    public function getClauses(array $whereClauses)
    {
        if (empty($whereClauses))
            return " 1 ";

        $sql = "";
        foreach ($whereClauses as $where) {
            $operator = $this->getOperator($where);
            $where = $this->cleanName($where);
            $sql = sprintf("%s %s %s :%s AND", $sql, $where, $operator, $where);
        }
        $sql = substr($sql, 0, -3);

        return $sql;
    }

    /**
     * @param string $where
     * @return string
     */
    public function getOperator(string $where)
    {
        foreach ($this->operators as $operator => $value) {
            $regex = sprintf("/%s/i", $operator);
            if (preg_match($regex, $where))
                return $value;
        }
        return "=";
    }

    /**
     * @param array $data
     * @return array
     */
    public function cleanAllName(array $data)
    {
        $result = [];

        foreach ($data as $key => $value) {
            $newKey = $this->cleanName($key);
            $result[$newKey] = $value;
        }

        return $result;
    }

    /**
     * @param string $where
     * @return string|string[]|null
     */
    public function cleanName(string $where)
    {
        return preg_replace('/\[\w*\]/', '', $where);
    }

    public function getTable(string $uri)
    {
        $uriParams = parse_url($uri);
        return substr($uriParams["path"], 1);
    }
}