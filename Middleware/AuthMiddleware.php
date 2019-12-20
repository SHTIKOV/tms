<?php 

declare(strict_types=1);

namespace Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Model\UserModel;

/**
 * AuthMiddleware
 * 
 * @author Константин Штыков (SHTIKOV)
 */
class AuthMiddleware implements MiddlewareInterface {

    const UNAVAILABLE_PATHS_FOR_REDIRECT = [
        '/',
        '/404',
        '/auth',
        '/auth/register',
        '/tasks',
        '/tasks/edit',
        '/tasks/load',
    ];
    
    public function process (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $params = require __DIR__ . '/../app/config/Parameters.php';
        $em = EntityManager::create (
            $params['db'], 
            Setup::createAnnotationMetadataConfiguration (["/Entity"], $params['isDevMod'])
        );
        $user = (new UserModel ($em))->getUserByToken ($_COOKIE['token']);
        
        /** If user authed */
        if (!is_null ($user)) {
            return $handler->handle ($request);
        } 
        /** If user not authed + rout not allowed */
        else if (!in_array ($request->getUri ()->getPath (), self::UNAVAILABLE_PATHS_FOR_REDIRECT)) {
            return new RedirectResponse ('/404');
        } 
        /** Done */
        else {
            return $handler->handle ($request);
        }
    }
}