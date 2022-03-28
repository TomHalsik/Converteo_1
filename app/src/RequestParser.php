<?php


namespace Api;


class RequestParser
{

    private $error = [];

    private $columns = [
        "Id", "Timestamp", "Age", "Industry", "Job_Title", "Salary", "Currency", "Postcollege_Experience", "Job_Ladder",
        "Other_Currency", "Employment_Type", "Company_Name", "Company_Size", "Country", "City", "Public_Or_Private",
        "Experience_In_Industry", "Experience_In_Company", "Job_Level", "Required_Hours_Per_Week", "Health_Insurance_Offered",
        "Actual_Hours_Per_Week", "Education_Level", "Annual_Base_Pay", "Annual_Bonus", "Annual_Stock_ValueBonus",
        "Annual_Week_Vacation", "Satisfied", "Resign_In_Year", "Opinion_industry_direction", "Gender", "Next_10_years_top_skill",
        "Done_bootcamp", "Employer", "Location", "Years_at_Employer", "Years_of_Experience", "Signing_Bonus", "Additional_Comments",
    ];

    private $operators = [
        "gte" => ">=",
        "lte" => "<=",
        "ngt" => "!>",
        "nlt" => "!<",
    ];

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
            "sort" => $sort ?? null,
            "fields" => $fields ?? null,
            "values" => $values
        ];
    }

    public function getSql()
    {
        $params = $this->getUriParams();

        $table = $this->getTable();
        $clauses = $this->getClauses($params['clauses']);
        $fields = $this->getFields($params['fields']);
        $sort = $this->getSort($params['sort']);
        $values = $this->cleanAllName($params['values']);

        $sql = sprintf("SELECT %s FROM %s WHERE %s %s", $fields, $table, $clauses, $sort);

        return [
            "request" => $sql,
            "values" => $values,
            "error" => $this->error,
        ];
    }

    public function checkColumnExist($columns)
    {
        $columnsArray = explode(',', $columns);
        foreach ($columnsArray as $column) {
            if (!in_array(ucfirst($column), $this->columns))
                $this->error[] =  [
                    "status" => 400,
                    "message" => sprintf("Unknown column %s' in 'field list'", $column)]
                ;
        }
    }

    public function getFields($fields)
    {
        if (empty($fields))
            return '*';
        $this->checkColumnExist($fields);
        return $fields;
    }

    public function getSort($sorts)
    {
        if (empty($sorts))
            return "";
        $this->checkColumnExist($sorts);
        return sprintf("ORDER BY %s", $sorts);
    }

    /**
     * @param array $clauses
     * @return string
     */
    public function getClauses(array $clauses)
    {
        if (empty($clauses))
            return " 1 ";

        $sql = "";
        foreach ($clauses as $clause) {
            $operator = $this->getOperator($clause);
            $clause = $this->cleanName($clause);
            $sql = sprintf("%s %s %s :%s AND", $sql, $clause, $operator, $clause);
        }
        $sql = substr($sql, 0, -3);

        return $sql;
    }

    /**
     * @param string $clause
     * @return string
     */
    public function getOperator(string $clause)
    {
        foreach ($this->operators as $operator => $value) {
            $regex = sprintf("/%s/i", $operator);
            if (preg_match($regex, $clause))
                return $value;
        }
        return "=";
    }

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
     * @param string $clause
     * @return string|string[]|null
     */
    public function cleanName(string $clause)
    {
        return preg_replace('/\[\w*\]/', '', $clause);
    }

    public function getTable()
    {
        $uri = explode('?', $_SERVER['REQUEST_URI']);
        return substr($uri[0], 1);
    }
}