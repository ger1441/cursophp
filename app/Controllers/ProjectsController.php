<?php
namespace App\Controllers;

use App\Models\Project;
use Laminas\Diactoros\Response\RedirectResponse;
use Respect\Validation\Validator;
use Laminas\Diactoros\ServerRequest;

class ProjectsController extends BaseController
{
    public function indexAction(){
        $projects = Project::all();
        $titlePage = 'Projects';
        return $this->renderHTML('projects/index.twig',compact('projects','titlePage'));
    }

    public function getAddProjectAction($request){
        $responseMessage = "";
        $classMessage = "";
        if($request->getMethod()=="POST"){
            $postData = $request->getParsedBody();
            $projectValidator = Validator::key('title',Validator::stringType()->notEmpty())
                ->key('description',Validator::stringType()->notEmpty());
            try {
                $projectValidator->assert($postData);

                $files = $request->getUploadedFiles();
                $logo = $files['logo'];
                $fileName = "";

                if($logo->getError() == UPLOAD_ERR_OK){
                    $fileName = $logo->getClientFilename();
                    $logo->moveTo("uploads/$fileName");
                }

                $project = new Project;
                $project->title = $postData['title'];
                $project->description = $postData['description'];
                $project->logo = $fileName;
                $project->save();
                $responseMessage = "Project Save success";
                $classMessage = "success";
            }catch(\Exception $e){
                $responseMessage = $e->getMessage();
                $classMessage = "info";
            }
        }
        return $this->renderHTML('addProject.twig',[
            'responseMessage' => $responseMessage,
            'classMessage' => $classMessage,
            'titlePage' => 'Add Project',
        ]);
    }

    public function deleteAction(ServerRequest $request){
        $params = $request->getQueryParams();
        $project = Project::find($params['id']);
        $project->delete();

        return new RedirectResponse('/projects');
    }
}
