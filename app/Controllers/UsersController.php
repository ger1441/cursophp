<?php
namespace App\Controllers;
use App\Models\User;
use Laminas\Diactoros\ServerRequest;
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

    public function getFormChangePass(){
        return $this->renderHTML('/users/changePass.twig');
    }

    public function postChangePass(ServerRequest $request){
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
                            $newPass = $userSearch->encryptPass($postData['newpass'],PASSWORD_DEFAULT);
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
        return $this->renderHTML('users/changePass.twig',[
            'responseMessage' => $responseMessage,
            'classMessage' => $classMessage
        ]);
    }
}
