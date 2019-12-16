<?php

namespace Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Model\TaskModel;
use Zend\Diactoros\Response;

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
}