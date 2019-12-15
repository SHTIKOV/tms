<?php

namespace Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Model\UserModel;

class AuthController extends \Core\BaseControllerAbstract {

    public function logout (ServerRequestInterface $request): ResponseInterface {
        (new UserModel ($this->em))
            ->logout ();
        $this->redirectTo ('/');
        return new Response;
    }

    public function loginPage (ServerRequestInterface $request): ResponseInterface {
        return $this->render ('Auth/login.html.twig');
    }

    public function login (ServerRequestInterface $request): ResponseInterface {
        $params = $request->getQueryParams ();
        $email = isset ($params['email']) ? $params['email'] : null;
        $password = isset ($params['password']) ? $params['password'] : null;
        //dump ($params);
        if ($email && $password) {
            (new UserModel ($this->em))
                ->login ($email, $password);
            $this->em->flush ();

            $this->redirectTo ('/auth/check');
        } else {
            throw new NotFoundException ('User not found');
        }

        return $params;
    }

    public function check (ServerRequestInterface $request): ResponseInterface {
        $isAuthed = (new UserModel ($this->em))
                ->check ($request->getCookieParams ());
        $this->redirectTo ($isAuthed ? '/tasks' : '/auth');
        return new Response;
    }

    public function register (ServerRequestInterface $request): ResponseInterface {
        $params = $request->getQueryParams ();
        $email = isset ($params['email']) ? $params['email'] : null;
        $password = isset ($params['password']) ? $params['email'] : null;
        if ($email && $password) {
            (new UserModel ($this->em))
                ->register ($params);
            $this->em->flush ();

            $this->redirectTo ('/auth');
        }

        return $this->render ('Auth/register.html.twig');
    }
}