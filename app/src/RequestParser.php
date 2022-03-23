<?php


namespace Api;


class RequestParser
{
    public function getUriParams()
    {
        $clauses = [];
        $values = [];

        $params = explode('&', $_SERVER['QUERY_STRING']);
        array_shift($params);
        foreach ($params as $param) {
            $singleParam = explode('=', $param);
            if ($singleParam[0] == "sort")
                $sort = $singleParam[1];
            else if ($singleParam[0] == "fields")
                $fields = $singleParam[1];
            else {
                $values += [$singleParam[0] => $singleParam[1]];
                $clauses[] = $singleParam[0];
            }

        }

        return [
            "clauses" => $clauses,
            "sort" => $sort,
            "fields" => $fields,
            "values" => $values
        ];
    }

    public function getSql()
    {
        $params = $this->getUriParams();

        $fields = $this->getFields($params['fields']);
        $table = $this->getTable();
        $clauses = $this->getClauses($params['clauses']);
        $sort = $this->getSort($params['sort']);
        $params['values'] += $sort != '' ? ['ORDER' => $params['sort']] : NULL;

        $sql = sprintf("SELECT %s FROM %s WHERE %s %s", $fields, $table, $clauses, $sort);

        return [
            "request" => $sql,
            "values" => $params['values'],
        ];
    }

    public function getFields($fields)
    {
        return empty($fields) ? " * " : $fields;
    }

    public function getSort($sort)
    {
        return empty($sort) ? "" : sprintf("ORDER BY :ORDER");
    }

    public function getClauses($clauses)
    {
        if (empty($clauses))
            return " 1 ";

        $sql = "";
        foreach ($clauses as $clause) {
            $operator = $this->getOperator($clause);
            $clause = $this->cleanClauseName($clause);
            $sql = sprintf("%s %s %s :%s AND", $sql, $clause, $operator, $clause);
        }
        $sql = substr($sql, 0, -3);

        return $sql;
    }

    public function getOperator(string $clause)
    {
        if (preg_match("/gte/i", $clause))
            return ">=";
        if (preg_match("/lte/i", $clause))
            return "<=";
        if (preg_match("/ngt/i", $clause))
            return "!>";
        if (preg_match("/nlt/i", $clause))
            return "!<";
        return "=";
    }

    public function cleanClauseName($clause)
    {
        return preg_replace('/\[\w*\]/', '', $clause);
    }

    public function getTable()
    {
        return "survey_2";
    }
}