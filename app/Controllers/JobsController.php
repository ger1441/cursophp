<?php
namespace App\Controllers;
use App\Models\Job;

class JobsController
{
    public function getAddJobAction(){
        if(isset($_POST['title'],$_POST['description']) && !empty($_POST['title']) && !empty($_POST['description'])){
            $job = new Job;
            $job->title = $_POST['title'];
            $job->description = $_POST['description'];
            $job->save();
        }

        include '../views/addJob.php';
    }
}
