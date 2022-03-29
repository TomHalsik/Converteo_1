<?php

namespace Preprocessing;

require ('DataNormalizer.php');

class JsonParser
{
    private $integerFields = [
        "INTEGER" => ["Annual_Base_Pay", "Annual_Bonus", "Annual_Stock_ValueBonus", "Signing_Bonus"],
        "RANGE" => ["Age", "Postcollege_Experience", "Years_In_Company", "Years_Of_Experience", "Annual_Weeks_Vacation",],
    ];

    private $dataNormalizer;

    public function __construct()
    {
        $this->dataNormalizer = new DataNormalizer();
    }

    public function parse(string $path)
    {
        $json = file_get_contents($path);
        $json = $this->dataNormalizer->replaceKeys($json);
        $data = json_decode($json,true);

        $values = [];
        foreach ($data as $entries)
        {
            $tmp = [];
            $tmp[] = 'NULL';
            foreach ($entries as $key => $value)
            {
                $key = str_replace(' ', '_', ucwords($key));
                if (in_array($key, $this->integerFields['INTEGER']))
                    $value = $this->dataNormalizer->normalizeAnnualIncomming($value);
                else if (in_array($key, $this->integerFields['RANGE']))
                    $value = $this->dataNormalizer->normalizeRange($value);
                else {
                    $value = addslashes($value);
                    $value = $value == '' ? "NULL" : "'$value'";
                }

                $tmp[] = $value;
            }
            $values[] = $tmp;
        }

        return [
            "table" => $this->getTable($path),
            "fields" => $this->getFields($data[0]),
            "values" => $values,
        ];
    }

    public function getTable(string $path)
    {
        $arrayPath = explode('/', $path);
        $fileName = explode('.', end($arrayPath));
        return $fileName[0];
    }

    public function getFields(array $data)
    {
        $fields = array_keys($data);
        array_unshift($fields, "id");
        foreach ($fields as $id => $value) {
            $tmp = str_replace(' ', '_', ucwords($value));
            $fields[$id] = sprintf("`%s`", $tmp);
        }

        return $fields;
    }
}