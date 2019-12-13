<?php

namespace Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BaseController extends \Core\BaseControllerAbstract {

    public function index (ServerRequestInterface $request): ResponseInterface {
        return $this->render ('Index/index.html.twig');
    }

    public function notFound (ServerRequestInterface $request): ResponseInterface {
        return $this->render ('Index/notFound.html.twig');
    }
}