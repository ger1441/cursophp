<?php

namespace App\Models;
use App\Traits\DurationAsString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    use DurationAsString;
    protected $table = 'projects';
}
