<?php
require_once '../vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

if(getenv('DEBUG')==='true'){
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);
}

use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;
use Laminas\Diactoros\Response;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabs\Harmony\Middleware\HttpHandlerRunnerMiddleware;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

// Crear archivo de LOG
// create a log channel
$log = new Logger('app');
$log->pushHandler(new StreamHandler(__DIR__ . "\..\logs\app.log", Logger::WARNING));

//Contenedor de Inyección de Dependencias
$container = new DI\Container();

//Conexion mediante ENV
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => getenv('DB_HOST'),
    'database'  => getenv('DB_NAME'),
    'username'  => getenv('DB_USER'),
    'password'  => getenv('DB_PASS'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();
// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

// Manejador para peticiones ( PSR7 )
$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

// Manejador de Routeo ( PSR7 )
$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();
$map->get('index', '/', [
    'App\Controllers\IndexController',
    'indexAction'
]);
$map->get('indexJobs', '/jobs', [
    'App\Controllers\JobsController',
    'indexAction',
]);
$map->get('addJob', '/jobs/add', [
    'App\Controllers\JobsController',
    'getAddJobAction',
]);
$map->post('saveJob', '/jobs/add', [
    'App\Controllers\JobsController',
    'getAddJobAction'
]);
$map->get('deleteJobs', '/jobs/delete/{id}', [
    'App\Controllers\JobsController',
    'deleteAction'
]);
$map->get('indexProjects','/projects',[
    'App\Controllers\ProjectsController',
    'indexAction'
]);
$map->get('addProject','/projects/add', [
    'App\Controllers\ProjectsController',
    'getAddProjectAction',
]);
$map->post('saveProject','/projects/add',[
    'App\Controllers\ProjectsController',
    'getAddProjectAction'
]);
$map->get('deleteProjects','/projects/delete',[
    'App\Controllers\ProjectsController',
    'deleteAction'
]);
$map->get('addUser','/users/add',[
    'App\Controllers\UsersController',
    'getAddUserAction',
]);
$map->post('saveUser','/users/add',[
    'App\Controllers\UsersController',
    'getAddUserAction'
]);
$map->get('showPass','/users/pass',[
    'App\Controllers\UsersController',
    'getPassword'
]);
$map->post('change','/users/changepass',[
    'App\Controllers\UsersController',
    'changePassword'
]);
$map->get('loginForm','/login',[
    'App\Controllers\AuthController',
    'getLogin'
]);
$map->get('logout','/logout',[
    'App\Controllers\AuthController',
    'getLogout'
]);
$map->post('loginAuth','/auth',[
    'App\Controllers\AuthController',
    'postAuth'
]);
$map->get('admin','/admin',[
    'App\Controllers\AdminController',
    'getIndex',
]);
$map->get('contactForm','/contact',[
    'App\Controllers\ContactController',
    'index',
]);
$map->post('contactSend','/contact/send',[
    'App\Controllers\ContactController',
    'send'
]);
$matcher = $routerContainer->getMatcher();

$route = $matcher->match($request);

if(!$route) {

     // ¿¿¿ Por qué no funciona lo siguiente ???
     # $controllerBase = new \App\Controllers\BaseController();
     # return $controllerBase->renderHTML('notFound.twig',['hola'=>"hi!"]);

    echo $request->getUri()->getPath()."<br>";
    echo 'No route';
} else{
    try {
        #Los Middlewares son 'capas' que permiten abstraer las partes de la aplicación
        #Checar funcionamiento del Middleware en Middleware -> DispatcherMiddleware -> process() [opc]
        #Harmony nos permitirá trabajar con Middlewares
        #SapiEmitter es un Middleware encargado de emitir la respuesta

        $harmony = new Harmony($request, new Response());
        $harmony->addMiddleware(new HttpHandlerRunnerMiddleware(new SapiEmitter()));
            if(getenv('DEBUG')==='true'){
                $harmony->addMiddleware(new \Franzl\Middleware\Whoops\WhoopsMiddleware());
            }
        $harmony->addMiddleware(new \App\Middlewares\AuthenticationMiddleware())
                ->addMiddleware(new Middlewares\AuraRouter($routerContainer))
                ->addMiddleware(new DispatcherMiddleware($container, 'request-handler'))
                ->run();
    } catch (Exception $e){
        $log->warning($e->getMessage());
        $emitter = new SapiEmitter();
        $emitter->emit(new Response\EmptyResponse(400));
    } catch (Error $e){
        $emitter = new SapiEmitter();
        $emitter->emit(new Response\EmptyResponse(500));
    }

    #Antes de implementar Harmony
    /*$handlerData = $route->handler;
    $controllerName = $handlerData['controller'];
    $actionName = $handlerData['action'];
    $needsAuth = $handlerData['auth'] ?? false;
    $pathAux = $request->getUri()->getPath();
    $controller = $container->get($controllerName);
    $response = $controller->$actionName($request);

    foreach ($response->getHeaders() as $name => $values)
    {
        foreach ($values as $value){
            //echo $name." : ".$value."-><br>";
            header(sprintf('%s: %s',$name,$value),false);
        }
    }
    http_response_code($response->getStatusCode());
    echo $response->getBody();*/
}
