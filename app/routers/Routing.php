<?php declare(strict_types=1);

use League\Route\Router;

$requestDispatcher = \Zend\Diactoros\ServerRequestFactory::fromGlobals (
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);
$router = new Router;

/**
 * Routers list
 */
$router->map ('GET', '/', [\Controller\BaseController::class, 'index']);
$router->group('/tasks', function (\League\Route\RouteGroup $route) {
    $route->map('GET', '/', [\Controller\TasksController::class, 'index']);
    $route->map('GET', '/{id}', [\Controller\TasksController::class, 'edit']);
});

(new Zend\HttpHandlerRunner\Emitter\SapiEmitter)->emit ($router->dispatch ($requestDispatcher));