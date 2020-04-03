<?php
namespace App\Services;

use App\Controllers\BaseController;
use App\Models\Job;

class DeleteService
{
    public function deleteElement($id,$class){
        $object = $class::find($id);
        $object->delete();
    }
}