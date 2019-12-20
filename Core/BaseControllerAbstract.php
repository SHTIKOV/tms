<?php

declare (strict_types=1);

namespace Core;

use Twig\Environment;
use Zend\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\EntityManager;
use Model\UserModel;


/**
 * Abstract controller
 * 
 * @author Константин Штыков (SHTIKOV)
 */
abstract class BaseControllerAbstract implements BaseControllerInterface {

    /** @var Environment */
    protected $twig;
    
    /** @var EntityManager */
    protected $em;
    
    /** @var UserModel|null */
    protected $userModel;
    
    /** @var Entity\User|null */
    protected $user;

    /** @var Response */
    protected $response;

    public function __construct (Environment $twig, EntityManager $em, ?UserModel $userModel = null) {
        $this->twig = $twig;
        $this->em = $em;
        $this->userModel = $userModel;
        $this->user = $userModel->getUserByToken ($_COOKIE['token']);
        $this->response = new Response;
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