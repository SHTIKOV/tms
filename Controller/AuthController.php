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

    public function login (ServerRequestInterface $request): ResponseInterface {
        $params = $request->getQueryParams ();
        $email = isset ($params['email']) ? $params['email'] : null;
        $password = isset ($params['password']) ? $params['password'] : null;
        if ($email && $password) {
            (new UserModel ($this->em))
                ->login ($email, $password);
            $this->em->flush ();

            $this->redirectTo ('/auth/check');
        }

        $messagses = [];
        if (count ($params)) {
            $messagses[] = 'Please check your entries';
        }

        return $this->render ('Auth/login.html.twig', [
            'messages' => $messagses
        ]);
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