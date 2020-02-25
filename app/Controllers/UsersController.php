<?php

namespace App\Controllers;
use App\Models\User;
use Cake\Database\Exception;
use Respect\Validation\Validator;

class UsersController extends BaseController
{
    public function getAddUserAction($request){

        $responseMessage = '';
        $classMessage    = '';
        $this->titlePage = 'Users';

        if($request->getMethod() == "POST"){
            $postData = $request->getParsedBody();

            $userValidator = Validator::key('email',Validator::email())
                                      ->key('password',Validator::stringType()->notEmpty());

            try {
                $userValidator->assert($postData);
                $user = new User();
                $user->email = $postData['email'];
                $user->password = $user->encryptPass($postData['password']);
                $user->save();
                $responseMessage = 'Saved';
                $classMessage = 'success';
            } catch (\Exception $e){
                $responseMessage = $e->getMessage();
                $classMessage = 'warning';
            }
        }
        //include '../views/addProject.php';
        return $this->renderHTML('addUser.twig',[
            'titlePage' => $this->titlePage,
            'responseMessage' => $responseMessage,
            'classMessage' => $classMessage
        ]);
    }

    public function getPassword(){
        return $this->renderHTML('users/pass.twig');
    }

    public function changePassword($request){
        $responseMessage = '';
        $classMessage = '';
        if($request->getMethod() == "POST"){
            $postData = $request->getParsedBody();

            $dataValidation = Validator::key('password',Validator::stringType()->notEmpty())
                                       ->key('newpass',Validator::stringType()->notEmpty())
                                       ->key('confirmpass',Validator::stringType()->notEmpty());
            try {
                $dataValidation->assert($postData);
                $sessionUserId = $_SESSION['userId'] ?? null;
                $userSearch = User::find($sessionUserId);
                if($userSearch){
                    if(password_verify($postData['password'],$userSearch->password)){
                        if($postData['newpass']==$postData['confirmpass']){
                            $newPass = $userSearch->encryptPass($postData['newpass']);
                            $userSearch->password = $newPass;
                            $userSearch->save();
                            $responseMessage = 'saved';
                            $classMessage = 'success';
                        }else{
                            $responseMessage = 'Confirm Pass';
                            $classMessage = 'warning';
                        }
                    }else{
                        $responseMessage = 'Check credentials';
                        $classMessage = 'warning';
                    }
                }else{
                    $responseMessage = 'User not Found';
                    $classMessage = 'error';
                }
            }catch (\Exception $e){
                $responseMessage = $e->getMessage();
                $classMessage = 'warning';
            }

        }
        return $this->renderHTML('users/pass.twig',[
           'responseMessage' => $responseMessage,
           'classMessage' => $classMessage
        ]);
    }
}
