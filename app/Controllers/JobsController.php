<?php
namespace App\Controllers;
use App\Models\Job;
use Respect\Validation\Validator;

class JobsController extends BaseController
{
    public function getAddJobAction($request){
        $responseMessage = "";
        $classMessage = "";
        if($request->getMethod() == "POST"){
            $postData = $request->getParsedBody();

            $jobValidator = Validator::key('title', Validator::stringType()->notEmpty())
                ->key('description', Validator::stringType()->notEmpty());

            try{
                $jobValidator->assert($postData);
                $job = new Job;
                $job->title = $postData['title'];
                $job->description = $postData['description'];
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
            'classMessage'=>$classMessage
        ]);
    }
}
