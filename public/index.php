<?php
session_start();

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once "../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

use Aura\Router\RouterContainer;
use Illuminate\Database\Capsule\Manager as Capsule;
use Laminas\Diactoros\Response;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabs\Harmony\Middleware\LaminasEmitterMiddleware;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Middlewares\AuraRouter;

$container = new DI\Container(); //Inyector de Dependencias.
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

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

//echo $request->getUri()->getPath();

$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();
$map->get('index','/',[
    'App\Controllers\IndexController',
    'indexAction'
]);

/** AUTH */
$map->get('loginForm','/login',[
    'App\Controllers\AuthController',
    'getLogin'
]);
$map->post('authForm','/auth',[
    'App\Controllers\AuthController',
    'postLogin'
]);
$map->get('logout','/logout',[
    'App\Controllers\AuthController',
    'getLogout'
]);

/** ADMIN */
$map->get('admin','/admin',[
    'App\Controllers\AdminController',
    'getIndex',
    true,
]);


/** USERS */
$map->get('addUsers','/users/add',[
    'App\Controllers\UsersController',
    'getAddUserAction',
    true,
]);
$map->post('saveUsers','/users/add',[
    'App\Controllers\UsersController',
    'getAddUserAction'
]);

/** JOBS */
$map->get('addJobs','/jobs/add',[
    'App\Controllers\JobsController',
    'getAddJobAction',
    true,
]);
$map->post('saveJobs','/jobs/add',[
    'App\Controllers\JobsController',
    'getAddJobAction'
]);
$map->get('indexJobs','/jobs',[
   'App\Controllers\JobsController',
   'indexAction',
   true,
]);
$map->get('deleteJobs','/jobs/delete',[
    'App\Controllers\JobsController',
    'deleteAction',
    true,
]);

/** PROJECTS */
$map->get('addProjects','/projects/add',[
   'App\Controllers\ProjectsController',
    'getAddProjectAction',
    true,
]);
$map->post('saveProjects','/projects/add',[
    'App\Controllers\ProjectsController',
    'getAddProjectAction'
]);
$map->get('indexProjects','/projects',[
    'App\Controllers\ProjectsController',
    'indexAction',
    true,
]);
$map->get('deleteProjects','/projects/delete',[
    'App\Controllers\ProjectsController',
    'deleteAction',
    true,
]);

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if(!$route){
    echo "No route";
} else{
    /*$handlerData = $route->handler;
    $controllerName = $handlerData[0];
    $actionName = $handlerData[1];
    $needsAuth = $handlerData[2] ?? false;

    $sessionUserId = $_SESSION['userId'] ?? null;
    if($needsAuth && !$sessionUserId){
        $controllerName = 'App\Controllers\AuthController';
        $actionName = 'getLogout';
    }else{
        if($actionName == 'getLogin' && $sessionUserId){
            $controllerName = 'App\Controllers\AdminController';
            $actionName = 'getAdmin';
        }
    }*/

    $harmony = new Harmony($request, new Response());
    $harmony
        ->addMiddleware(new LaminasEmitterMiddleware(new SapiEmitter()))
        ->addMiddleware(new AuraRouter($routerContainer))
        ->addMiddleware(new DispatcherMiddleware($container,'request-handler'))
        ->run();

}

