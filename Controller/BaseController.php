<?php

declare (strict_types=1);

namespace Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * UBase controller
 * 
 * @author Константин Штыков (SHTIKOV)
 */
class BaseController extends \Core\BaseControllerAbstract {

    public function index (ServerRequestInterface $request): ResponseInterface {
        return $this->render ('Index/index.html.twig');
    }

    public function notFound (ServerRequestInterface $request): ResponseInterface {
        return $this->render ('Index/notFound.html.twig');
    }
}