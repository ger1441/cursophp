<?php

namespace App\Models;
use App\Traits\{HasDefaultImage,DurationAsString};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use SoftDeletes;
    use HasDefaultImage;
    use DurationAsString;
    protected $table = 'jobs';
}