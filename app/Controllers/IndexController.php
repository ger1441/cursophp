<?php

namespace App\Controllers;

use App\Models\{Job,Project};

class IndexController extends BaseController
{
    public function indexAction(){

        $name = "Yerman QL";
        $limitMonths = 15;

        $jobs = Job::all();
        $projects = Project::all();

        $filterFunction = function($job) use ($limitMonths){
            return $job['months'] >= $limitMonths;
        };

        $jobs = array_filter($jobs->toArray(),$filterFunction);

        return $this->renderHTML('index.twig',[
            'name' => $name,
            'jobs' => $jobs,
            'projects' => $projects
        ]);
    }
}