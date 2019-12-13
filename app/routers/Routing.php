<?php declare(strict_types=1);

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use League\Route\Router;
use League\Container\Container;
use Strategy\AppStrategy;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Twig\TwigFunction;
use Model\UserModel;
use Controller\{
    BaseController,
    TasksController,
    AuthController,
};

$params = require __DIR__ . '/../config/Parameters.php';
$twig = new Environment (new FilesystemLoader ('templates'), []);
$em = EntityManager::create (
    $params['db'], 
    Setup::createAnnotationMetadataConfiguration (["/Entity"], $params['isDevMod'])
);
$userModel = new UserModel ($em);

/**
 * Register functions for twig
 */
$twig->addFunction (new TwigFunction ('dump', dump));
$twig->addFunction (new TwigFunction ('json_encode', json_encode));
$twig->addFunction (new TwigFunction ('json_decode', json_decode));
$twig->addFunction (new TwigFunction ('isAuth', function () use ($userModel) {
    return !is_null ($userModel->getUserByToken ($_COOKIE['token']));
}));

/**
 * Dependency injections
 */
/** BaseController */
$container = (new Container);
$container
    ->add (BaseController::class)
    ->addArgument ($twig)
    ->addArgument ($em)
    ->addArgument ($userModel);

/** TasksController */
$container
    ->add (TasksController::class)
    ->addArgument ($twig)
    ->addArgument ($em)
    ->addArgument ($userModel);

/** AuthController */
$container
    ->add (AuthController::class)
    ->addArgument ($twig)
    ->addArgument ($em)
    ->addArgument ($userModel);

$strategy = (new AppStrategy)->setContainer ($container);
$router   = (new Router)->setStrategy ($strategy);
$router->middleware (new Middleware\AuthMiddleware);

/**
 * Routers list
 */
$router->map ('GET', '/404', [BaseController::class, 'notFound']);
$router->map ('GET', '/', [BaseController::class, 'index']);
/** Tasks */
$router->group ('/tasks', function (\League\Route\RouteGroup $route) {
    $route->map ('GET', '/', [TasksController::class, 'index']);
    $route->map ('GET', '/{id}', [TasksController::class, 'edit']);
});

/** Auth */
$router->group ('/auth', function (\League\Route\RouteGroup $route) {
    $route->map ('GET', '/', [AuthController::class, 'login']);
    $route->map ('GET', '/register', [AuthController::class, 'register']);
    $route->map ('GET', '/check', [AuthController::class, 'check']);
    $route->map ('GET', '/logout', [AuthController::class, 'logout']);
});

$requestDispatcher = \Zend\Diactoros\ServerRequestFactory::fromGlobals (
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);
(new Zend\HttpHandlerRunner\Emitter\SapiEmitter)->emit ($router->dispatch ($requestDispatcher));