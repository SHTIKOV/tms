<?php

declare (strict_types=1);

namespace Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use League\Route\Http\Exception\BadRequestException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Model\TaskModel;
use Entity\Task;

/**
 * Tasks controller
 * 
 * @author Константин Штыков (SHTIKOV)
 */
class TasksController extends \Core\BaseControllerAbstract {

    public function index (ServerRequestInterface $request): ResponseInterface {
        return $this->render ('Tasks/index.html.twig', [
            'defaultTask' => (new Task)->jsonSerialize (),
        ]);
    }

    public function edit (ServerRequestInterface $request): ResponseInterface {
        $task = (new TaskModel ($this->em))
            ->edit ($request->getQueryParams (), $this->user);

        $this->em->flush ();
        
        $this->response->getBody ()->write (json_encode ($task->jsonSerialize ()));
        return $this->response;
    }

    public function load (ServerRequestInterface $request): ResponseInterface {
        $tasksQB = (new TaskModel ($this->em))
            ->load ($request->getQueryParams ());

        $paginator = new Paginator ($tasksQB);
        $this->response->getBody ()->write (json_encode ([
            'count' => $paginator->count (),
            'data' => $tasksQB->getQuery ()->getArrayResult (),
        ]));
        return $this->response;
    }
    
    /**
     * Remove task
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws BadRequestException
     */
    public function remove (ServerRequestInterface $request): ResponseInterface {
        $params = $request->getQueryParams ();
        if (!isset ($params['id']) || empty ($params['id'])) {
            throw new BadRequestException ('Field "id" is required.');
        }
        
        $task = (new TaskModel ($this->em))->remove ((int) $params['id']);
        $this->em->flush ();

        return $this->response;
    }
}