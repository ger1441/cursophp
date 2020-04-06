<?php
namespace App\Controllers;

use App\Models\Message;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;

class ContactController extends BaseController
{
    public function index(){
        return $this->renderHTML('contacts/index.twig',['titlePage'=>"Contact"]);
    }

    public function send(ServerRequest $request){
        $data = $request->getParsedBody();

        $message = new Message();
        $message->name = $data['name'];
        $message->email = $data['email'];
        $message->message = $data['message'];
        $message->send = false;
        $message->save();

        return new RedirectResponse('/contact');
    }
}