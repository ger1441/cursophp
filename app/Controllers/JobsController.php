<?php

namespace App\Controllers;
use App\Models\Job;
use App\Services\JobService;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;
use Respect\Validation\Validator;

class JobsController extends BaseController
{
    private $jobService;

    public function __construct(JobService $jobService)
    {
        parent::__construct();
        $this->jobService = $jobService;
    }

    public function indexAction(){
        $jobs = Job::withTrashed()->get();
        return $this->renderHTML('jobs/index.twig',compact('jobs'));
    }

    public function getAddJobAction($request){

        $responseMessage = '';
        $classMessage    = '';
        $this->titlePage = 'Jobs';

        if($request->getMethod()=='POST'){
            $postData = $request->getParsedBody();
            $jobValidator = Validator::key('title',Validator::stringType()->notEmpty())
                                    ->key('description',Validator::stringType()->notEmpty());
            try {
                $jobValidator->assert($postData);
                $files = $request->getUploadedFiles();
                $logo = $files['logo'];

                $filePath = "";
                if($logo->getError() == UPLOAD_ERR_OK){
                    $fileName = $logo->getClientFilename();
                    $filePath = "uploads/$fileName";
                    $logo->moveTo($filePath);
                }else $fileName = '';

                $job = new Job();
                $job->title = $postData['title'];
                $job->description = $postData['description'];
                $job->image = "$filePath";
                $job->save();
                $responseMessage = 'Saved';
                $classMessage = 'success';
            } catch (\Exception $e){
                $responseMessage = $e->getMessage();
                $classMessage = 'warning';
            }
        }

        //include "../views/addJob.php";
        return $this->renderHTML('addJob.twig',[
            'titlePage' => $this->titlePage,
            'responseMessage' => $responseMessage,
            'classMessage' => $classMessage
        ]);
    }

    public function deleteAction(ServerRequest $request){
        $idDelete = $request->getAttribute('id');
        $this->jobService->deleteJob($idDelete);
        /*$params = $request->getQueryParams();
        $this->jobService->deleteJob($params['id']);*/

        return new RedirectResponse('/jobs');
    }
}