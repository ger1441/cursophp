<?php
namespace App\Middlewares;

use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        $paths_protegidos = ['/admin','/jobs','/jobs/add','/projects','/projects/add','/users','/users/add'];
        $sessionUserId = $_SESSION['userId'] ?? null;


        if($path == "/login" && $sessionUserId){
            return new RedirectResponse('/admin');
        }else if(in_array($path,$paths_protegidos)){
            if(!$sessionUserId){
                return new RedirectResponse('/login');
            }
        }

        return $handler->handle($request);
    }
}