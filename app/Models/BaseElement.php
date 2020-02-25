<?php

namespace App\Models;

class BaseElement implements Printable
{
    protected $title;
    public $description;
    public $visible = true;
    public $months;

    public function __construct($title,$description)
    {
        $this->setTitle($title);
        $this->description = $description;
    }

    public function getTitle() {
        return $this->title;
    }
    public function setTitle($title)
    {
        if($title == '') {
            $this->title = 'N/A';
        }else {
            $this->title = $title;
        }
    }

    public function getDurationAsString()
    {
        $years = floor($this->months / 12);
        $extraMonths = $this->months % 12;
        $msgYears = ($years > 0) ? "$years years" : "";
        $msgMonths = ($extraMonths > 0) ? "$extraMonths months" : "";

        return "$msgYears $msgMonths";
    }

    public function getDescription()
    {
        return $this->description;
    }
}
