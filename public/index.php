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
    'controller' => 'App\Controllers\IndexController',
    'action' => 'indexAction'
]);

/** AUTH */
$map->get('loginForm','/login',[
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogin'
]);
$map->post('authForm','/auth',[
    'controller' => 'App\Controllers\AuthController',
    'action' => 'postLogin'
]);
$map->get('logout','/logout',[
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogout'
]);

/** ADMIN */
$map->get('admin','/admin',[
    'controller' => 'App\Controllers\AdminController',
    'action' => 'getIndex',
    'auth' => true,
]);


/** USERS */
$map->get('addUsers','/users/add',[
    'controller' => 'App\Controllers\UsersController',
    'action' => 'getAddUserAction',
    'auth' => true,
]);
$map->post('saveUsers','/users/add',[
    'controller' => 'App\Controllers\UsersController',
    'action' => 'getAddUserAction'
]);

/** JOBS */
$map->get('addJobs','/jobs/add',[
    'controller' => 'App\Controllers\JobsController',
    'action' => 'getAddJobAction',
    'auth' => true,
]);
$map->post('saveJobs','/jobs/add',[
    'controller' => 'App\Controllers\JobsController',
    'action' => 'getAddJobAction'
]);

/** PROJECTS */
$map->get('addProjects','/projects/add',[
   'controller' => 'App\Controllers\ProjectsController',
    'action' => 'getAddProjectAction',
    'auth' => true,
]);
$map->post('saveProjects','/projects/add',[
    'controller' => 'App\Controllers\ProjectsController',
    'action' => 'getAddProjectAction'
]);

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if(!$route){
    echo "No route";
} else{
    $handlerData = $route->handler;
    $controllerName = $handlerData['controller'];
    $actionName = $handlerData['action'];
    $needsAuth = $handlerData['auth'] ?? false;

    $sessionUserId = $_SESSION['userId'] ?? null;
    if($needsAuth && !$sessionUserId){
        $controllerName = 'App\Controllers\AuthController';
        $actionName = 'getLogout';
    }else{
        if($actionName == 'getLogin' && $sessionUserId){
            $controllerName = 'App\Controllers\AdminController';
            $actionName = 'getAdmin';
        }
    }

    $controller = new $controllerName;
    $response = $controller->$actionName($request);

    foreach ($response->getHeaders() as $name => $values){
        foreach ($values as $value){
            header(sprintf('%s: %s',$name,$value),false);
        }
    }
    http_response_code($response->getStatusCode());
    echo $response->getBody();
}

function printElement($element) {
    //if(!$element->visible) return;

    echo '<li class="work-position">
                <h5>'.$element->title.'</h5>
                <p>'.$element->description.'</p>
                <p>'.$element->getDurationAsString().'</p>
                <strong>Achievements:</strong>
                <ul>
                    <li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>
                    <li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>
                    <li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>
                </ul>
              </li>';
}

