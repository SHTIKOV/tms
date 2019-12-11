<?php

namespace Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Zend\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

abstract class BaseControllerAbstract {

    /** @var Environment */
    protected $twig;

    /** @var Response */
    protected $response;

    /** @var EntityManager */
    protected $em;

    public function __construct () {
        $this->twig = new Environment (new FilesystemLoader ('templates'), []);
        $this->response = new Response;
        $params = require __DIR__ . '/../app/config/Parameters.php';
        $this->em = EntityManager::create (
            $params['db'], 
            Setup::createAnnotationMetadataConfiguration (["/Entity"], $params['isDevMod'])
        );
    }

    public function render (string $template, array $vars = []): ResponseInterface {
        $this->response->getBody ()->write ($this->twig->render ($template, array_merge ($vars, [])));
        return $this->response;
    }

}