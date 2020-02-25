<?php

namespace App\Services;

use App\Models\Job;

class JobService
{
    public function deleteJob($id){
        //var_dump($id);
        $job = Job::findOrFail($id);
        $job->delete();
    }
}