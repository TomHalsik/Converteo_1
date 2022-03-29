<?php


namespace Preprocessing;


class DataNormalizer
{

    private $newFieldsName = [
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
        'Years of Experience in Current Company' => 'Years_In_Company',
        'Years at Employer' => 'Years_In_Company',
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
        '"Employer"' => '"Company_Name"',
    ];

    public $fails = [];

    public function replaceKeys(string $json)
    {
        foreach ($this->newFieldsName as $oldValue => $newValue)
            $json = str_replace($oldValue, $newValue, $json);

        return $json;
    }

    public function normalizeRange(string $value)
    {
        if (intval($value) == $value)
            return (int) $value;
        if (floatval($value) == $value)
            return (int) $value;
        return isset($value[0]) ? intval($value[0]) : 0;
    }

    /**
     * @param string $value
     * @return float|int
     */
    public function normalizeAnnualIncomming(string $value)
    {
        $value = str_ireplace(
            ['$', '£', '€', 'dollars', 'dollar', 'euros', 'euro', 'eur'],
            ['', '', '', '', '', '', '', ''],
            $value);

        // Input with valid format
        if (intval($value) == $value)
            return (int) $value;
        if (floatval($value) == $value)
            return (int) $value;
        if (preg_match("/(^\d{1,}([,]\d{3})+)(\.\d*)?/", $value, $match))
            return (int) filter_var($match[0], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);

        // Input without number
        if (!preg_match('/[0-9]+/', $value))
            return 0;

        // Input without letters
        if (!preg_match('/[A-z]+/', $value)) {
            if (preg_match("/(^\d+\,\d{2})$/", $value, $match))
                return intval($value);
        }

        // Input in other format
        if (preg_match("/(\d+)[ ]?K|k|thousand/", $value, $match))
            return intval($match[0]) * 1000;
        if (preg_match("/(\d+)[ ]?M|m|million/", $value, $match))
            return intval($match[0]) * 1000000;

        // Input with 2 digit or less
        if (count(array_filter(str_split($value),'is_numeric')) < 3)
            return 0;

        // Input with multiple value
        if (preg_match("/(\d{1,}([,]\d{3})+)(\.\d*)?/", $value, $match))
            return (int) filter_var(max($match), FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
        if (preg_match("/\d{4,}/", $value, $match))
            return max($match);

        // Input with letter at the end and comma between nb
        if (preg_match("/(^\d{1,},?\d*).*$/", $value, $match))
            return intval($match[0]);

        // Input with thousand format with invalid format
        if (preg_match("/(\d{1,}([,| ][ ]*\d{3})+)(\.\d*)?/", $value, $match))
            return (int)filter_var($match[0], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
        if (preg_match("/(^\d{1,}(\.\d{3})+(\.\d{2})$)/", $value, $match))
            return round(floatval($match[0]));
        if (preg_match("/(^\d{1,}(\.\d{3})+(\.\d{2})$)/", $value, $match))
            return round(floatval($match[0]));
        if (preg_match("/(\d{1,}(\.\d{3})+)/", $value, $match))
            return round(floatval($match[0]));
        if (preg_match("/^\d{1,}('\d{3})+/", $value, $match))
            return round(floatval(str_replace("'", "", $match[0])));

        $this->fails = $value;
        return intval($value);
    }
}