<?php

namespace App\Traits;

trait DurationAsString
{
    public function getDurationAsString($title)
    {
        $years = floor($this->months / 12);
        $extraMonths = $this->months % 12;
        $msgYears = ($years > 0) ? "$years years" : "";
        $msgMonths = ($extraMonths > 0) ? "$extraMonths months" : "";

        $msgReturn = (!empty($msgYears)||!empty($msgMonths)) ? "$title duration: $msgYears $msgMonths" : "Not information";
        return $msgReturn;
    }
}