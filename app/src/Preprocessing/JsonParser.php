<?php

namespace Preprocessing;

require ('DataNormalizer.php');

class JsonParser
{
    public $newFieldsName = [
        'How old are you?' => 'Age',
        'What industry do you work in?' => 'Industry',
        'What is your annual salary?' => 'Annual_Base_Pay',
        'Please indicate the currency' => 'Currency',
        'Where are you located? (City/state/country)' => 'Location',
        'How many years of post-college professional work experience do you have?' => 'Postcollege_Experience',
        'If your job title needs additional context, please clarify here:' => 'Additionnal_Comments',
        'If \"Other,\" please indicate the currency here:' => 'Other_Currency',
        'Company Size - # Employees' => 'Company_Size',
        'Primary Location (Country)' => 'Country',
        'Primary Location (City)' => 'City',
        'Industry in Company' => 'Industry',
        'Job Title In Company' => 'Job_Title',
        'Years Experience in Industry' => 'Years_Of_Experience',
        'Years of Experience in Current Company' => 'Years_At_Employer',
        'Highest Level of Formal Education Completed' => 'Education_Level',
        'Total Base Salary in 2018 (in USD)' => 'Annual_Base_Pay',
        'Total Bonus in 2018 (cumulative annual value in USD)' => 'Annual_Bonus',
        'Total Stock Options/Equity in 2018 (cumulative annual value in USD)' => 'Annual_Stock_ValueBonus',
        'Annual Vacation (in Weeks)' => 'Annual_Weeks_Vacation',
        'Are you happy at your current position?' => 'Satisfied',
        'Do you plan to resign in the next 12 months?' => 'Resign_In_Year',
        'What are your thoughts about the direction of your industry?' => 'Opinion_industry_direction',
        'Final Question: What are the top skills (you define what that means) that you believe will be necessary for job growth in your industry over the next 10 years?' => 'Next_10_years_top_skill',
        'Have you ever done a bootcamp? If so was it worth it?' => 'Done_Bootcamp',
        'Annual Stock Value/Bonus' => 'Annual_Stock_ValueBonus',
    ];

    public $integerFields = [
        "INTEGER" => ["Annual_Base_Pay", "Annual_Bonus", "Annual_Stock_ValueBonus",],
        "RANGE" => ["Age", "Years_At_Employer", "Years_Of_Experience", "Annual_Weeks_Vacation",],
    ];

    public $dataNormalizer;

    public function __construct()
    {
        $this->dataNormalizer = new DataNormalizer();
    }

    public function parse(string $path)
    {
        $json = file_get_contents($path);
        $json = $this->replaceKeys($json);
        $data = json_decode($json,true);

        $arrayPath = explode('/', $path);
        $tableName = end($arrayPath);

        foreach ($data as $entries)
        {
            foreach ($entries as $key => $value)
            {
                $fieldName = str_replace(' ', '_', ucwords($key));
                if (in_array($key, $this->integerFields['INTEGER']))
                    $value = $this->dataNormalizer->normalizeAnnualIncomming($value);
                if (in_array($key, $this->integerFields['RANGE']))
                    $value = $value[0];
            }
        }
        exit();
    }

    public function replaceKeys(string $json)
    {
        foreach ($this->newFieldsName as $oldValue => $newValue)
            $json = str_replace($oldValue, $newValue, $json);

        return $json;
    }
}