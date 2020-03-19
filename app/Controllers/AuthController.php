<?php
namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator;

class AuthController extends BaseController
{
    public function getLogin(){
        return $this->renderHTML('login.twig');
    }

    public function postLogin($request){
        $responseMessage = "";
        $classMessage = "";

        $postData = $request->getParsedBody();
        $userValidate = Validator::key('email',Validator::email())
                                 ->key('password',Validator::stringType()->notEmpty());
        try {
            $userValidate->assert($postData);
            $user = User::where('email',$postData['email'])->first();
            if($user){
                if(password_verify($postData['password'],$user->password)){
                    $responseMessage = "Encontrado";
                }else $responseMessage = "NO Encontrado";
            }else{
                $responseMessage = "Verifique su información";
                $classMessage = "info";
            }
        }catch (\Exception $e){
            $responseMessage = $e->getMessage();
            $classMessage = "info";
        }

        echo $responseMessage;
    }
}