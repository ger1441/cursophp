<?php
namespace App\Controllers;

use App\Models\Project;
use Respect\Validation\Validator;

class ProjectsController extends BaseController
{
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
        ]);
    }
}
