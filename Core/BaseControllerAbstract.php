<?php

namespace Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Zend\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;

abstract class BaseControllerAbstract {

    /** @var Environment */
    protected $twig;

    /** @var Response */
    protected $response;

    public function __construct () {
        $this->twig = new Environment (new FilesystemLoader ('templates'), []);
        $this->response = new Response;
    }

    public function render (string $template, array $vars = []): ResponseInterface {
        $this->response->getBody ()->write ($this->twig->render ($template, array_merge ($vars, [])));
        return $this->response;
    }

}