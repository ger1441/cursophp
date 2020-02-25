<?php

namespace App\Controllers;
use App\Models\{Job,Project};

class IndexController extends BaseController
{
    public function indexAction(){

        $jobs = Job::all();
        for($i=0;$i<count($jobs);$i++){
            $jobs[$i]->durationJob = $jobs[$i]->getDurationAsString("Jobs");
        }

        $projects = Project::all();
        for($i=0;$i<count($projects);$i++){
            $projects[$i]->durationProject = $projects[$i]->getDurationAsString("Project");
        }

        /*$limitMonths = 10;
        $filterJobs = function (array $job) use($limitMonths){
            return $job['months'] >= $limitMonths;
        };
        $jobs = array_filter($jobs->toArray(),$filterJobs);*/

        $name = "Yerman QL";
        $this->titlePage = "Resume";

        //include "../views/index.php";
        return $this->renderHTML('index.twig',[
            'name' => $name,
            'titlePage' => $this->titlePage,
            'jobs' => $jobs,
            'projects' => $projects
        ]);
    }
}
