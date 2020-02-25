<?php

namespace App\Controllers;
use App\Models\User;
use Laminas\Diactoros\ServerRequest;
use Respect\Validation\Validator;
use Laminas\Diactoros\Response\RedirectResponse;

class AuthController extends BaseController
{
    public function getLogin()
    {
        return $this->renderHTML('login.twig');
    }

    public function postAuth(ServerRequest $request)
    {
        $postData = $request->getParsedBody();
        $userValidation = Validator::key('email',Validator::email())
                                   ->key('password',Validator::stringType()->notEmpty());
        try {
            $userValidation->assert($postData);
            $user = User::where('email',$postData['email'])->first();
            if($user) {
                if(password_verify($postData['password'],$user->password)) {
                    $_SESSION['userId'] = $user->id;
                    return new RedirectResponse('/admin');
                }else {
                    $responseMessage = "Bad Credentials";
                    $classMessage = "warning";
                }
            } else {
                $responseMessage = "Bad Credentials";
                $classMessage = "warning";
            }
        } catch (\Exception $e){
            $responseMessage = $e->getMessage();
            $classMessage = "warning";
        }

        return $this->renderHTML('login.twig',[
           'responseMessage' => $responseMessage,
           'classMessage' => $classMessage
        ]);
    }

    public function getLogout()
    {
        unset($_SESSION['userId']);
        return new RedirectResponse('/login');
    }
}
