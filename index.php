<?php

require 'vendor/autoload.php';
require_once __DIR__ . '/Controllers/TasksController.php';



$request = Zend\Diactoros\ServerRequestFactory::fromGlobals (
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$router = new League\Route\Router;
$twig = new Twig\Environment (new \Twig\Loader\FilesystemLoader ('templates'), []);

$controllers = [
    new \Controllers\TasksController ($router, $twig)
];



$response = $router->dispatch ($request);

(new Zend\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);