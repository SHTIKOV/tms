<?php

namespace Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Model\TaskModel;
use League\Route\Http\Exception\BadRequestException;

class TasksController extends \Core\BaseControllerAbstract {

    public function index (ServerRequestInterface $request): ResponseInterface {
        $task = (new TaskModel ($this->em))
            ->get ($request->getQueryParams ());
        return $this->render ('Tasks/index.html.twig', [
            'tasks' => $task
        ]);
    }

    public function edit (ServerRequestInterface $request): ResponseInterface {
        $task = (new TaskModel ($this->em))
            ->edit ($request->getQueryParams ());

        $this->em->flush ();
        
        $this->response->getBody ()->write (json_encode ($task->jsonSerialize ()));
        return $this->response;
    }
    
    public function remove (ServerRequestInterface $request): ResponseInterface {
        $params = $request->getQueryParams ();
        if (!isset ($params['id']) || !$params['id']) {
            throw new BadRequestException ('Field "id" is required.');
        }
        
        $task = (new TaskModel ($this->em))->remove ($params['id']);
        $this->em->flush ();

        return $this->response;
    }
}