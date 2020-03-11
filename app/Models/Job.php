<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'jobs';

    public function getDurationAsString() {
        $years  = floor($this->months / 12 ) > 0 ? floor($this->months / 12 ) . " years": "";
        $extraM = $this->months % 12 > 0 ? $this->months % 12 . " months" : "";
        return "Job duration: $years $extraM";
    }
}