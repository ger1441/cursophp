<?php
namespace App\Controllers;
use App\Models\User;
use Respect\Validation\Validator;

class UsersController extends BaseController
{
    public function getAddUserAction($request){
        $responseMessage = '';
        $classMessage = '';
        if($request->getMethod()=="POST"){
            $postData = $request->getParsedBody();
            $userValidator = Validator::key('email',Validator::email())
                ->key('password',Validator::stringType()->notEmpty());
            try {
                $userValidator->assert($postData);

                $user = new User;
                $user->email = $postData['email'];
                $user->password = $user->encryptPass($postData['password']);
                $user->save();
                $responseMessage = 'User Save success';
                $classMessage = 'success';
            } catch (\Exception $e){
                $responseMessage = $e->getMessage();
                $classMessage = 'info';
            }
        }

        return $this->renderHTML('addUser.twig',[
            'responseMessage' => $responseMessage,
            'classMessage' => $classMessage,
            'titlePage' => 'Add User',
        ]);
    }
}
