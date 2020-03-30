<?php
namespace App\Controllers;
use App\Models\Job;
use Laminas\Diactoros\Response\RedirectResponse;
use Respect\Validation\Validator;
use Laminas\Diactoros\ServerRequest;

class JobsController extends BaseController
{
    public function indexAction(){
        $jobs = Job::all();
        $titlePage = "Jobs";
        return $this->renderHTML('jobs/index.twig', compact('jobs','titlePage'));
    }

    public function getAddJobAction($request){
        $responseMessage = "";
        $classMessage = "";
        if($request->getMethod() == "POST"){
            $postData = $request->getParsedBody();

            $jobValidator = Validator::key('title', Validator::stringType()->notEmpty())
                ->key('description', Validator::stringType()->notEmpty());

            try{
                $jobValidator->assert($postData);

                $files = $request->getUploadedFiles();
                $logo = $files['logo'];
                $fileName = "";

                if($logo->getError() == UPLOAD_ERR_OK){
                    $fileName = $logo->getClientFilename();
                    $logo->moveTo("uploads/$fileName");
                }

                $job = new Job;
                $job->title = $postData['title'];
                $job->description = $postData['description'];
                $job->logo = $fileName;
                $job->save();
                $responseMessage = 'Save Job success!';
                $classMessage = 'success';
            }catch(\Exception $e) {
                //var_dump($e->getMessage());
                $responseMessage = $e->getMessage();
                $classMessage = 'info';
            }
        }

        return $this->renderHTML('addJob.twig',[
            'responseMessage'=>$responseMessage,
            'classMessage'=>$classMessage,
            'titlePage' => 'Add Job'
        ]);
    }

    public function deleteAction(ServerRequest $request){
        $params = $request->getQueryParams();
        $job = Job::find($params['id']);
        $job->delete();

        return new RedirectResponse('/jobs');
    }

}
