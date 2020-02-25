<?php
namespace App\Middlewares;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if($request->getUri()->getPath()==='/admin'){
            $sessionUserId = $_SESSION['userId'] ?? null;
            if(!$sessionUserId){
                //return new EmptyResponse(401);
                return new RedirectResponse('/login');
            }
        }
        if($request->getUri()->getPath()==='/login'){
            $sessionUserId = $_SESSION['userId'] ?? null;
            if($sessionUserId){
                //return new EmptyResponse(401);
                return new RedirectResponse('/admin');
            }
        }
        return $handler->handle($request);
    }
}
