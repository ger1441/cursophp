<?php

namespace App\Controllers;
use App\Models\Project;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;
use Respect\Validation\Validator;

class ProjectsController extends BaseController
{
    public function indexAction(){
        $projects = Project::withTrashed()->get();
        return $this->renderHTML('projects/index.twig',['projects'=>$projects]);
    }

    public function getAddProjectAction($request){

        $responseMessage = '';
        $classMessage    = '';
        $this->titlePage = 'Projects';

        if($request->getMethod() == "POST"){
            $postData = $request->getParsedBody();

            $projectValidator = Validator::key('title',Validator::stringType()->notEmpty())
                                         ->key('description',Validator::stringType()->notEmpty())
                                         ->key('duration',Validator::intVal());

            try {
                $projectValidator->assert($postData);
                $project = new Project();
                $project->title = $postData['title'];
                $project->description = $postData['description'];
                $project->months = intval($postData['duration']);
                $project->save();
                $responseMessage = 'Saved';
                $classMessage = 'success';
            } catch (\Exception $e){
                $responseMessage = $e->getMessage();
                $classMessage = 'warning';
            }
        }
        //include '../views/addProject.php';
        return $this->renderHTML('addProject.twig',[
            'titlePage' => $this->titlePage,
            'responseMessage' => $responseMessage,
            'classMessage' => $classMessage
        ]);
    }

    public function deleteAction(ServerRequest $request){
        $parameters = $request->getQueryParams();
        $project = Project::find($parameters['id']);
        $project->delete();

        return new RedirectResponse('/projects');
    }
}
