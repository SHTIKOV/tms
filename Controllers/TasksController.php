<?php

namespace Controllers;

use League\Route\Router;
use Twig\Environment;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TasksController {

    /** @var Router */
    protected $router;
    /** @var Environment */
    protected $twig;
    
    public function __construct (Router $router, Environment $twig) {
        $this->router = $router;
        $this->twig = $twig;

        $router->map ('GET', '/', function (ServerRequestInterface $request) use ($twig): ResponseInterface {
            $response = new \Zend\Diactoros\Response;
            $response->getBody ()->write ($twig->render ('Tasks/index.html.twig', ['name' => 'Fabien']));
            return $response;
        });
    }
}