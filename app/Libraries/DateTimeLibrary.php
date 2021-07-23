<?php

namespace App\Libraries;

use Carbon\Carbon;

/**
 * class DateTimeLibrary
 * @package App\Libraries
 */
class DateTimeLibrary
{
    /**
     * To validate if string is valid date time string
     *
     * @param string $string
     * @return string
     */
    public static function validate(string $string)
    {
        if (strtotime($string) === false) {
            $string = Carbon::today()->format('d-m-Y');
        }
        return $string;
    }
}