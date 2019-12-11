<?php declare(strict_types=1);

use League\Route\Router;
use Controller\{
    BaseController,
    TasksController,
    AdminController,
    AuthController,
};

$requestDispatcher = \Zend\Diactoros\ServerRequestFactory::fromGlobals (
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);
$router = new Router;

/**
 * Routers list
 */
$router->map ('GET', '/', [BaseController::class, 'index']);
/** Tasks */
$router->group ('/tasks', function (\League\Route\RouteGroup $route) {
    $route->map ('GET', '/', [TasksController::class, 'index']);
    $route->map ('GET', '/{id}', [TasksController::class, 'edit']);
});

/** Admin */
$router->group ('/admin', function (\League\Route\RouteGroup $route) {
    $route->map ('GET', '/', [AdminController::class, 'index']);
});

/** Auth */
$router->group ('/auth', function (\League\Route\RouteGroup $route) {
    $route->map ('GET', '/', [AuthController::class, 'login']);
    $route->map ('GET', '/register', [AuthController::class, 'register']);
});

(new Zend\HttpHandlerRunner\Emitter\SapiEmitter)->emit ($router->dispatch ($requestDispatcher));