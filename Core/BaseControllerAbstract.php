<?php

namespace Core;

use Twig\Environment;
use Twig\TwigFunction;
use Twig\Loader\FilesystemLoader;
use Zend\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Model\UserModel;

abstract class BaseControllerAbstract {

    /** @var Environment */
    protected $twig;

    /** @var Response */
    protected $response;

    /** @var EntityManager */
    protected $em;

    /** @var Entity\User|null */
    protected $user;

    public function __construct () {
        $this->twig = new Environment (new FilesystemLoader ('templates'), []);
        $this->response = new Response;
        $params = require __DIR__ . '/../app/config/Parameters.php';
        $this->em = EntityManager::create (
            $params['db'], 
            Setup::createAnnotationMetadataConfiguration (["/Entity"], $params['isDevMod'])
        );

        $userModel = new UserModel ($this->em);
        $this->user = $userModel->getUserByToken ($_COOKIE['token']);

        /**
         * Register functions to twig
         */
        $this->twig->addFunction (new TwigFunction ('dump', dump));
        $this->twig->addFunction (new TwigFunction ('json_encode', json_encode));
        $this->twig->addFunction (new TwigFunction ('json_decode', json_decode));
        $this->twig->addFunction (new TwigFunction ('isAuth', function () {
            return !is_null ($this->user);
        }));
    }

    public function render (string $template, array $params = []): ResponseInterface {
        $this->response->getBody ()->write ($this->twig->render ($template, array_merge ([
            'messages' => [],
            'user' => $this->user,
        ], $params)));
        return $this->response;
    }

    public function redirectTo (string $route): void {
        header ('Location: ' . $route);
        exit;
    }

}