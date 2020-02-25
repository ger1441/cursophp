<?php

namespace App\Controllers;


use App\Models\Message;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class ContactController extends BaseController
{
    public function index(){
        return $this->renderHTML('contact/index.twig');
    }

    public function send(ServerRequest $request){
        $requestData = $request->getParsedBody();
        $message = new Message();
        $message->name = $requestData['name'];
        $message->email = $requestData['email'];
        $message->message = $requestData['message'];
        $message->save();

        return new RedirectResponse('/contact');
    }
}