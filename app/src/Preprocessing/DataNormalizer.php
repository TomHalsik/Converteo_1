<?php


namespace Preprocessing;


class DataNormalizer
{
    /**
     * @param string $value
     * @return float|int
     */
    public function normalizeAnnualIncomming(string $value)
    {
        // Normalizes common number formats
        $expectedValue = (int) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if (ctype_digit($expectedValue))
            return $expectedValue;

        // Use K for thousand
        if (stripos($value, 'K') || str_contains($value, 'thousand')) {
            return $expectedValue * 1000;
        }
        // Use M for million
        if (stripos($value, 'M') || str_contains($value, 'million')) {
            return $expectedValue * 1000000;
        }
        // When hourly and annual pay are present
        if (preg_match("/\d+(\,\d{3,})+/", $value, $match)) {
            return $this->normalizeAnnualIncomming($match[0]);
        }
        // thousand separated with dot, and possibly float
        if (preg_match("/\d+(\.\d{3})(\.\d+)?/", $value, $match)) {
            $formattedValue = str_replace('.', '', $match[0]);
            // If float
            if (strlen(end($match)) == 3)
                $formattedValue = substr($formattedValue, 0, -2);
            return $this->normalizeAnnualIncomming($formattedValue);
        }
        // Reverse comma and dot
        if (preg_match("/\d+(\.\d{3})(\,\d+)/", $value, $match)) {
            $formattedValue = strtr($match[0], ['.' => ',', ',' => '.']);
            return $this->normalizeAnnualIncomming($formattedValue);
        }
        // Hourly value, and value below thousand with dot or comma
        else  {
            return 0;
        }
    }
}