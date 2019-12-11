<?php

namespace Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TasksController extends \Core\BaseControllerAbstract {

    public function index (ServerRequestInterface $request): ResponseInterface {
        return $this->render ('Tasks/index.html.twig', [
            'tasks' => []
        ]);
    }

    public function edit (ServerRequestInterface $request): ResponseInterface {
        return $this->render ('Tasks/edit.html.twig', [
            'task' => []
        ]);
    }
}