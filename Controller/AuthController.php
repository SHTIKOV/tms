<?php

namespace Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends \Core\BaseControllerAbstract {

    public function login (ServerRequestInterface $request): ResponseInterface {
        return $this->render ('Auth/login.html.twig');
    }

    public function register (ServerRequestInterface $request): ResponseInterface {
        return $this->render ('Auth/register.html.twig');
    }
}