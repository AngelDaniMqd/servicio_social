<?php


namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function nowMexico()
    {
        return Carbon::now('America/Mexico_City');
    }
    
    public static function formatMexico($date, $format = 'd/m/Y H:i')
    {
        return Carbon::parse($date)->setTimezone('America/Mexico_City')->format($format);
    }
}