<?php
namespace App\Services;

class DateCheck {
    public function isValid($str_date, $str_format = "d/m/Y", $str_timezone = false) {
        $date = \DateTime::createFromFormat($str_format, $str_date);

        if ($date && (int)$date->format('Y') < 1900) {
            return false;
        }

        return $date && \DateTime::getLastErrors()['warning_count'] == 0 && \DateTime::getLastErrors()['error_count'] == 0;
    }
}
