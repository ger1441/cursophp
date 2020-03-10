<?php
require_once "BaseElement.php";


class Job extends BaseElement
{

    public function __construct($title, $description)
    {
        $newTitle = 'Job: '.$title;
        //$this->title = $newTitle;
        parent::__construct($newTitle, $description);
    }

    public function getDurationAsString() {
        $years  = floor($this->months / 12 ) > 0 ? floor($this->months / 12 ) . " years": "";
        $extraM = $this->months % 12 > 0 ? $this->months % 12 . " months" : "";
        return "Job duration: $years $extraM";
    }
}