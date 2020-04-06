<?php
namespace App\Controllers;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class ContactController extends BaseController
{
    public function index(){
        return $this->renderHTML('contacts/index.twig',['titlePage'=>"Contact"]);
    }

    public function send(ServerRequest $request){
        $data = $request->getParsedBody();

        // Create the Transport
        $transport = (new Swift_SmtpTransport(getenv('SMTP_HOST'), getenv('SMTP_PORT')))
            ->setUsername(getenv('SMTP_USER'))
            ->setPassword(getenv('SMTP_PASS'))
        ;

        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        // Create a message
                $message = (new Swift_Message('Wonderful Subject'))
                    ->setFrom(['contact@midominio.com' => 'Contact Form'])
                    ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
                    ->setBody("Hi! You have a new message. Name: {$data['name']} Email: {$data['email']} Message: {$data['message']}");

        // Send the message
        $result = $mailer->send($message);
        return new RedirectResponse('/contact');
    }
}