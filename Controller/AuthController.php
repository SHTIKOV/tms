<?php

declare (strict_types=1);

namespace Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Model\UserModel;

/**
 * Auth controller
 * 
 * @author Константин Штыков (SHTIKOV)
 */
class AuthController extends \Core\BaseControllerAbstract {

    public function logout (ServerRequestInterface $request): ResponseInterface {
        (new UserModel ($this->em))
            ->logout ();
        $this->redirectTo ('/');
        return new Response;
    }

    public function login (ServerRequestInterface $request): ResponseInterface {
        $params = $request->getQueryParams ();
        $email = isset ($params['email']) ? $params['email'] : null;
        $password = isset ($params['password']) ? $params['password'] : null;
        if ($email && $password) {
            (new UserModel ($this->em))
                ->login ($email, $password);
            $this->em->flush ();
        }

        return $this->render ('Auth/login.html.twig');
    }

    public function register (ServerRequestInterface $request): ResponseInterface {
        $params = $request->getQueryParams ();
        $email = isset ($params['email']) ? $params['email'] : null;
        $password = isset ($params['password']) ? $params['email'] : null;
        if ($email && $password) {
            (new UserModel ($this->em))
                ->register ($params);
            $this->em->flush ();
        }

        return $this->render ('Auth/register.html.twig');
    }
}