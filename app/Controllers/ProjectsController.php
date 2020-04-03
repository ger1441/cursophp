<?php
namespace App\Controllers;

use App\Models\Project;
use App\Services\DeleteService;
use Laminas\Diactoros\Response\RedirectResponse;
use Respect\Validation\Validator;
use Laminas\Diactoros\ServerRequest;

class ProjectsController extends BaseController
{
    private $objService;

    public function __construct(DeleteService $objService){
        parent::__construct();
        $this->objService = $objService;
    }

    public function indexAction(){
        $projects = Project::withTrashed()->get();
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
        $this->objService->deleteElement($params['id'],Project::class);

        return new RedirectResponse('/projects');
    }
}
